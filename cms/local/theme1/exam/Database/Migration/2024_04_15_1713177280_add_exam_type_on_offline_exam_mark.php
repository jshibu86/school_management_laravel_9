<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExamTypeOnOfflineExamMark extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("offline_exam_mark", function (Blueprint $table) {
            // Check if the 'exam_type' column does not exist
            if (!Schema::hasColumn("offline_exam_mark", "exam_type")) {
                // Add the 'exam_type' column if it doesn't exist
                $table
                    ->string("exam_type")
                    ->default("Offline")
                    ->after("exam_id");
            }
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
