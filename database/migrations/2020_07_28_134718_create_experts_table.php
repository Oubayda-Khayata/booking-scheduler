<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_timezone_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->enum('gender', ['male', 'female']);
            $table->string('expertise');
            /**
             * working time measured in minutes: each day have 1440 minutes.
             * So, daily working time can have values from 0 -> 1439
             * In case an expert works from 10:00 P.M. to 03:00 A.M., the values would be:
             * from: 1320, to: 180
             * In case an expert works from 12:00 P.M. to 06:00 P.M., the values would be:
             * from: 720, to: 1080
             */
            $table->integer('daily_working_time_from');
            $table->integer('daily_working_time_to');
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });
        Schema::table('experts', function (Blueprint $table) {
            $table->foreign('country_timezone_id')->references('id')->on('country_timezone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experts');
    }
}
