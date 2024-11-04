<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarkReportAvgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("mark_report", function (Blueprint $table) {
            $table
                ->string("total_mark_obtainable")
                ->nullable()
                ->after("student_id");
            $table
                ->string("total_mark_obtain")
                ->nullable()
                ->after("total_mark_obtainable");
            $table
                ->string("average")
                ->nullable()
                ->after("total_mark_obtain");
            $table
                ->string("is_promotion")
                ->nullable()
                ->comment("consider this avg mark in promotion")
                ->after("average");
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
