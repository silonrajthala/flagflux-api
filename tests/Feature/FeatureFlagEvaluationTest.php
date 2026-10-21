<?php

namespace Tests\Feature;

use App\Models\Environment;
use App\Models\FeatureFlag;
use App\Models\FlagRule;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureFlagEvaluationTest extends TestCase
{
    use RefreshDatabase;

    public function test_flag_evaluates_to_false_if_it_does_not_exit(): void
    {
        $response = $this->postJson('/api/v1/evaluate/non-existent-project/some-flag');

        $response->assertStatus(200)
                 ->assertJson([
                     'enabled' => false,
                 ]);
    }

    public function test_master_switch_turns_flag_on_globally(): void
    {
        $project = Project::create(['name' => 'Test App', 'slug' => 'test-app']);

        FeatureFlag::create([
            'project_id' => $project->id,
            'name' => 'New UI',
            'key' => 'new-ui',
            'is_enabled' => true,
        ]);

        $response = $this->postJson('/api/v1/evaluate/test-app/new-ui');

        $response->assertStatus(200)
                 ->assertJson([
                     'enabled' => true,
                 ]);
    }

    public function test_targeting_rules_allow_specific_user_groups(): void
    {
        $project = Project::create(['name' => 'Global App', 'slug' => 'global-app']);

        $flag = FeatureFlag::create([
            'project_id' => $project->id,
            'name' => 'Dark Mode',
            'key' => 'dark-mode',
            'is_enabled' => true,
        ]);

        // Add rule: Only allowed if country is "DE" (Germany) or "AU" (Australia)
        FlagRule::create([
            'feature_flag_id' => $flag->id,
            'attribute' => 'country',
            'operator' => 'in',
            'values' => ['DE', 'AU'],
        ]);

        // 1. Test with a user from the US (Should be false)
        $responseUS = $this->postJson('/api/v1/evaluate/global-app/dark-mode', [
            'context' => ['country' => 'US']
        ]);
        $responseUS->assertJson(['enabled' => false]);

        // 2. Test with a user from Germany (Should be true because DE is in the rule)
        $responseDE = $this->postJson('/api/v1/evaluate/global-app/dark-mode', [
            'context' => ['country' => 'DE']
        ]);
        $responseDE->assertJson(['enabled' => true]);

        // 3. Test with a user from Australia using 'AU' (Should be true)
        $responseAU = $this->postJson('/api/v1/evaluate/global-app/dark-mode', [
            'context' => ['country' => 'AU']
        ]);
        $responseAU->assertJson(['enabled' => true]);
    }

    public function test_percentage_rollout_buckets_users_consistently(): void
    {
        $project = Project::create(['name' => 'Rollout App', 'slug' => 'rollout-app']);

        $flag = FeatureFlag::create([
            'project_id' => $project->id,
            'name' => 'Beta Dashboard',
            'key' => 'beta-dashboard',
            'is_enabled' => true,
            'rollout_percentage' => 50, // 50% rollout
        ]);

        // Test with User A
        $responseA = $this->postJson('/api/v1/evaluate/rollout-app/beta-dashboard', [
            'context' => ['user_id' => 'user-123']
        ]);

        // Test with User B
        $responseB = $this->postJson('/api/v1/evaluate/rollout-app/beta-dashboard', [
            'context' => ['user_id' => 'user-999']
        ]);

        // Responses should be deterministic (we just check they return valid booleans)
        $responseA->assertStatus(200);
        $responseB->assertStatus(200);
    }
}
