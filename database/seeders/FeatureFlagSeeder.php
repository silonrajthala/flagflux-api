<?php

namespace Database\Seeders;

use App\Models\Environment;
use App\Models\FeatureFlag;
use App\Models\FlagRule;
use App\Models\Project;
use Illuminate\Database\Seeder;

class FeatureFlagSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Sample Project
        $project = Project::create([
            'name' => 'Global E-Commerce Platform',
            'slug' => 'global-store',
        ]);

        // 2. Create Environments
        Environment::create(['project_id' => $project->id, 'name' => 'production']);
        Environment::create(['project_id' => $project->id, 'name' => 'staging']);

        // 3. Flag 1: Global Master Switch (Always Enabled)
        FeatureFlag::create([
            'project_id' => $project->id,
            'name' => 'New Navbar Design',
            'key' => 'new-navbar',
            'is_enabled' => true,
        ]);

        // 4. Flag 2: Regional Rollout (Targeted to Australia and Germany)
        $regionalFlag = FeatureFlag::create([
            'project_id' => $project->id,
            'name' => 'One-Click Apple Pay',
            'key' => 'one-click-apple-pay',
            'is_enabled' => true,
        ]);

        // Add targeting rule: Country must be 'AU' or 'DE'
        FlagRule::create([
            'feature_flag_id' => $regionalFlag->id,
            'attribute' => 'country',
            'operator' => 'in',
            'values' => ['AU', 'DE'],
        ]);
    }
}
