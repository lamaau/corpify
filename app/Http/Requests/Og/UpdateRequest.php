<?php

namespace App\Http\Requests\Og;

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
            'user_id' => ['required', Rule::exists('users', 'id')],
            'parent_id' => ['required', Rule::exists('users', 'id')],
            'position_id' => ['required', Rule::exists('positions', 'id')],
            'position_category_id' => ['required', Rule::exists('position_categories', 'id')],
        ];
    }
}
