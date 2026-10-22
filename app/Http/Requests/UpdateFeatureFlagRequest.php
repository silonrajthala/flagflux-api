<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeatureFlagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'is_enabled' => 'sometimes|boolean',
            'rollout_percentage' => 'nullable|integer|min:0|max:100',
        ];
    }
}
