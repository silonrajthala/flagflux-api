<?php

namespace App\Services;

use App\Models\FeatureFlag;
use Illuminate\Support\Facades\Cache;

class FeatureFlagEvaluatorService
{
    public function evaluate(string $projectSlug, string $flagKey, array $context = []): bool
    {
        $cacheKey = "flags:project:{$projectSlug}";

        // Cache all flags for this project for 60 seconds to ensure high performance
        $flags = Cache::remember($cacheKey, 60, function () use ($projectSlug) {
            return FeatureFlag::with('rules')
                ->whereHas('project', fn($q) => $q->where('slug', $projectSlug))
                ->get()
                ->keyBy('key');
        });

        // If the flag doesn't exist, default to false (disabled)
        if (!isset($flags[$flagKey])) {
            return false;
        }

        $flag = $flags[$flagKey];

        // If the master switch is off, it's disabled for everyone
        if (!$flag->is_enabled) {
            return false;
        }

        // If there are no custom rules, respect the global master switch
        if ($flag->rules->isEmpty()) {
            return true;
        }

        // Evaluate targeting rules against the provided user context
        foreach ($flag->rules as $rule) {
            $userValue = $context[$rule->attribute] ?? null;

            if ($this->matchesRule($rule, $userValue)) {
                return true; // Matches target rule, grant access
            }
        }

        // If rules exist but none matched, default to false
        return false;
    }

    protected function matchesRule($rule, $userValue): bool
    {
        if ($userValue === null) {
            return false;
        }

        return match ($rule->operator) {
            'equals' => $userValue === ($rule->values[0] ?? null),
            'in' => in_array($userValue, $rule->values),
            default => false,
        };
    }
}
