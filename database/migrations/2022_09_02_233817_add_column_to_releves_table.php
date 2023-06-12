<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRelevesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('releves', function (Blueprint $table) {

            $table->unsignedBigInteger('organique_id');
            $table->foreign('organique_id')->references('id')->on('employe_organiques');
            $table->unsignedBigInteger('sousTraitant_id');
            $table->foreign('sousTraitant_id')->references('id')->on('sous_traitants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('releves', function (Blueprint $table) {
            //
        });
    }
}
