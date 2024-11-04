<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSemesterFromToExamTermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("exam_term", function (Blueprint $table) {
            $table
                ->string("from_date")
                ->nullable()
                ->after("exam_term_name");
            $table
                ->string("to_date")
                ->nullable()
                ->after("from_date");

            $table->renameColumn("academy_year", "academic_year");
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
