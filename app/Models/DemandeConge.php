<?php

namespace App\Models;

use App\Models\User;
use App\Models\UploadFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemandeConge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_conge',
        'date_debut',
        'date_fin',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function upload_files() {
        return $this->hasMany(UploadFile::class);
    }
}
