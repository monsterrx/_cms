<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ResourceDefinitionRegistry
{
    /** @var array<string, string> */
    private const RELATION_TABLES = [
        'album_id' => 'albums',
        'artist_id' => 'artists',
        'batch_id' => 'batches',
        'category_id' => 'categories',
        'contest_id' => 'giveaways',
        'designation_id' => 'designations',
        'employee_id' => 'employees',
        'genre_id' => 'genres',
        'giveaway_id' => 'giveaways',
        'jock_id' => 'jocks',
        'music_awards_release_id' => 'music_awards_releases',
        'release_id' => 'music_awards_releases',
        'school_id' => 'schools',
        'show_id' => 'shows',
        'song_id' => 'songs',
        'sponsor_id' => 'sponsors',
        'student_id' => 'students',
        'user_id' => 'users',
    ];

    /** @var array<string, array<int, array<string, mixed>>> */
    private static array $columnCache = [];

    public function __construct(private StationContext $stations)
    {
    }

    /** @return array<string, mixed> */
    public function resolve(string $section, string $item): array
    {
        $resource = config("workspace.resources.{$section}.{$item}");

        if (! is_array($resource) || ! isset($resource['table']) || ! Schema::hasTable($resource['table'])) {
            throw new NotFoundHttpException('The requested resource is not configured.');
        }

        return [
            'section' => $section,
            'item' => $item,
            'read_only' => false,
            'write_levels' => [1, 2],
            'filters' => [],
            ...$resource,
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function columns(string $table): array
    {
        if (isset(self::$columnCache[$table])) {
            return self::$columnCache[$table];
        }

        $columns = DB::select(
            'SELECT COLUMN_NAME, DATA_TYPE, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, COLUMN_KEY, EXTRA, CHARACTER_MAXIMUM_LENGTH
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
             ORDER BY ORDINAL_POSITION',
            [DB::getDatabaseName(), $table]
        );

        return self::$columnCache[$table] = array_map(static fn (object $column): array => [
            'name' => $column->COLUMN_NAME,
            'data_type' => strtolower($column->DATA_TYPE),
            'column_type' => strtolower($column->COLUMN_TYPE),
            'nullable' => $column->IS_NULLABLE === 'YES',
            'default' => $column->COLUMN_DEFAULT,
            'key' => $column->COLUMN_KEY,
            'extra' => strtolower($column->EXTRA ?? ''),
            'max_length' => $column->CHARACTER_MAXIMUM_LENGTH === null
                ? null
                : (int) $column->CHARACTER_MAXIMUM_LENGTH,
        ], $columns);
    }

    /**
     * @param  array<string, mixed>  $resource
     * @return array<int, array<string, mixed>>
     */
    public function fields(array $resource): array
    {
        $readOnly = (bool) $resource['read_only'];
        $fields = array_values(array_filter(array_map(
            function (array $column) use ($readOnly): ?array {
                $name = $column['name'];

                if ($this->isSensitive($name)) {
                    return null;
                }

                $media = $this->isMediaField($name);
                $technical = in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'], true);
                $systemManaged = $name === 'location';
                $relationTable = self::RELATION_TABLES[$name] ?? null;
                $type = $this->fieldType($column, $relationTable, $media);

                return [
                    ...$column,
                    'label' => Str::headline($name),
                    'type' => $type,
                    'form' => ! $technical && ! $systemManaged,
                    'table' => false,
                    'writable' => ! $readOnly && ! $technical && ! $systemManaged && ! $media,
                    'upload_supported' => ! $media,
                    'options' => $relationTable
                        ? $this->relationOptions($relationTable)
                        : $this->enumOptions($column),
                ];
            },
            $this->columns($resource['table'])
        )));

        $tableColumns = $resource['columns'] ?? $this->preferredTableColumns($fields);

        return array_map(static function (array $field) use ($tableColumns): array {
            $field['table'] = in_array($field['name'], $tableColumns, true);

            return $field;
        }, $fields);
    }

    /** @param array<int, array<string, mixed>> $fields */
    public function hasUploads(array $fields): bool
    {
        return collect($fields)->contains(static fn (array $field): bool => $field['type'] === 'file');
    }

    /** @param array<string, mixed> $record */
    public function sanitizeRecord(array $record): array
    {
        return collect($record)
            ->reject(fn (mixed $value, string $key): bool => $this->isSensitive($key))
            ->all();
    }

    /** @param array<string, mixed> $resource */
    public function applyScopes($query, array $resource): void
    {
        $columnNames = array_column($this->columns($resource['table']), 'name');

        if (in_array('deleted_at', $columnNames, true)) {
            $query->whereNull('deleted_at');
        }

        $stationCode = $this->stations->current();
        if ($stationCode && in_array('location', $columnNames, true)) {
            $query->where('location', $stationCode);
        }

        foreach ($resource['filters'] as $filter) {
            if (! in_array($filter['column'] ?? null, $columnNames, true)) {
                continue;
            }

            $query->where(
                $filter['column'],
                $filter['operator'] ?? '=',
                $filter['value'] ?? null
            );
        }

        if (($resource['archive_logs'] ?? false) && in_array('created_at', $columnNames, true)) {
            $query->whereYear('created_at', '<', now()->year);
        }
    }

    private function isSensitive(string $name): bool
    {
        return in_array($name, [
            'password',
            'remember_token',
            'token',
            'access_token',
            'refresh_token',
        ], true);
    }

    private function isMediaField(string $name): bool
    {
        return (bool) preg_match('/(^|_)(image|photo|cover|thumbnail|banner|background|icon|logo|wallpaper|file|path)($|_)/i', $name);
    }

    /** @param array<string, mixed> $column */
    private function fieldType(array $column, ?string $relationTable, bool $media): string
    {
        if ($media) {
            return 'file';
        }

        if ($relationTable || $column['data_type'] === 'enum') {
            return 'select';
        }

        if ($column['data_type'] === 'tinyint' && $column['column_type'] === 'tinyint(1)') {
            return 'checkbox';
        }

        if (in_array($column['data_type'], ['text', 'mediumtext', 'longtext'], true)) {
            return 'textarea';
        }

        if ($column['data_type'] === 'date') {
            return 'date';
        }

        if (in_array($column['data_type'], ['datetime', 'timestamp'], true)) {
            return 'datetime-local';
        }

        if (in_array($column['data_type'], ['bigint', 'decimal', 'double', 'float', 'int', 'integer', 'mediumint', 'smallint', 'tinyint'], true)) {
            return 'number';
        }

        if (str_contains($column['name'], 'email')) {
            return 'email';
        }

        if (str_contains($column['name'], 'url') || str_contains($column['name'], 'link')) {
            return 'url';
        }

        return 'text';
    }

    /** @param array<string, mixed> $column */
    private function enumOptions(array $column): array
    {
        if ($column['data_type'] !== 'enum') {
            return [];
        }

        preg_match_all("/'((?:[^'\\\\]|\\\\.)*)'/", $column['column_type'], $matches);

        return array_map(static fn (string $value): array => [
            'value' => stripcslashes($value),
            'label' => Str::headline(stripcslashes($value)),
        ], $matches[1] ?? []);
    }

    /** @return array<int, array{value: int|string, label: string}> */
    private function relationOptions(string $table): array
    {
        if (! Schema::hasTable($table)) {
            return [];
        }

        $columns = array_column($this->columns($table), 'name');
        $labelColumns = array_values(array_intersect(
            ['first_name', 'last_name', 'name', 'title', 'email', 'employee_number', 'batch_number', 'slug_string'],
            $columns
        ));

        if ($labelColumns === []) {
            return [];
        }

        return DB::table($table)
            ->select(array_values(array_unique(['id', ...$labelColumns])))
            ->when(in_array('deleted_at', $columns, true), fn ($query) => $query->whereNull('deleted_at'))
            ->orderBy($labelColumns[0])
            ->limit(5000)
            ->get()
            ->map(static function (object $record) use ($labelColumns): array {
                $parts = collect($labelColumns)
                    ->map(static fn (string $column) => trim((string) ($record->{$column} ?? '')))
                    ->filter()
                    ->take(2);

                return [
                    'value' => $record->id,
                    'label' => $parts->implode(' ') ?: "Record #{$record->id}",
                ];
            })
            ->values()
            ->all();
    }

    /** @param array<int, array<string, mixed>> $fields */
    private function preferredTableColumns(array $fields): array
    {
        $names = array_column($fields, 'name');
        $preferred = array_values(array_intersect([
            'id',
            'employee_number',
            'first_name',
            'last_name',
            'name',
            'title',
            'email',
            'batch_number',
            'position',
            'dated',
            'location',
            'is_active',
            'status',
            'created_at',
        ], $names));

        foreach ($fields as $field) {
            if (count($preferred) >= 7) {
                break;
            }

            if (
                ! in_array($field['name'], $preferred, true)
                && $field['type'] !== 'file'
                && $field['type'] !== 'textarea'
                && $field['name'] !== 'deleted_at'
            ) {
                $preferred[] = $field['name'];
            }
        }

        return array_slice(array_values(array_unique($preferred)), 0, 7);
    }
}
