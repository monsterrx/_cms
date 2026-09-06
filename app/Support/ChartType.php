<?php

namespace App\Support;

use Illuminate\Database\Query\Builder;
use Illuminate\Validation\ValidationException;

final class ChartType
{
    /** @return array<int, array{value: string, label: string}> */
    public static function options(string $module, string $station): array
    {
        if ($module === 'dropouts') {
            return [['value' => 'dropouts', 'label' => 'Dropouts']];
        }

        if ($module === 'daily') {
            return $station === 'mnl'
                ? [
                    ['value' => 'daily', 'label' => 'Daily Survey Top 5'],
                    ['value' => 'playlist', 'label' => 'Playlist'],
                    ['value' => 'throwback', 'label' => 'Throwback'],
                ]
                : [
                    ['value' => 'local', 'label' => 'Southside Sounds'],
                    ['value' => 'playlist', 'label' => 'Playlist'],
                    ['value' => 'throwback', 'label' => 'Throwback'],
                ];
        }

        return [['value' => 'official', 'label' => "Station's Chart"]];
    }

    /** @return array{daily: int, playlist: int, local: int, throwback: int, is_dropped: int} */
    public static function flags(string $module, string $type, string $station): array
    {
        $valid = collect(self::options($module, $station))->pluck('value')->contains($type);
        if (! $valid) {
            throw ValidationException::withMessages(['type' => 'The selected chart type is not available for this station.']);
        }

        $flags = ['daily' => 0, 'playlist' => 0, 'local' => 0, 'throwback' => 0, 'is_dropped' => 0];
        if ($module === 'dropouts') {
            $flags['is_dropped'] = 1;
        } elseif ($type === 'local') {
            $flags['local'] = 1;
        } elseif ($type === 'throwback') {
            $flags['throwback'] = 1;
            $flags['daily'] = $module === 'daily' ? 1 : 0;
        } elseif ($type === 'playlist') {
            $flags['playlist'] = 1;
            $flags['daily'] = $module === 'daily' ? 1 : 0;
        } elseif ($type === 'daily') {
            $flags['daily'] = 1;
        }

        return $flags;
    }

    public static function apply(Builder $query, array $flags): void
    {
        if ($flags['is_dropped'] === 1) {
            $query->where('charts.is_dropped', 1);

            return;
        }

        foreach ($flags as $column => $value) {
            $query->where('charts.'.$column, $value);
        }
    }

    public static function key(array $flags): string
    {
        if ($flags['is_dropped']) {
            return 'dropouts';
        }
        if ($flags['local']) {
            return 'local';
        }
        if ($flags['throwback']) {
            return $flags['daily'] ? 'daily-throwback' : 'throwback';
        }
        if ($flags['playlist']) {
            return $flags['daily'] ? 'daily-playlist' : 'playlist';
        }

        return $flags['daily'] ? 'daily' : 'official';
    }

    /** @return array{daily: int, playlist: int, local: int, throwback: int, is_dropped: int} */
    public static function fromKey(string $key): array
    {
        $flags = ['daily' => 0, 'playlist' => 0, 'local' => 0, 'throwback' => 0, 'is_dropped' => 0];

        return match ($key) {
            'official' => $flags,
            'daily' => array_replace($flags, ['daily' => 1]),
            'playlist' => array_replace($flags, ['playlist' => 1]),
            'daily-playlist' => array_replace($flags, ['daily' => 1, 'playlist' => 1]),
            'local' => array_replace($flags, ['local' => 1]),
            'throwback' => array_replace($flags, ['throwback' => 1]),
            'daily-throwback' => array_replace($flags, ['daily' => 1, 'throwback' => 1]),
            'dropouts' => array_replace($flags, ['is_dropped' => 1]),
            default => throw ValidationException::withMessages(['chart_type' => 'The scheduled chart type is invalid.']),
        };
    }
}
