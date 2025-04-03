<?php

namespace App\Rules\Gallery;

use App\Models\Gallery\Gallery;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FeaturedRule implements ValidationRule
{
    public function __construct(
        private ?Gallery $gallery = null
    ) {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!(bool)$value) {
            return;
        }

        $query = Gallery::query()->featured();

        if ($this->gallery) {
            $query->where('id', '!=', $this->gallery->id);
        }

        $count = $query->count();

        if ($count >= 6) {
            $fail('Maximum featured item is 6');
        }
    }
}
