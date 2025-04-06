<?php

namespace App\Http\Requests\Setting;

use App\Rules\FileOrURL;
use Illuminate\Support\Str;
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
            SettingContext::Contact() => [
                'phone' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ],
            SettingContext::Address() => [
                'building_name' => 'required|string|max:255',
                'address_line_1' => 'required|string|max:255',
                'address_line_2' => 'required|string|max:255',
            ],
            SettingContext::SocialMedia() => [
                'social_media' => ['required', 'array'],
                'social_media.*.icon' => ['required', 'string', 'max:255'],
                'social_media.*.name' => ['required', 'string', 'max:255'],
                'social_media.*.link' => ['required', 'url', 'max:255'],
            ],
            SettingContext::HeroCaraouselImage() => [
                'hero_carousel_image' => ['required', 'array'],
                'hero_carousel_image.*.title' => ['required', 'string', 'max:255'],
                'hero_carousel_image.*.summary' => ['required', 'string', 'max:255'],
                'hero_carousel_image.*.file' => ['required', new FileOrURL(['jpeg', 'jpg', 'png'])],
            ],
            SettingContext::HeroCaraouselText() => [
                'hero_carousel_text' => ['required', 'array'],
                'hero_carousel_text.*.icon' => ['required', 'string', 'max:255'],
                'hero_carousel_text.*.title' => ['required', 'string', 'max:255'],
                'hero_carousel_text.*.summary' => ['required', 'string', 'max:255'],
            ],
        };

        return array_merge($rules, [
            'context' => ['required', new Enum(SettingContext::class)],
        ]);
    }

    public function getData(): Collection
    {
        return collect($this->validated())->except('context')->transform(function ($values) {
            if (is_array($values)) {
                return collect($values)->map(function ($value) {
                    return collect($value)->put('id', Str::uuid()->toString())->all();
                })->all();
            }

            return $values;
        });
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function messages(): array
    {
        return [
            'hero_carousel_text.*.icon.required' => 'The icon field is required.',
            'hero_carousel_text.*.title.required' => 'The title field is required.',
            'hero_carousel_text.*.summary.required' => 'The summary field is required.',

            'hero_carousel_image.*.title.required' => 'The title field is required.',
            'hero_carousel_image.*.summary.required' => 'The summary field is required.',
            'hero_carousel_image.*.file.required' => 'The image file is required.',
            'hero_carousel_image.*.file' => 'The file must be a valid image (jpeg, jpg, png).',

            'social_media.*.icon.required' => 'The icon field is required.',
            'social_media.*.name.required' => 'The name field is required.',
            'social_media.*.link.required' => 'The link field is required.',
            'social_media.*.link.url' => 'The link must be a valid URL.',
        ];
    }
}
