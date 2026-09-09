<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;

final class StationContext
{
    private const SESSION_KEY = 'workspace.station_code';

    public function current(?Request $request = null): string
    {
        $default = $this->default();

        if ($request === null && ! app()->bound('request')) {
            return $default;
        }

        $request ??= app('request');

        $employeeStation = $request->user()?->Employee?->location;
        if ($this->isSupported($employeeStation)) {
            $default = $employeeStation;
        }

        if (! $request->hasSession() || ! $this->canSwitch($request->user())) {
            return $default;
        }

        $selected = $request->session()->get(self::SESSION_KEY, $default);

        return $this->isSupported($selected) ? $selected : $default;
    }

    public function select(Request $request, string $station): void
    {
        abort_unless($this->canSwitch($request->user()), 403);
        abort_unless($this->isSupported($station), 422);

        $request->session()->put(self::SESSION_KEY, $station);
    }

    public function canSwitch(?User $user): bool
    {
        $level = $user?->Employee?->Designation?->level;

        return $level !== null
            && in_array((int) $level, config('workspace.station_switch_levels', [1, 2]), true);
    }

    /** @return array<int, array{value: string, label: string}> */
    public function options(): array
    {
        return collect(config('workspace.stations', []))
            ->map(static fn (string $label, string $value): array => compact('value', 'label'))
            ->values()
            ->all();
    }

    private function default(): string
    {
        $configured = strtolower((string) config('workspace.station_code', 'mnl'));

        return $this->isSupported($configured) ? $configured : 'mnl';
    }

    private function isSupported(mixed $station): bool
    {
        return is_string($station)
            && array_key_exists($station, config('workspace.stations', []));
    }
}
