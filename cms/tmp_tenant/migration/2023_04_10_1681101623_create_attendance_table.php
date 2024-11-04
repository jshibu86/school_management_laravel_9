<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("attendance", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("academic_year")->unsigned();
            $table->integer("class_id")->unsigned();
            $table->integer("section_id")->unsigned();
            $table
                ->integer("period_id")
                ->unsigned()
                ->nullable()
                ->comment("period_id_reference_for_class_timetable");
            $table
                ->integer("subject_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("teacher_id")
                ->unsigned()
                ->nullable();
            $table
                ->string("type")
                ->nullable()
                ->comment("1=>hourly,2=>daily");

            $table->string("attendance_date")->nullable();
            $table->string("attendance_month")->nullable();
            $table->string("attendance_year")->nullable();
            $table->string("attendance_time")->nullable();
            $table->string("attendance_taken_by")->nullable();

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

            $table->userstamps();
            $table->softUserstamps();
            $table->softDeletes();

            // $table
            // ->foreign("class_id")
            // ->references("id")
            // ->on("lclass");
        });

        Schema::create("attendance_students", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("attendance_id")->unsigned();
            $table->integer("student_id")->unsigned();
            $table->string("attendance")->nullable();

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

            $table->userstamps();
            $table->softUserstamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("attendance");
    }
}
