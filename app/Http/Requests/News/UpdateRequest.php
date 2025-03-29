<?php

namespace App\Http\Requests\News;

use Illuminate\Support\Str;
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
            'title' => ['required', Rule::unique('news', 'title')->ignore($this->news), 'max:255'],
            'file' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'slug' => ['required', Rule::unique('news', 'slug')->ignore($this->news)],
            'body' => ['required', 'string'],
        ];
    }

    public function getNewsData(): array
    {
        return collect($this->validated())->only(['title'])->merge([
            'slug' => Str::slug($this->title) . '-' . now(),
            'created_by' => $this->user()->id,
        ])->all();
    }

    public function getNewsContent(): array
    {
        return ['content' => $this->body];
    }
}
