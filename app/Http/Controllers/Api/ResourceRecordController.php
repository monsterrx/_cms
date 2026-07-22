<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ResourceDefinitionRegistry;
use App\Support\StationContext;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class ResourceRecordController extends Controller
{
    public function __construct(
        private ResourceDefinitionRegistry $registry,
        private StationContext $stations
    ) {
    }

    public function index(Request $request, string $section, string $item): JsonResponse
    {
        $resource = $this->registry->resolve($section, $item);
        $fields = $this->registry->fields($resource);
        $columns = array_column($fields, 'name');
        $tableFields = collect($fields)->where('table', true)->values()->all();
        $searchable = collect($fields)
            ->filter(static fn (array $field): bool => in_array($field['data_type'], [
                'char', 'enum', 'mediumtext', 'longtext', 'text', 'varchar',
            ], true))
            ->pluck('name')
            ->all();

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'sort' => ['nullable', 'string', Rule::in($columns)],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:10', 'max:100'],
        ]);

        $query = DB::table($resource['table']);
        $this->registry->applyScopes($query, $resource);

        if (($validated['search'] ?? '') !== '' && $searchable !== []) {
            $term = '%'.$this->escapeLike($validated['search']).'%';
            $query->where(static function (Builder $searchQuery) use ($searchable, $term): void {
                foreach ($searchable as $index => $column) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    $searchQuery->{$method}($column, 'like', $term);
                }
            });
        }

        $sort = $validated['sort'] ?? $this->defaultSort($columns);
        $direction = $validated['direction'] ?? 'desc';
        $perPage = (int) ($validated['per_page'] ?? 25);
        $paginator = $query->orderBy($sort, $direction)->paginate($perPage);
        $records = collect($paginator->items())
            ->map(fn (object $record): array => $this->registry->sanitizeRecord((array) $record))
            ->all();

        return $this->successResponse([
            'resource' => [
                'section' => $section,
                'item' => $item,
                'label' => $resource['label'],
                'read_only' => (bool) $resource['read_only'],
                'can_write' => $this->canWrite($request, $resource),
                'has_uploads' => $this->registry->hasUploads($fields),
            ],
            'records' => $records,
            'fields' => $fields,
            'table_fields' => $tableFields,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'sort' => $sort,
                'direction' => $direction,
            ],
        ], 'Records loaded successfully.');
    }

    public function show(Request $request, string $section, string $item, int $id): JsonResponse
    {
        $resource = $this->registry->resolve($section, $item);
        $record = $this->findRecord($resource, $id);

        return $this->successResponse([
            'record' => $this->registry->sanitizeRecord((array) $record),
        ], 'Record loaded successfully.');
    }

    public function store(Request $request, string $section, string $item): JsonResponse
    {
        $resource = $this->registry->resolve($section, $item);
        abort_unless($this->canWrite($request, $resource), 403);

        $fields = $this->registry->fields($resource);
        $payload = $this->validatedPayload($request, $resource, $fields, null);
        $columnNames = array_column($this->registry->columns($resource['table']), 'name');

        $this->applySystemValues($payload, $resource, $fields, true);
        if (in_array('created_at', $columnNames, true)) {
            $payload['created_at'] = now();
        }
        if (in_array('updated_at', $columnNames, true)) {
            $payload['updated_at'] = now();
        }

        $id = DB::table($resource['table'])->insertGetId($payload);
        $record = $this->findRecord($resource, $id);

        return $this->successResponse([
            'record' => $this->registry->sanitizeRecord((array) $record),
        ], 'Record created successfully.', 201);
    }

    public function update(Request $request, string $section, string $item, int $id): JsonResponse
    {
        $resource = $this->registry->resolve($section, $item);
        abort_unless($this->canWrite($request, $resource), 403);
        $this->findRecord($resource, $id);

        $fields = $this->registry->fields($resource);
        $payload = $this->validatedPayload($request, $resource, $fields, $id);
        $columnNames = array_column($this->registry->columns($resource['table']), 'name');
        $this->applySystemValues($payload, $resource, $fields, false);

        if (in_array('updated_at', $columnNames, true)) {
            $payload['updated_at'] = now();
        }

        if ($payload !== []) {
            $query = DB::table($resource['table'])->where('id', $id);
            $this->registry->applyScopes($query, $resource);
            $query->update($payload);
        }

        $record = $this->findRecord($resource, $id);

        return $this->successResponse([
            'record' => $this->registry->sanitizeRecord((array) $record),
        ], 'Record updated successfully.');
    }

    public function destroy(Request $request, string $section, string $item, int $id): JsonResponse
    {
        $resource = $this->registry->resolve($section, $item);
        abort_unless($this->canWrite($request, $resource), 403);
        $this->findRecord($resource, $id);

        $columnNames = array_column($this->registry->columns($resource['table']), 'name');
        $query = DB::table($resource['table'])->where('id', $id);
        $this->registry->applyScopes($query, $resource);

        if (in_array('deleted_at', $columnNames, true)) {
            $values = ['deleted_at' => now()];
            if (in_array('updated_at', $columnNames, true)) {
                $values['updated_at'] = now();
            }
            $query->update($values);
        } else {
            $query->delete();
        }

        return $this->successResponse(null, 'Record deleted successfully.');
    }

    /**
     * @param  array<string, mixed>  $resource
     * @param  array<int, array<string, mixed>>  $fields
     * @return array<string, mixed>
     */
    private function validatedPayload(
        Request $request,
        array $resource,
        array $fields,
        ?int $recordId
    ): array {
        $rules = [];
        $writableFields = collect($fields)->where('writable', true);

        foreach ($writableFields as $field) {
            $fieldRules = $recordId === null ? [] : ['sometimes'];
            $required = $recordId === null
                && ! $field['nullable']
                && $field['default'] === null
                && ! str_contains($field['extra'], 'auto_increment')
                && $field['name'] !== 'location';

            $fieldRules[] = $required ? 'required' : 'nullable';

            if ($field['type'] === 'checkbox') {
                $fieldRules[] = 'boolean';
            } elseif ($field['type'] === 'number') {
                $fieldRules[] = in_array($field['data_type'], ['decimal', 'double', 'float'], true)
                    ? 'numeric'
                    : 'integer';
            } elseif ($field['type'] === 'email') {
                $fieldRules[] = 'email';
            } elseif ($field['type'] === 'url') {
                $fieldRules[] = 'url';
            } elseif (in_array($field['type'], ['date', 'datetime-local'], true)) {
                $fieldRules[] = 'date';
            } else {
                $fieldRules[] = 'string';
            }

            if ($field['max_length']) {
                $fieldRules[] = 'max:'.$field['max_length'];
            }

            if ($field['key'] === 'UNI') {
                $unique = Rule::unique($resource['table'], $field['name']);
                $fieldRules[] = $recordId === null ? $unique : $unique->ignore($recordId);
            }

            if ($field['options'] !== []) {
                $fieldRules[] = Rule::in(array_column($field['options'], 'value'));
            }

            $rules[$field['name']] = $fieldRules;
        }

        $validator = Validator::make($request->all(), $rules);
        $validated = $validator->validate();

        return collect($validated)
            ->only($writableFields->pluck('name')->all())
            ->map(static fn (mixed $value) => $value === '' ? null : $value)
            ->all();
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $resource
     * @param array<int, array<string, mixed>> $fields
     */
    private function applySystemValues(array &$payload, array $resource, array $fields, bool $creating): void
    {
        $columnNames = array_column($fields, 'name');

        if (in_array('location', $columnNames, true)) {
            $payload['location'] = $this->stations->current();
        }

        foreach ($resource['filters'] as $filter) {
            if (($filter['operator'] ?? '=') === '=' && in_array($filter['column'] ?? null, $columnNames, true)) {
                $payload[$filter['column']] = $filter['value'] ?? null;
            }
        }

        if (! $creating) {
            return;
        }

        foreach ($fields as $field) {
            if (
                $field['type'] === 'file'
                && ! $field['nullable']
                && $field['default'] === null
            ) {
                $payload[$field['name']] = '';
            }
        }
    }

    /** @param array<string, mixed> $resource */
    private function canWrite(Request $request, array $resource): bool
    {
        if ($resource['read_only']) {
            return false;
        }

        $user = $request->user();
        $level = $user?->Employee?->Designation?->level;

        return $level !== null && in_array((int) $level, $resource['write_levels'], true);
    }

    /** @param array<string, mixed> $resource */
    private function findRecord(array $resource, int $id): object
    {
        $query = DB::table($resource['table'])->where('id', $id);
        $this->registry->applyScopes($query, $resource);
        $record = $query->first();

        abort_if($record === null, 404);

        return $record;
    }

    /** @param array<int, string> $columns */
    private function defaultSort(array $columns): string
    {
        if (in_array('created_at', $columns, true)) {
            return 'created_at';
        }

        return in_array('id', $columns, true) ? 'id' : $columns[0];
    }

    private function escapeLike(string $value): string
    {
        return addcslashes($value, '%_\\');
    }
}
