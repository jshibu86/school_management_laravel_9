<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExamtermExamTable extends Migration
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
                ->integer("exam_term")
                ->unsigned()
                ->after("exam_type");
            $table
                ->string("type_of_exam")

                ->after("exam_term");
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
