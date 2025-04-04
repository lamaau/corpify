<?php

namespace App\Rules;

use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Validation\ValidationRule;

class FileOrURL implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // If it's an uploaded file
        if ($value instanceof UploadedFile) {
            $extension = strtolower($value->getClientOriginalExtension());
            $mimeValid = in_array($extension, ['jpeg', 'jpg', 'png', 'webp']);
            $sizeValid = $value->getSize() <= 5 * 1024 * 1024; // 5MB

            if (!($mimeValid && $sizeValid)) {
                $fail("The {$attribute} must be a valid image (jpeg, jpg, png, webp) not exceeding 5MB.");
            }

            return;
        }

        // If it's a string, validate as URL
        if (is_string($value)) {
            if (!filter_var($value, FILTER_VALIDATE_URL)) {
                $fail("The {$attribute} must be a valid URL.");
            }

            return;
        }

        // If neither file nor string
        $fail("The {$attribute} must be a file or a valid URL.");
    }
}
