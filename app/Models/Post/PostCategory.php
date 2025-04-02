<?php

namespace App\Models\Post;

use App\Models\Traits\HasAuthor;
use App\Models\Traits\HasQueryFilter;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use HasAuthor,
        HasQueryFilter;

    protected $fillable = [
        'category_name',
        'category_summary',
    ];
}
