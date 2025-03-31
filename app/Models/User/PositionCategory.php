<?php

namespace App\Models\User;

use App\Models\User\PositionAssignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PositionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_category_name',
        'position_category_summary',
    ];

    protected static function booted(): void
    {
        // set default value to keep every recursive og have default one
        static::created(function (PositionCategory $model) {
            $model->assignments()->create([
                // 
            ]);
        });
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(PositionAssignment::class);
    }
}
