<?php

namespace App\Models\Member;

use App\Models\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Model;

class MemberCategory extends Model
{
    use HasAuthor;

    protected $fillable = [
        'member_category_name',
    ];
}
