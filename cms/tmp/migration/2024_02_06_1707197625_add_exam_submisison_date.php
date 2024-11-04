<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExamSubmisisonDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("exam", function (Blueprint $table) {
            $table
                ->string("exam_submission_date")
                ->nullable()
                ->after("exam_time");
            $table
                ->string("exam_submission_time")
                ->nullable()
                ->after("exam_submission_date");
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
