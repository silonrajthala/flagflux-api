<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeatureFlagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust if you have custom authorization policies
    }

    public function rules(): array
    {
        $projectId = $this->route('projectId');

        return [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:feature_flags,key,NULL,id,project_id,' . $projectId,
            'is_enabled' => 'boolean',
            'rollout_percentage' => 'nullable|integer|min:0|max:100',
        ];
    }
}
