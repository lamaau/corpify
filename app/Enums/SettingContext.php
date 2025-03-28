<?php

namespace App\Enums;

enum SettingContext: string
{
    use InvokableCases;

    case APP = 'app';

    public static function createArrayContext(string $case, string $index): string
    {
        return "{$case}.$index";
    }
}
