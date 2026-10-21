<?php

namespace App\Services;

use App\Models\FeatureFlag;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class FeatureFlagEvaluatorService
{
    public function evaluate(string $projectSlug, string $flagKey, array $context = []): bool
    {
        // Cache key for sub-millisecond evaluation performance
        $cacheKey = "flag_eval_{$projectSlug}_{$flagKey}_" . md5(json_encode($context));

        return Cache::remember($cacheKey, 60, function () use ($projectSlug, $flagKey, $context) {
            $project = Project::where('slug', $projectSlug)->first();

            if (!$project) {
                return false;
            }

            $flag = FeatureFlag::where('project_id', $project->id)
                ->where('key', $flagKey)
                ->with('rules')
                ->first();

            // 1. If flag doesn't exist or global master switch is off, return false
            if (!$flag || !$flag->is_enabled) {
                return false;
            }

            // 2. Evaluate Targeting Rules (if any exist)
            foreach ($flag->rules as $rule) {
                $contextValue = $context[$rule->attribute] ?? null;

                if ($contextValue === null) {
                    return false; // Attribute missing in context, fail rule
                }

                if ($rule->operator === 'equals' && $contextValue !== $rule->values[0]) {
                    return false;
                }

                if ($rule->operator === 'in' && !in_array($contextValue, $rule->values)) {
                    return false;
                }
            }

            // 3. Evaluate Percentage Rollout (Hash Bucketing)
            if ($flag->rollout_percentage < 100) {
                $userId = $context['user_id'] ?? $context['id'] ?? null;

                if (!$userId) {
                    return false; // Cannot bucket without a unique user identifier
                }

                // Generate a deterministic bucket between 0 and 99 using crc32
                $bucket = abs(crc32($userId . $flag->key)) % 100;

                if ($bucket >= $flag->rollout_percentage) {
                    return false; // User falls outside the rollout percentage
                }
            }

            return true;
        });
    }
}
