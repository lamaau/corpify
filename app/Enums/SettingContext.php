<?php

namespace App\Enums;

use App\Actions\Setting\SettingAction;

enum SettingContext: string
{
    use InvokableCases;

    case App = 'app';
    case Contact = 'contact';
    case Address = 'address';
    case SocialMedia = 'social_media';
    case HeroCaraouselImage = 'hero_carousel_image';
    case HeroCaraouselText = 'hero_carousel_text';

    /**
     * An context have sort
     *
     * @return array
     */
    public static function contextArray(): array
    {
        return [
            self::SocialMedia(),
            self::HeroCaraouselImage(),
            self::HeroCaraouselText()
        ];
    }

    public static function getByContext(string $context)
    {
        $query = SettingAction::instance()->getFormattedSettings($context);

        return collect($query)->transform(function ($value, $key) {
            if (in_array($key, self::contextArray())) {
                $decodedValue = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedValue)) {
                    return $decodedValue;
                }
            }

            return $value;
        });
    }
}
