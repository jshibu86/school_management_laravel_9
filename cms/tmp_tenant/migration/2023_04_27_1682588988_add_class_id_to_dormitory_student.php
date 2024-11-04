<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClassIdToDormitoryStudent extends Migration
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
                ->integer("class_id")
                ->unsigned()
                ->nullable()
                ->after("academic_year");
            $table
                ->integer("section_id")
                ->unsigned()
                ->nullable()
                ->after("class_id");
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
