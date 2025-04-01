<?php

namespace App\Enums\Post;

use App\Enums\InvokableCases;

enum PostStatus: int
{
    use InvokableCases;

    case Published = 1;
    case Draft = 2;
    case Archived = 3;
}
