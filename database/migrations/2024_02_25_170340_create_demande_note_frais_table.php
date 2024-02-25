<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demande_note_frais', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_depense');
            $table->float('montant');
            $table->text('description');
            $table->integer('statut')->default(0);
            $table->string('etat')->default('En cours');
            $table->foreignIdFor(User::class);
            $table->integer('validateur_id')->nullable();
            $table->timestamp('date_attribution_validateur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_note_frais');
    }
};
