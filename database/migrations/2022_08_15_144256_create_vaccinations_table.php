<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->date('date_vaccination');
            $table->bigInteger('dose_vaccination');
            $table->unsignedBigInteger('organique_id');
            $table->foreign('organique_id')->references('id')->on('employe_organiques');
            $table->unsignedBigInteger('sousTraitant_id');
            $table->foreign('sousTraitant_id')->references('id')->on('sous_traitants');
            $table->unsignedBigInteger('cmt_id');
            $table->foreign('cmt_id')->references('id')->on('c_m_t_s');
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
        Schema::dropIfExists('vaccinations');
    }
}
