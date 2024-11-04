<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomefeildsToChapterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("chapter", function (Blueprint $table) {
            $table
                ->text("academic_year")
                ->nullable()
                ->after("subject_id");
            $table
                ->text("chapter_description")
                ->nullable()
                ->after("chapter_name");
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
