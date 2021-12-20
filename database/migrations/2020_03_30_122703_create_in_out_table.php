<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInOutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_outs', function (Blueprint $table) {
            $table->id();
            $table->string('motif');
            $table->dateTime('date_arrive');
            $table->dateTime('date_depart')->nullable();
            $table->integer('visiteur_id');
            $table->string('resp_entree');
            $table->string('resp_sortie');
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
        Schema::dropIfExists('in_out');
    }
}
