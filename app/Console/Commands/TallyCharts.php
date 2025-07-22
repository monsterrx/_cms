<?php

namespace App\Console\Commands;

use App\Traits\SystemFunctions;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Chart;
use App\Models\Tally;
use Illuminate\Support\Facades\Log;

class TallyCharts extends Command
{
    use SystemFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'charts:tally {type=daily}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tally the votes for charts (the daily survey or countdown top 7)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $chart_type = $this->argument('type');

        $dateNow = Carbon::now();

        $latestChartDate = Chart::query()
            ->whereNull('deleted_at')
            ->where('daily', 0)
            ->where('local', 0)
            ->where('is_posted', operator: 1)
            ->where('location', $this->getStationCode())
            ->select('dated')
            ->max('dated');

        $latestSurveyDate = Chart::query()
            ->select('dated')
            ->where('daily', 1)
            ->where('local', 0)
            ->where('is_posted', 1)
            ->whereNull('deleted_at')
            ->max('dated');

        if ($chart_type === 'charts') {
            $charts = Chart::query()
                ->where('daily', '=', 0)
                ->where('local', '=', 0)
                ->where('is_posted', '=', 1)
                ->where('dated', $latestChartDate)
                ->whereNull('deleted_at')
                ->orderBy('position')
                ->get();

            if ($charts->isEmpty()) {
                Log::info("No tallied charts for countdown top 7 on {$dateNow}");
            }

            $chartDate = $charts->first()->dated;

            // Check if a tally already exists for that date
            $existingTally = Tally::query()
                ->where('dated', $chartDate)
                ->where('chart_type', '=', 'charts')
                ->exists();

            if ($existingTally) {
                Log::info("A tally already exists for The Countdown dated {$chartDate}. Skipping insert.");
                $this->warn("A tally already exists for The Countdown dated {$chartDate}. Skipping insert.");
                return 0;
            }

            foreach ($charts as $chart) {
                $chart->total_votes = intval(intval($chart->online_votes) + intval($chart->phone_votes) + intval($chart->social_votes)) ?? 0;

                $tally = [
                    'result' => $chart->total_votes,
                    'last_result' => 0,
                    'chart_type' => 'charts',
                    'chart_id' => $chart->id,
                    'dated' => $chart->dated,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                Tally::create($tally);
            }

            Log::info("Tallied the votes for The Countdown Top 7 dated {$charts->first()->dated}");
            $this->info("Tallied the votes for The Countdown Top 7 dated {$charts->first()->dated}");
        }
        else if ($chart_type === 'daily') {
            $dailyCharts = Chart::query()
                ->where('daily', '=', 1)
                ->where('local', '=', 0)
                ->where('is_posted', '=', 1)
                ->where('dated', $latestSurveyDate)
                ->whereNull('deleted_at')
                ->orderBy('position')
                ->get();

            if ($dailyCharts->isEmpty()) {
                Log::info("No tallied charts for the daily survey on {$dateNow}");
            }

            $chartDate = $dailyCharts->first()->dated;

            // Check if a tally already exists for that date
            $existingTally = Tally::query()
                ->where('dated', $chartDate)
                ->where('chart_type', '=', 'daily')
                ->exists();

            if ($existingTally) {
                Log::info("A tally already exists for The Daily Survey dated {$chartDate}. Skipping insert.");
                $this->warn("A tally already exists for The Daily Survey dated {$chartDate}. Skipping insert.");
                return 0;
            }

            foreach ($dailyCharts as $chart) {
                $chart->total_votes = intval(intval($chart->online_votes) + intval($chart->phone_votes) + intval($chart->social_votes)) ?? 0;

                $tally = [
                    'result' => $chart->total_votes,
                    'last_result' => 0,
                    'chart_type' => 'daily',
                    'chart_id' => $chart->id,
                    'dated' => $chart->dated,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                Tally::create($tally);
            }

            Log::info("Tallied the votes for The Daily Survey dated {$chartDate}");
            $this->info("Tallied the votes for The Daily Survey dated {$chartDate}");
        }

        Log::info("Unknown chart type, accepting charts / daily.");
        $this->error("Unknown chart type, aborting operation accepting charts / daily.");
        return 0;
    }
}
