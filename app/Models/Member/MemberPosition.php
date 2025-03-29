<?php

namespace App\Models\Member;

use App\Models\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Model;

class MemberPosition extends Model
{
    use HasAuthor;

    protected $fillable = [
        'member_position_name',
        'sort',
    ];
}
