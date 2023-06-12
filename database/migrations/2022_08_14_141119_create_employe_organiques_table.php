<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeOrganiquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employe_organiques', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('email')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->enum('sexe', ['F', 'M']);
            $table->enum('situation_familiale', ['C','M','D','V']);
            $table->string('structure');
            $table->string('fonction');
            $table->enum('echelle', ['E','M','C','CS']);
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
        Schema::dropIfExists('employe_organiques');
    }
}
