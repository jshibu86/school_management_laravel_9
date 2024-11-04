<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CareteMarkEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("mark_entry", function (Blueprint $table) {
            $table->increments("id");
            $table
                ->integer("academic_year")
                ->unsigned()
                ->nullable();
            $table
                ->integer("term_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("class_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("section_id")
                ->unsigned()
                ->nullable();

            $table->integer("student_id")->unsigned();
            $table
                ->foreign("student_id")
                ->on("students")
                ->references("id")
                ->cascadeOnDelete();

            $table
                ->integer("subject_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("exam_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("exam_type")
                ->unsigned()
                ->nullable();
            $table->json("distribution")->nullable();

            $table
                ->integer("is_present")
                ->comment("1=>present,0=>absent")
                ->nullable();
            $table->string("total_mark")->nullable();
            $table->string("grade")->nullable();
            $table->string("point")->nullable();
            $table->text("remark")->nullable();
            $table->string("entry_date")->nullable();
            $table->string("entry_time")->nullable();
            $table->userstamps();
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
