<?php

namespace App\Http\Requests\ProgramRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required"],
            "summary" => ["required"],
            'features' => ['required', 'array'],
            'features.*.icon' => ['required', 'string'],
            'features.*.feature_name' => ['required', 'string', 'max:25'],
        ];
    }

    public function getProgramData(): array
    {
        return collect($this->validated())->except('features')->merge([
            'created_by' => user()->id,
        ])->all();
    }

    public function getProgramFeaturesData(): array
    {
        return collect($this->features)->all();
    }
}
