<?php

namespace App\Models\User;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'nip', 'avatar', 'user_id'];

    protected $appends = ['avatar'];

    public function avatar(): Attribute
    {
        return Attribute::make(get: fn() => "https://ui-avatars.com/api/?name={$this->name}&amp;color=FFFFFF&amp;background=09090b");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
