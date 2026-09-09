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
        'indieground_id' => 'indiegrounds',
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

    public function __construct(private StationContext $stations) {}

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

        if (DB::getDriverName() === 'sqlite') {
            return self::$columnCache[$table] = array_map(static fn (array $column): array => [
                'name' => $column['name'],
                'data_type' => strtolower($column['type_name']),
                'column_type' => strtolower($column['type']),
                'nullable' => $column['nullable'],
                'default' => $column['default'],
                'key' => $column['name'] === 'id' ? 'PRI' : '',
                'extra' => $column['auto_increment'] ? 'auto_increment' : '',
                'max_length' => null,
            ], Schema::getColumns($table));
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
            function (array $column) use ($readOnly, $resource): ?array {
                $name = $column['name'];

                if ($this->isSensitive($name)) {
                    return null;
                }

                if (in_array($name, $resource['hidden_fields'] ?? [], true)) {
                    return null;
                }

                $media = $this->isMediaField($name);
                $upload = $this->uploadDefinition($resource, $name);
                $technical = in_array($name, ['id', 'created_at', 'updated_at', 'deleted_at'], true);
                $systemManaged = $name === 'location';
                $relationTable = self::RELATION_TABLES[$name] ?? null;
                $richText = in_array($name, $resource['rich_text_fields'] ?? [], true);
                $configuredOptions = $resource['field_options'][$name]
                    ?? ($name === 'is_active' ? [
                        ['value' => 1, 'label' => 'Yes'],
                        ['value' => 0, 'label' => 'No'],
                    ] : []);
                $override = $resource['field_overrides'][$name] ?? [];
                $type = $richText
                    ? 'rich-text'
                    : ($configuredOptions !== [] ? 'select' : $this->fieldType($column, $relationTable, $media));

                return [
                    ...$column,
                    'label' => $resource['field_labels'][$name]
                        ?? ($name === 'is_active' ? 'Is Active?' : Str::headline($relationTable ? Str::beforeLast($name, '_id') : $name)),
                    'default' => $override['default'] ?? $column['default'],
                    'create_hidden' => $override['create_hidden'] ?? false,
                    'editor_resource' => ['section' => $resource['section'], 'item' => $resource['item']],
                    'help' => $resource['field_help'][$name] ?? null,
                    'type' => $override['type'] ?? $type,
                    'form' => ! $technical
                        && ! $systemManaged
                        && ! in_array($name, $resource['hidden_form_fields'] ?? [], true),
                    'table' => false,
                    'sortable' => true,
                    'virtual' => false,
                    'writable' => ! $readOnly
                        && ! $technical
                        && ! $systemManaged
                        && ! in_array($name, $resource['readonly_fields'] ?? [], true)
                        && (! $media || $upload !== null),
                    'upload_supported' => $media && $upload !== null,
                    'crop' => $upload === null ? null : [
                        'width' => (int) $upload['width'],
                        'height' => (int) $upload['height'],
                        'label' => $upload['label'] ?? null,
                    ],
                    'crop_variants' => $upload['variants'] ?? null,
                    'options' => $configuredOptions !== []
                        ? $configuredOptions
                        : ($relationTable
                            ? $this->relationOptions($relationTable)
                            : $this->enumOptions($column)),
                    'show_when' => $override['show_when'] ?? null,
                    'clears' => $override['clears'] ?? [],
                ];
            },
            $this->columns($resource['table'])
        )));

        foreach ($resource['related_uploads'] ?? [] as $name => $upload) {
            if (! is_array($upload)) {
                continue;
            }

            $fields[] = [
                'name' => $name,
                'data_type' => 'varchar',
                'column_type' => 'varchar(255)',
                'nullable' => true,
                'default' => null,
                'key' => '',
                'extra' => '',
                'max_length' => 255,
                'label' => Str::headline($name),
                'type' => 'file',
                'form' => true,
                'table' => false,
                'sortable' => false,
                'virtual' => true,
                'writable' => ! $readOnly,
                'upload_supported' => true,
                'crop' => [
                    'width' => (int) $upload['width'],
                    'height' => (int) $upload['height'],
                    'label' => $upload['label'] ?? null,
                ],
                'crop_variants' => $upload['variants'] ?? null,
                'options' => [],
            ];
        }

        foreach ($resource['virtual_fields'] ?? [] as $name => $definition) {
            if (! is_array($definition)) {
                continue;
            }

            $fields[] = [
                'name' => $name,
                'data_type' => 'virtual',
                'column_type' => 'virtual',
                'nullable' => (bool) ($definition['nullable'] ?? false),
                'default' => $definition['default'] ?? null,
                'key' => '',
                'extra' => '',
                'max_length' => $definition['max_length'] ?? null,
                'label' => $definition['label'] ?? Str::headline($name),
                'help' => $definition['help'] ?? null,
                'type' => $definition['type'] ?? 'text',
                'form' => (bool) ($definition['form'] ?? true),
                'table' => false,
                'sortable' => false,
                'virtual' => true,
                'writable' => ! $readOnly,
                'upload_supported' => false,
                'crop' => null,
                'crop_variants' => null,
                'options' => isset($definition['relation']) ? $this->relationOptions($definition['relation']) : ($definition['options'] ?? []),
                'show_when' => $definition['show_when'] ?? null,
                'clears' => $definition['clears'] ?? [],
            ];
        }

        $fieldOrder = $resource['field_order'] ?? [];
        if ($fieldOrder !== []) {
            $positions = array_flip($fieldOrder);
            usort($fields, static function (array $left, array $right) use ($positions): int {
                $leftPosition = $positions[$left['name']] ?? PHP_INT_MAX;
                $rightPosition = $positions[$right['name']] ?? PHP_INT_MAX;

                return $leftPosition <=> $rightPosition;
            });
        }

        $tableColumns = $resource['columns'] ?? $this->preferredTableColumns($fields);

        $hiddenTableFields = array_values(array_unique([
            'created_at',
            'updated_at',
            'deleted_at',
            'location',
            ...($resource['hidden_table_fields'] ?? []),
        ]));

        return array_map(static function (array $field) use ($tableColumns, $hiddenTableFields): array {
            $field['table'] = in_array($field['name'], $tableColumns, true)
                && ! in_array($field['name'], $hiddenTableFields, true);

            return $field;
        }, $fields);
    }

    /** @param array<int, array<string, mixed>> $fields */
    public function hasUploads(array $fields): bool
    {
        return collect($fields)->contains(static fn (array $field): bool => $field['type'] === 'file');
    }

    /**
     * @param  array<string, mixed>  $resource
     * @return array<string, mixed>|null
     */
    public function uploadDefinition(array $resource, string $field): ?array
    {
        $definition = $resource['uploads'][$field]
            ?? $resource['related_uploads'][$field]
            ?? null;

        return is_array($definition) ? $definition : null;
    }

    /** @param array<string, mixed> $record */
    public function sanitizeRecord(array $record, array $resource = []): array
    {
        $hidden = $resource['hidden_fields'] ?? [];

        return collect($record)
            ->except($hidden)
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

        $stationVia = $resource['station_via'] ?? null;
        if (is_array($stationVia)) {
            $table = $stationVia['table'] ?? null;
            $localKey = $stationVia['local_key'] ?? null;
            $foreignKey = $stationVia['foreign_key'] ?? 'id';
            $column = $stationVia['column'] ?? 'location';

            if (is_string($table) && is_string($localKey) && in_array($localKey, $columnNames, true)) {
                $query->whereExists(function ($related) use ($table, $localKey, $foreignKey, $column, $resource, $stationCode): void {
                    $related->selectRaw('1')
                        ->from($table)
                        ->whereColumn($table.'.'.$foreignKey, $resource['table'].'.'.$localKey)
                        ->where($table.'.'.$column, $stationCode);
                });
            }
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
        return (bool) preg_match('/(^|_)(image|photo|seal|cover|thumbnail|banner|background|icon|logo|wallpaper|file|path)($|_)/i', $name);
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

        if ($column['data_type'] === 'date' || (! in_array($column['data_type'], ['datetime', 'timestamp', 'time'], true) && in_array($column['name'], ['birthday', 'birthdate', 'start_date', 'end_date', 'date', 'dated'], true))) {
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
        if ($table === 'indiegrounds' && Schema::hasTable('artists')) {
            return DB::table('indiegrounds')
                ->join('artists', 'artists.id', '=', 'indiegrounds.artist_id')
                ->select('indiegrounds.id', 'artists.name')
                ->whereNull('indiegrounds.deleted_at')
                ->where('indiegrounds.location', $this->stations->current())
                ->orderBy('artists.name')
                ->limit(5000)
                ->get()
                ->map(static fn (object $record): array => [
                    'value' => $record->id,
                    'label' => $record->name ?: "Indieground Artist #{$record->id}",
                ])
                ->values()
                ->all();
        }

        if ($table === 'jocks' && Schema::hasTable('employees')) {
            return DB::table('jocks')
                ->join('employees', 'employees.id', '=', 'jocks.employee_id')
                ->whereNull('jocks.deleted_at')
                ->whereNull('employees.deleted_at')
                ->where('employees.location', $this->stations->current())
                ->orderBy('jocks.name')
                ->get(['jocks.id', 'jocks.name'])
                ->map(static fn (object $record): array => [
                    'value' => $record->id,
                    'label' => $record->name ?: "Jock #{$record->id}",
                ])
                ->values()
                ->all();
        }

        $labelColumns = array_values(array_intersect(
            ['first_name', 'last_name', 'name', 'title', 'email', 'employee_number', 'batch_number', 'slug_string'],
            $columns
        ));

        if ($labelColumns === []) {
            return [];
        }

        return DB::table($table)
            ->select(array_values(array_unique(['id', ...$labelColumns, ...($table === 'albums' ? ['artist_id'] : [])])))
            ->when(in_array('deleted_at', $columns, true), fn ($query) => $query->whereNull('deleted_at'))
            ->when(
                in_array('location', $columns, true),
                fn ($query) => $query->where('location', $this->stations->current())
            )
            ->orderBy($labelColumns[0])
            ->limit(5000)
            ->get()
            ->map(static function (object $record) use ($labelColumns, $table): array {
                $parts = collect($labelColumns)
                    ->map(static fn (string $column) => trim((string) ($record->{$column} ?? '')))
                    ->filter()
                    ->take(2);

                return [
                    'value' => $record->id,
                    'label' => $parts->implode(' ') ?: "Record #{$record->id}",
                    ...($table === 'albums' ? ['artist_id' => $record->artist_id] : []),
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
            'is_active',
            'status',
        ], $names));

        foreach ($fields as $field) {
            if (count($preferred) >= 7) {
                break;
            }

            if (
                ! in_array($field['name'], $preferred, true)
                && $field['type'] !== 'file'
                && $field['type'] !== 'textarea'
                && ! in_array($field['name'], ['created_at', 'updated_at', 'deleted_at', 'location'], true)
            ) {
                $preferred[] = $field['name'];
            }
        }

        return array_slice(array_values(array_unique($preferred)), 0, 7);
    }
}
