<?php

namespace App\Models;

use App\Models\User;
use App\Models\Direction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'libelle',
        'description',
        'direction_id'
    ];

    public function direction() {
        return $this->belongsTo(Direction::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }

}
