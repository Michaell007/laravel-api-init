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
        Schema::create('demande_conges', function (Blueprint $table) {
            $table->id();
            $table->string('type_conge'); // Congé annuel Congé de maladie Congé de maternité/paternité Autre (spécifier)
            $table->timestamp('date_debut')->nullable();
            $table->timestamp('date_fin')->nullable();
            $table->string('etat')->default('En cours');
            $table->integer('statut')->default(0);
            $table->string('commentaire')->nullable();
            $table->foreignIdFor(User::class)->nullable();
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
        Schema::dropIfExists('demande_conges');
    }
};
