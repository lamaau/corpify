<?php

namespace App\Models\User;

use App\Models\User;
use App\Models\User\PositionAssignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PositionCategory extends Model
{
    use HasFactory;

    protected $fillable = ['position_category_name', 'created_by'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignments()
    {
        return $this->hasMany(PositionAssignment::class);
    }
}
