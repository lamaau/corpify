<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SettingRequest extends FormRequest
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
            'app_hero_carousel' => ['array'],
            'app_hero_carousel.*.title' => ['required', 'string', 'max:255'],
            'app_hero_carousel.*.summary' => ['required', 'string', 'max:255'],
            'app_hero_carousel.*.file' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp'],
            'app_hero_carousel.*.sort' => ['nullable', 'integer', 'min:1'],

            'app_box' => ['nullable', 'array'],
            'app_box.*.icon' => ['nullable', 'string', 'max:50'],
            'app_box.*.summary' => ['nullable', 'string', 'max:255'],
            'app_box.*.sort' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function getData(): Collection
    {
        return collect($this->validated());
    }
}
