<?php

namespace App\Http\Requests\Regulation;

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
            'title' => ['required', 'string', 'max:255', Rule::unique('regulations', 'title')->ignore($this->regulation)],
            'summary' => ['required', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // Max 5 MB
        ];
    }

    public function getData(): array
    {
        return collect($this->validated())->except('file')->all();
    }
}
