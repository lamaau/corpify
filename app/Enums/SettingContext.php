<?php

namespace App\Enums;

enum SettingContext: string
{
    use InvokableCases;

    case APP = 'app';
    case CONTACT = 'contact';
    case ADDRESS = 'address';
    case SOCIAL_MEDIA = 'social_media';

    public static function createArrayContext(string $case, string $index): string
    {
        return "{$case}.$index";
    }
}
