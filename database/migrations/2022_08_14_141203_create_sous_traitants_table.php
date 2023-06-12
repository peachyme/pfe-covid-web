<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSousTraitantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sous_traitants', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('email')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->enum('sexe', ['F', 'M']);
            $table->date('date_naissance');
            $table->enum('situation_familiale', ['C','M','D','V']);
            $table->enum('type', ['Cuisine','Jardinage','Ménage','Transport']);
            $table->foreignId('region_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sous_traitants');
    }
}
