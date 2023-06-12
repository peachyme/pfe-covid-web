<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->date('date_consultation');
            $table->enum('symptomes', ['O','N']);
            $table->enum('maladies_chroniques', ['O','N']);
            $table->enum('modalités_priseEnCharge', ['D','BDV','H','RT']);
            $table->bigInteger('periode_confinement');
            $table->enum('evolution_maladie', ['G','D']);
            $table->mediumText('observation');
            $table->unsignedBigInteger('organique_id');
            $table->foreign('organique_id')->references('id')->on('employe_organiques');
            $table->unsignedBigInteger('sousTraitant_id');
            $table->foreign('sousTraitant_id')->references('id')->on('sous_traitants');
            $table->foreignId('depistage_id')->constrained();
            $table->unsignedBigInteger('cmt_id');
            $table->foreign('cmt_id')->references('id')->on('c_m_t_s');
            $table->foreignId('region_id')->constrained();
            $table->foreignId('zone_id')->constrained();
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
        Schema::dropIfExists('consultations');
    }
}
