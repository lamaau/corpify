<?php

namespace App\Http\Requests\WorkProgram;

use App\Enums\Program\WorkProgramStatus;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'title' => ['required', Rule::unique('posts', 'title'), 'max:255'],
            'summary' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'status' => ['required', new Enum(WorkProgramStatus::class)],
            'program_id' => ['required', Rule::exists('programs', 'id')],
            'file' => ['required', 'file', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }

    public function getData(): array
    {
        return collect($this->validated())->except('file')->merge([
            'slug' => Str::slug($this->title) . '-' . now(),
            'created_by' => $this->user()->id,
        ])->all();
    }
}
