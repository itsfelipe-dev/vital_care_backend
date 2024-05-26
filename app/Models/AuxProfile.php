<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuxProfile extends Model
{
    use HasFactory;

    protected $table = 'aux_profiles';
    protected $fillable = [
        'user_id',
        'birthday',
        'specialty',
        'profile_image',
        'description',
        'stars',
        'city'
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
