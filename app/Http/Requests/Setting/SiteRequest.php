<?php

namespace App\Http\Requests\Setting;

use App\Enums\SettingContext;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SiteRequest extends FormRequest
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
        $context = $this->getContext();

        if (!$context) {
            throw ValidationException::withMessages([
                'context' => 'The context is required.',
            ]);
        }

        $rules = match ($context) {
            SettingContext::CONTACT() => [
                'phone' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ],
            SettingContext::ADDRESS() => [
                'building_name' => 'required|string|max:255',
                'address_line_1' => 'required|string|max:255',
                'address_line_2' => 'required|string|max:255',
            ],
            SettingContext::SOCIAL_MEDIA() => [
                'social_media' => ['required', 'array'],
                'social_media.*.icon' => ['required', 'string', 'max:255'],
                'social_media.*.name' => ['required', 'string', 'max:255'],
                'social_media.*.link' => ['required', 'url', 'max:255'],
            ],
        };

        return array_merge($rules, [
            'context' => ['required', new Enum(SettingContext::class)],
        ]);
    }

    public function getData(): Collection
    {
        return collect($this->validated());
    }

    public function getContext(): ?string
    {
        return $this->context;
    }
}
