<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStampTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fileRefId');
            $table->unsignedBigInteger('user_id');
            $table->double('x_coordinate');
            $table->double('y_coordinate');
            $table->integer('page_number');
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
        Schema::dropIfExists('stamp');
    }
}
