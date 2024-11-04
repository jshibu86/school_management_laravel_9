<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubjectisToHsubmissionsTable extends Migration
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
                ->integer("subject_id")
                ->unsigned()
                ->after("homework_id");
            $table
                ->foreign("subject_id")
                ->references("id")
                ->on("subject")
                ->onDelete("cascade");
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
