<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemarkToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("homework_submissions", function (Blueprint $table) {
            $table
                ->text("remark")
                ->nullable()
                ->after("subject_id");
            $table
                ->integer("evaluated")
                ->after("remark")
                ->default(0);
            $table->string("submitted_date")->after("evaluated");
            $table->string("submitted_time")->after("submitted_date");

            $table
                ->string("teacher_remark")
                ->nullable()
                ->after("submitted_time");
            $table
                ->string("evaluation_date")
                ->nullable()
                ->after("teacher_remark");
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
