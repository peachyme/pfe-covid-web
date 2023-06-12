<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepistagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depistages', function (Blueprint $table) {
            $table->id();
            $table->date('date_test');
            $table->enum('type_test', ['PCR', 'Antigénique', 'Sérologique', 'Scanner']);
            $table->enum('resultat_test', ['Positif','Négatif']);
            $table->string('couverture');
            $table->unsignedBigInteger('cmt_id');
            $table->foreign('cmt_id')->references('id')->on('c_m_t_s');
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
        Schema::dropIfExists('depistages');
    }
}
