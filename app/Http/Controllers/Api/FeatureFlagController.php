<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FeatureFlagEvaluatorService;
use Illuminate\Http\Request;

class FeatureFlagController extends Controller
{
    protected FeatureFlagEvaluatorService $evaluator;

    public function __construct(FeatureFlagEvaluatorService $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function evaluate(Request $request, string $projectSlug, string $flagKey)
    {
        // Extract user context sent in the request (e.g., country, plan, user_id)
        $context = $request->input('context', []);

        // Run the evaluation service
        $isEnabled = $this->evaluator->evaluate($projectSlug, $flagKey, $context);

        return response()->json([
            'project' => $projectSlug,
            'flag' => $flagKey,
            'enabled' => $isEnabled,
        ]);
    }
}
