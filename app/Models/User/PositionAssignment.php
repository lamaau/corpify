<?php

namespace App\Models\User;

use App\Models\User;
use App\Models\User\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'position_id', 'position_category_id', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function category()
    {
        return $this->belongsTo(PositionCategory::class, 'position_category_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(PositionAssignment::class, 'parent_id')->with(['user', 'position', 'category', 'children']);
    }
}
