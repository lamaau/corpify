<?php

namespace App\Enums;

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
    public static function getContextHaveSorts(): array
    {
        return [
            self::SocialMedia(),
            self::HeroCaraouselImage(),
            self::HeroCaraouselText()
        ];
    }
}
