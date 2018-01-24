<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOthersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('others', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('up_votes_count')->default(0);
            $table->unsignedInteger('down_votes_count')->default(0);
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('others');
    }
}
