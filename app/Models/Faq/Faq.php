<?php

namespace App\Models\Faq;

use App\Models\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasAuthor;

    protected $fillable = [
        'question',
        'answer',
        'created_by',
    ];
}
