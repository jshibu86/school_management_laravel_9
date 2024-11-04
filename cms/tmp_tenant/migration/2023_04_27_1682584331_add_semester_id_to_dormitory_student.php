<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSemesterIdToDormitoryStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("dormitory_students", function (Blueprint $table) {
            $table
                ->integer("semester_id")
                ->unsigned()
                ->nullable()
                ->after("academic_year");
            $table
                ->string("date_of_reg")
                ->nullable()
                ->after("semester_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
