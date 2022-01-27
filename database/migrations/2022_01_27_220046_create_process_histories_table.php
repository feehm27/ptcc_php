<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_histories', function (Blueprint $table) {
            $table->id();
            $table->date('modification_date');
            $table->string('status_process');
            $table->text('modification_description');
            
            $table->unsignedBigInteger('process_id');
            $table->foreign('process_id')->references('id')->on('processes');

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
        Schema::dropIfExists('process_histories');
    }
}
