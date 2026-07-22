<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\StationContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class StationController extends Controller
{
    public function update(Request $request, StationContext $stations): JsonResponse
    {
        abort_unless($stations->canSwitch($request->user()), 403);

        $validated = $request->validate([
            'station' => ['required', 'string', Rule::in(array_column($stations->options(), 'value'))],
        ]);

        $stations->select($request, $validated['station']);

        return $this->successResponse([
            'station' => $stations->current($request),
        ], 'Station changed successfully.');
    }
}
