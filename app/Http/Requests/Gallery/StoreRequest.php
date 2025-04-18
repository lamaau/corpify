<?php

namespace App\Http\Requests\Gallery;

use App\Rules\Gallery\FeaturedRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'caption' => ['required', 'string', 'max:255'],
            'featured' => ['required', 'boolean', new FeaturedRule],
            'file' => ['required', 'file', 'mimes:jpeg,jpg,png,webp,pdf,mp4', 'max:5120'], // 5MB limit
        ];
    }

    public function getData(): array
    {
        return collect($this->validated())->except('file')->merge([
            'created_by' => user()->id,
        ])->all();
    }
}
