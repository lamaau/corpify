<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SortRequest extends FormRequest
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
            'featured' => 'nullable|array',
            'featured.*.id' => 'required|exists:galleries,id',
            'featured.*.sort' => 'required|integer',
            'gallery' => 'nullable|array',
            'gallery.*.id' => 'required|exists:galleries,id',
            'gallery.*.sort' => 'required|integer',
        ];
    }
}
