<?php

namespace App\Models;

use App\Models\Agence;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Direction extends Model
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
        'agence_id'
    ];

    public function agence() {
        return $this->belongsTo(Agence::class);
    }

    public function services() {
        return $this->hasMany(Service::class);
    }

}
