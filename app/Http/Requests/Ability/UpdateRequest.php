<?php

namespace App\Http\Requests\Ability;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'name' => ['required', Rule::unique('roles', 'name')->ignore($this->role)],
            'summary' => ['nullable', 'max:100'],
            'abilities' => ['required', 'array'],
        ];
    }

    public function getRoleData(): array
    {
        return $this->except(['abilities']);
    }

    public function getAbilityData(): array
    {
        return $this->only('abilities')['abilities'];
    }
}
