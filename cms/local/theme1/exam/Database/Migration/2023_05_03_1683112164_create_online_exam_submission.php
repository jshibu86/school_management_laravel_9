<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineExamSubmission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("online_exam", function (Blueprint $table) {
            $table->increments("id");
            $table->string("academic_year")->nullable();
            $table->integer("exam_id")->unsigned();
            $table
                ->integer("student_id")

                ->unsigned()
                ->nullable();
            $table->string("total_questions")->nullable();
            $table->string("total_answered")->nullable();
            $table->string("total_correct")->nullable();
            $table->string("submit_date")->nullable();
            $table->string("submit_time")->nullable();

            $table
                ->integer("status")
                ->default(1)
                ->comment("-1=>trash,0=>disable,1=>active");
            $table->timestamp("created_at")->useCurrent();
            $table
                ->timestamp("updated_at")
                ->default(
                    DB::raw("CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
                );
        });
        Schema::create("online_exam_submission", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("online_exam_id")->unsigned();
            $table
                ->integer("question_id")
                ->unsigned()
                ->nullable();
            $table->string("correct_answer")->nullable();
            $table->string("your_answer")->nullable();
            $table->string("mark")->nullable();

            $table
                ->string("is_correct")
                ->default(0)
                ->comment("0=>no,1=>yes");

            $table
                ->integer("status")
                ->default(1)
                ->comment("-1=>trash,0=>disable,1=>active");
            $table->timestamp("created_at")->useCurrent();
            $table
                ->timestamp("updated_at")
                ->default(
                    DB::raw("CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
                );
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
