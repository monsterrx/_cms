<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ResourceDefinitionRegistry;
use App\Support\ResourceImageStorage;
use App\Support\ResourcePresenter;
use App\Support\RichTextSanitizer;
use App\Support\StationContext;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

final class ResourceRecordController extends Controller
{
    public function __construct(
        private ResourceDefinitionRegistry $registry,
        private StationContext $stations,
        private ResourceImageStorage $images,
        private RichTextSanitizer $richText,
        private ResourcePresenter $presenter
    ) {
    }

    public function index(Request $request, string $section, string $item): JsonResponse
    {
        $resource = $this->registry->resolve($section, $item);
        $fields = $this->registry->fields($resource);
        $columns = collect($fields)->where('virtual', false)->pluck('name')->all();
        $tableColumnPositions = array_flip($resource['columns'] ?? []);
        $tableFields = collect($fields)
            ->where('table', true)
            ->sortBy(static fn (array $field): int => $tableColumnPositions[$field['name']] ?? PHP_INT_MAX)
            ->values()
            ->all();
        $searchable = collect($fields)
            ->filter(static fn (array $field): bool => ! $field['virtual'] && in_array(
                $field['data_type'],
                ['char', 'enum', 'mediumtext', 'longtext', 'text', 'varchar'],
                true
            ))
            ->pluck('name')
            ->all();

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'sort' => ['nullable', 'string', Rule::in($columns)],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:10', 'max:250'],
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

        $sortableCards = ($resource['presentation'] ?? null) === 'sortable-graphic-cards';
        $configuredSort = $resource['default_sort'] ?? null;
        $sort = $validated['sort'] ?? (is_string($configuredSort) && in_array($configuredSort, $columns, true)
            ? $configuredSort
            : ($sortableCards && in_array('number', $columns, true)
                ? 'number'
                : $this->defaultSort($columns)));
        $direction = $validated['direction'] ?? ($resource['default_direction'] ?? ($sortableCards ? 'asc' : 'desc'));
        $configuredPageSize = max(10, min(250, (int) ($resource['per_page'] ?? 25)));
        $perPage = (int) ($validated['per_page'] ?? ($sortableCards ? 100 : $configuredPageSize));
        $paginator = $query->orderBy($sort, $direction)->paginate($perPage);
        $records = collect($paginator->items())
            ->map(fn (object $record): array => $this->registry->sanitizeRecord((array) $record, $resource))
            ->all();
        $records = $this->presenter->decorate($resource, $records);

        return $this->successResponse([
            'resource' => [
                'section' => $section,
                'item' => $item,
                'label' => $resource['label'],
                'read_only' => (bool) $resource['read_only'],
                'can_write' => $this->canWrite($request, $resource),
                'has_uploads' => $this->registry->hasUploads($fields),
                'presentation' => $resource['presentation'] ?? 'table',
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
        $fields = $this->registry->fields($resource);
        $sanitized = $this->registry->sanitizeRecord((array) $record, $resource);
        $presented = $this->presenter->decorate($resource, [$sanitized])[0] ?? $sanitized;

        return $this->successResponse([
            'record' => $presented,
            'fields' => $fields,
            'resource' => [
                'section' => $section,
                'item' => $item,
                'label' => $resource['label'],
                'read_only' => (bool) $resource['read_only'],
                'can_write' => $this->canWrite($request, $resource),
                'has_uploads' => $this->registry->hasUploads($fields),
                'presentation' => $resource['presentation'] ?? 'table',
            ],
        ], 'Record loaded successfully.');
    }

    public function store(Request $request, string $section, string $item): JsonResponse
    {
        $resource = $this->registry->resolve($section, $item);
        abort_unless($this->canWrite($request, $resource), 403);

        $fields = $this->registry->fields($resource);
        $payload = $this->validatedPayload($request, $resource, $fields, null);
        $columnNames = array_column($this->registry->columns($resource['table']), 'name');
        $uploads = ['stored' => [], 'related' => []];

        try {
            $uploads = $this->storeUploadedImages($payload, $resource);
            $this->applySystemValues($request, $payload, $resource, $fields, true);
            if (in_array('created_at', $columnNames, true)) {
                $payload['created_at'] = now();
            }
            if (in_array('updated_at', $columnNames, true)) {
                $payload['updated_at'] = now();
            }

            $id = DB::transaction(function () use ($payload, $resource, $uploads): int {
                $id = DB::table($resource['table'])->insertGetId($payload);
                $this->applyRelatedImages($uploads['related'], $payload);

                return $id;
            });
        } catch (Throwable $exception) {
            $this->images->discard($uploads['stored']);
            throw $exception;
        }
        $record = $this->findRecord($resource, $id);

        return $this->successResponse([
            'record' => $this->registry->sanitizeRecord((array) $record, $resource),
        ], 'Record created successfully.', 201);
    }

    public function update(Request $request, string $section, string $item, int $id): JsonResponse
    {
        $resource = $this->registry->resolve($section, $item);
        abort_unless($this->canWrite($request, $resource), 403);
        $existingRecord = $this->findRecord($resource, $id);

        $fields = $this->registry->fields($resource);
        $payload = $this->validatedPayload($request, $resource, $fields, $id);
        $columnNames = array_column($this->registry->columns($resource['table']), 'name');
        $uploads = ['stored' => [], 'related' => []];

        try {
            $uploads = $this->storeUploadedImages($payload, $resource);
            $this->applySystemValues($request, $payload, $resource, $fields, false);

            if (in_array('updated_at', $columnNames, true)) {
                $payload['updated_at'] = now();
            }

            DB::transaction(function () use ($existingRecord, $id, $payload, $resource, $uploads): void {
                if ($payload !== []) {
                    $query = DB::table($resource['table'])->where('id', $id);
                    $this->registry->applyScopes($query, $resource);
                    $query->update($payload);
                }

                $this->applyRelatedImages($uploads['related'], $payload, $existingRecord);
            });
        } catch (Throwable $exception) {
            $this->images->discard($uploads['stored']);
            throw $exception;
        }

        $record = $this->findRecord($resource, $id);

        return $this->successResponse([
            'record' => $this->registry->sanitizeRecord((array) $record, $resource),
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
        $writableFields = collect($fields)
            ->where('writable', true)
            ->where('virtual', false);

        foreach ($writableFields as $field) {
            $fieldRules = $recordId === null ? [] : ['sometimes'];
            $required = $recordId === null
                && ! $field['nullable']
                && $field['default'] === null
                && ! str_contains($field['extra'], 'auto_increment')
                && $field['name'] !== 'location';

            $fieldRules[] = $required ? 'required' : 'nullable';

            if ($field['type'] === 'file') {
                $crop = $this->resolvedCrop($request, $field);
                $fieldRules[] = 'image';
                $fieldRules[] = 'mimes:jpg,jpeg,png,webp';
                $fieldRules[] = 'max:12288';
                $fieldRules[] = 'dimensions:width='.$crop['width'].',height='.$crop['height'];
            } elseif ($field['type'] === 'checkbox') {
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

            if ($field['max_length'] && $field['type'] !== 'file') {
                $fieldRules[] = 'max:'.$field['max_length'];
            }

            if ($field['key'] === 'UNI') {
                $unique = Rule::unique($resource['table'], $field['name']);
                $fieldRules[] = $recordId === null ? $unique : $unique->ignore($recordId);
            }

            if ($field['type'] !== 'file' && $field['options'] !== []) {
                $fieldRules[] = Rule::in(array_column($field['options'], 'value'));
            }

            $rules[$field['name']] = $fieldRules;
        }

        $validator = Validator::make($request->all(), $rules);
        $validated = $validator->validate();

        $payload = collect($validated)
            ->only($writableFields->pluck('name')->all())
            ->map(static fn (mixed $value) => $value === '' ? null : $value)
            ->all();

        foreach ($writableFields->where('type', 'rich-text') as $field) {
            if (array_key_exists($field['name'], $payload)) {
                $payload[$field['name']] = $this->richText->sanitize($payload[$field['name']]);
            }
        }

        return $payload;
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $resource
     * @param array<int, array<string, mixed>> $fields
     */
    private function applySystemValues(
        Request $request,
        array &$payload,
        array $resource,
        array $fields,
        bool $creating
    ): void
    {
        $columnNames = array_column($fields, 'name');
        $tableColumns = array_column($this->registry->columns($resource['table']), 'name');

        if (in_array('location', $columnNames, true)) {
            $payload['location'] = $this->stations->current();
        }

        foreach ($resource['filters'] as $filter) {
            if (($filter['operator'] ?? '=') === '=' && in_array($filter['column'] ?? null, $columnNames, true)) {
                $payload[$filter['column']] = $filter['value'] ?? null;
            }
        }

        foreach ($resource['generated_fields'] ?? [] as $field => $definition) {
            if (! in_array($field, $tableColumns, true) || ! is_array($definition)) {
                continue;
            }

            if (($definition['create_only'] ?? false) && ! $creating) {
                continue;
            }

            $source = $definition['source'] ?? null;
            if (is_string($source) && array_key_exists($source, $payload)) {
                $value = $payload[$source];
                $payload[$field] = match ($definition['transform'] ?? null) {
                    'studly' => Str::studly((string) $value),
                    'slug' => Str::slug((string) $value),
                    default => $value,
                };
            } elseif (array_key_exists('value', $definition) && $creating) {
                $payload[$field] = $definition['value'];
            } elseif (($definition['generator'] ?? null) === 'authenticated_employee_id' && $creating) {
                $employeeId = $request->user()?->employee_id;

                if (! is_numeric($employeeId) || (int) $employeeId < 1) {
                    throw ValidationException::withMessages([
                        $field => 'Your account is not linked to an employee record. Please contact an administrator.',
                    ]);
                }

                $payload[$field] = (int) $employeeId;
            } elseif (($definition['generator'] ?? null) === 'random_code' && $creating) {
                $length = max(6, min(32, (int) ($definition['length'] ?? 10)));
                $prefix = (string) ($definition['prefix'] ?? '');
                $suffix = (string) ($definition['suffix'] ?? '');
                do {
                    $generated = $prefix.Str::upper(Str::random($length)).$suffix;
                } while (DB::table($resource['table'])->where($field, $generated)->exists());
                $payload[$field] = $generated;
            }
        }

        if (! $creating) {
            return;
        }

        foreach ($fields as $field) {
            if (
                $field['type'] === 'file'
                && ! $field['upload_supported']
                && ! $field['nullable']
                && $field['default'] === null
            ) {
                $payload[$field['name']] = '';
            }
        }
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $resource
     * @return array{
     *     stored: array<int, array{name: string, path: string}>,
     *     related: array<int, array{field: string, name: string, definition: array<string, mixed>}>
     * }
     */
    private function storeUploadedImages(array &$payload, array $resource): array
    {
        $stored = [];
        $related = [];

        foreach ($payload as $field => $value) {
            if (! $value instanceof UploadedFile) {
                continue;
            }

            $definition = $this->registry->uploadDefinition($resource, $field);
            abort_if($definition === null, 422, 'Image uploads are not configured for this field.');

            $image = $this->images->store($value, $definition);
            $stored[] = $image;

            if (isset($definition['relation_field'], $definition['target_table'], $definition['target_column'])) {
                $related[] = [
                    'field' => $field,
                    'name' => $image['name'],
                    'definition' => $definition,
                ];
                unset($payload[$field]);
            } else {
                $payload[$field] = $image['name'];
            }
        }

        return ['stored' => $stored, 'related' => $related];
    }

    /**
     * @param array<int, array{field: string, name: string, definition: array<string, mixed>}> $images
     * @param array<string, mixed> $payload
     */
    private function applyRelatedImages(array $images, array $payload, ?object $existingRecord = null): void
    {
        foreach ($images as $image) {
            $definition = $image['definition'];
            $relationField = (string) $definition['relation_field'];
            $relationId = $payload[$relationField] ?? $existingRecord?->{$relationField} ?? null;

            if (! is_numeric($relationId)) {
                throw ValidationException::withMessages([
                    $image['field'] => 'Select the related artist before applying its image.',
                ]);
            }

            $targetTable = (string) $definition['target_table'];
            $targetColumn = (string) $definition['target_column'];
            $columns = array_column($this->registry->columns($targetTable), 'name');
            if (! in_array($targetColumn, $columns, true)) {
                throw ValidationException::withMessages([
                    $image['field'] => 'The related image field is not configured correctly.',
                ]);
            }

            $query = DB::table($targetTable)->where('id', (int) $relationId);
            if (in_array('location', $columns, true)) {
                $query->where('location', $this->stations->current());
            }

            if (! $query->exists()) {
                throw ValidationException::withMessages([
                    $image['field'] => 'The selected related artist could not be found for this station.',
                ]);
            }

            $query->update([$targetColumn => $image['name']]);
        }
    }

    /** @return array{width: int, height: int, label?: string|null} */
    private function resolvedCrop(Request $request, array $field): array
    {
        $variants = $field['crop_variants'] ?? null;
        $selector = is_array($variants) ? ($variants['selector'] ?? null) : null;
        $selected = is_string($selector) ? $request->input($selector) : null;
        $variant = is_string($selected) && is_array($variants[$selected] ?? null)
            ? $variants[$selected]
            : null;

        return $variant ?? $field['crop'];
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
