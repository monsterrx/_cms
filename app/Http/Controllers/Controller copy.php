<?php

namespace App\Http\Controllers;

use App\Traits\AssetProcessors;
use App\Traits\ChartFunctions;
use App\Traits\JockFunctions;
use App\Traits\LogsUsers;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, SystemFunctions, MediaProcessors, AssetProcessors, ChartFunctions, JockFunctions, LogsUsers;
}
