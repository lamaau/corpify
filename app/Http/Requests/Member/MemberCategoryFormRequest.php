<?php

namespace App\Http\Requests\Member;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class MemberCategoryFormRequest extends FormRequest
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
            'member_category_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('member_categories', 'member_category_name')->ignore($this->memberCategory)
            ]
        ];
    }
}
