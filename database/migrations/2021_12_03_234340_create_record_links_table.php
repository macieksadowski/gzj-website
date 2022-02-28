<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_links', function (Blueprint $table) {
            $table->id();

            $table->string('url');
            $table->bigInteger('record_set_id')->unsigned();
            $table->foreign('record_set_id')->references('id')->on('record_sets');

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
        Schema::dropIfExists('record_links');
    }
}
