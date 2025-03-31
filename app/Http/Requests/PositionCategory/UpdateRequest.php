<?php

namespace App\Http\Requests\PositionCategory;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'position_category_name' => [
                'required',
                'max:25',
                Rule::unique('position_categories', 'position_category_name')->ignore($this->position_category)
            ],
            'position_category_summary' => ['nullable'],
        ];
    }
}
