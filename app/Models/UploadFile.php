<?php

namespace App\Models;

use App\Models\DemandeConge;
use App\Models\DemandeNoteFrais;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UploadFile extends Model
{
    use HasFactory;

    public function demande_conge() {
        return $this->belongsTo(DemandeConge::class);
    }

    public function demande_note_frais() {
        return $this->belongsTo(DemandeNoteFrais::class);
    }

}
