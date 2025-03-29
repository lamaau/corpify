<?php

namespace App\Http\Requests\News;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

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
            'title' => ['required', Rule::unique('news', 'title'), 'max:255'],
            'file' => ['required', 'file', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'slug' => ['required', Rule::unique('news', 'slug')],
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
