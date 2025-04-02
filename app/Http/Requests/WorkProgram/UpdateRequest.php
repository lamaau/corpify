<?php

namespace App\Http\Requests\WorkProgram;

use App\Enums\Program\WorkProgramStatus;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'title' => ['required', 'max:255', Rule::unique('posts', 'title')->ignore($this->work_program)],
            'summary' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'status' => ['required', new Enum(WorkProgramStatus::class)],
            'program_id' => ['required', Rule::exists('programs', 'id')],
            'file' => ['file', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
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
