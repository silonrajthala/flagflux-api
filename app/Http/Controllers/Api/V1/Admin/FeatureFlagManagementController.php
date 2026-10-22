<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeatureFlagRequest;
use App\Http\Requests\UpdateFeatureFlagRequest;
use App\Models\FeatureFlag;

class FeatureFlagManagementController extends Controller
{
    // List all flags for a project
    public function index($projectId)
    {
        return response()->json([
            'flags' => FeatureFlag::where('project_id', $projectId)->with('rules')->get()
        ]);
    }

    // Create a new feature flag
    public function store(StoreFeatureFlagRequest $request, $projectId)
    {
        $validated = $request->validated();

        $flag = FeatureFlag::create([
            'project_id' => $projectId,
            'name' => $validated['name'],
            'key' => $validated['key'],
            'is_enabled' => $validated['is_enabled'] ?? false,
            'rollout_percentage' => $validated['rollout_percentage'] ?? null,
        ]);

        return response()->json([
            'message' => 'Feature flag created successfully.',
            'flag' => $flag
        ], 201);
    }

    // Update an existing flag
    public function update(UpdateFeatureFlagRequest $request, $id)
    {
        $flag = FeatureFlag::findOrFail($id);

        $flag->update($request->validated());

        return response()->json([
            'message' => 'Feature flag updated successfully.',
            'flag' => $flag
        ]);
    }

    // Delete a flag
    public function destroy($id)
    {
        $flag = FeatureFlag::findOrFail($id);
        $flag->delete();

        return response()->json([
            'message' => 'Feature flag deleted successfully.'
        ]);
    }
}
