<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Carbon;

class DateObjectCast implements CastsAttributes
{
    /**
     * Transform the attribute from storage.
     */
    public function get($model, string $key, mixed $value, array $attributes): array | null
    {
        if (!$value) {
            return null;
        }

        $date = Carbon::parse($value);

        return [
            'original'  => $date->toDateTimeString(),
            'formatted' => [
                'date' => $date->format('d M Y'),
                'time' => $date->format('H:i A'),
            ],
        ];
    }

    /**
     * Transform the attribute before storing.
     */
    public function set($model, string $key, mixed $value, array $attributes): string
    {
        if (is_array($value) && isset($value['original'])) {
            return Carbon::parse($value['original'])->toDateTimeString();
        }

        return Carbon::parse($value)->toDateTimeString();
    }
}
