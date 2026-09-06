<?php

namespace Tests\Unit;

use App\Support\ChartType;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

final class ChartTypeTest extends TestCase
{
    public function test_station_chart_only_exposes_the_official_chart(): void
    {
        $values = array_column(ChartType::options('station', 'mnl'), 'value');

        self::assertSame(['official'], $values);
    }

    public function test_cebu_uses_southside_instead_of_daily(): void
    {
        $values = array_column(ChartType::options('daily', 'cbu'), 'value');

        self::assertContains('local', $values);
        self::assertNotContains('daily', $values);
    }

    public function test_daily_playlist_and_throwback_keep_the_daily_flag(): void
    {
        self::assertSame(1, ChartType::flags('daily', 'playlist', 'mnl')['daily']);
        self::assertSame(1, ChartType::flags('daily', 'throwback', 'mnl')['daily']);
    }

    public function test_schedule_keys_round_trip_to_their_flags(): void
    {
        foreach (['official', 'daily', 'playlist', 'daily-playlist', 'local', 'throwback', 'daily-throwback', 'dropouts'] as $key) {
            self::assertSame($key, ChartType::key(ChartType::fromKey($key)));
        }
    }

    public function test_invalid_chart_type_is_rejected(): void
    {
        $this->expectException(ValidationException::class);

        ChartType::flags('daily', 'local', 'mnl');
    }
}
