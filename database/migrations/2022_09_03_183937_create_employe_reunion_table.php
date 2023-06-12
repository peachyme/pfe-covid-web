<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeReunionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employe_reunion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organique_id');
            $table->foreign('organique_id')->references('id')->on('employe_organiques');
            $table->foreignId('reunion_id')->constrained();
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
        Schema::dropIfExists('employe_reunion');
    }
}
