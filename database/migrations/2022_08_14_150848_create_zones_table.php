<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('code_zone');
            $table->string('libelle_zone');
            $table->integer('capacité_zone');
            $table->integer('effectif_medecins');
            $table->integer('effectif_infermiers');
            $table->unsignedBigInteger('responsable_zone');
            $table->foreign('responsable_zone')->references('id')->on('employe_organiques');
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
        Schema::dropIfExists('zones');
    }
}
