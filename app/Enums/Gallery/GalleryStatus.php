<?php

namespace App\Enums\Gallery;

use App\Enums\InvokableCases;

enum GalleryStatus: string
{
    use InvokableCases;

    case Featured = 'featured';
}
