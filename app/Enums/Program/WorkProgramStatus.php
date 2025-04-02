<?php

namespace App\Enums\Program;

use App\Enums\InvokableCases;

enum WorkProgramStatus: int
{
    use InvokableCases;

    case Published = 1;
    case Draft = 2;
    case Archived = 3;
}
