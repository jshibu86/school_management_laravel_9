<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamconfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("exam", function (Blueprint $table) {
            $table->increments("id");
            $table->string("academic_year");
            $table->integer("exam_type")->unsigned();
            $table->integer("class_id")->unsigned();
            $table->integer("section_id")->unsigned();
            $table->integer("subject_id")->unsigned();
            $table->integer("department_id")->unsigned();
            $table->string("max_mark")->default(0);
            $table->string("min_mark")->default(0);
            $table->string("total_mark")->default(0);
            $table->text("include_students")->nullable();
            $table->text("exclude_students")->nullable();
            $table->string("exam_date");
            $table->string("exam_time");
            $table->string("promotion")->nullable();
            $table->string("exam_percentage")->nullable();
            $table->string("timeline")->nullable();
            $table->string("uploaded_file")->nullable();
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

            // $table
            // ->foreign("class_id")
            // ->references("id")
            // ->on("lclass");
        });

        Schema::create("exam_notifications", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("exam_id")->unsigned();
            $table->string("notify_date")->nullable();
            $table->string("notify_time")->nullable();
            $table->text("notify_message")->nullable();
            $table->integer("is_notify")->default(0);
            $table
                ->string("notify_type")
                ->default(1)
                ->comment("0=>phone,1=>email");
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
            $table
                ->foreign("exam_id")
                ->references("id")
                ->on("exam");
        });

        Schema::create("exam_questions", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("exam_id")->unsigned();
            $table->string("order");
            $table->string("question_type");
            $table->text("question")->nullable();
            $table->text("options")->nullable();
            $table->text("answer")->nullable();
            $table->string("attachment")->nullable();
            $table->string("mark");
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
        Schema::dropIfExists("exam");
    }
}
