<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\DesignationNavigation;
use App\Support\ResourceDefinitionRegistry;
use App\Support\ResourceImageStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EditorImageController extends Controller
{
    public function __invoke(Request $request, ResourceDefinitionRegistry $registry, ResourceImageStorage $images): JsonResponse
    {
        $validated = $request->validate([
            'section' => ['sometimes', 'string'],
            'item' => ['sometimes', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:12288'],
        ]);
        $section = $validated['section'] ?? 'digital-content-programs';
        $item = $validated['item'] ?? 'articles';
        $resource = $registry->resolve($section, $item);
        abort_unless(app(DesignationNavigation::class)->canWrite($request->user(), $section, $item) && ! $resource['read_only'], 403);
        $directory = $resource['uploads']['image']['directory'] ?? 'articles';
        $stored = $images->store($validated['image'], ['directory' => $directory]);

        return $this->successResponse(['url' => url('images/'.$directory.'/'.$stored['name'])], 'Image uploaded.', 201);
    }
}
