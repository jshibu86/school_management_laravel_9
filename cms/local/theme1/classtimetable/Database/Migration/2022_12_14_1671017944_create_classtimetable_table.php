<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateClasstimetableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("period_class", function (Blueprint $table) {
            $table->increments("id");
            $table
                ->integer("academic_year")
                ->unsigned()
                ->nullable();

            $table
                ->integer("class_id")
                ->unsigned()
                ->nullable();

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

        Schema::create("period_class_mapping", function (Blueprint $table) {
            $table->increments("id");
            $table
                ->integer("period_class_id")
                ->unsigned()
                ->nullable();
            $table->string("from");
            $table->string("to");
            $table->integer("type");
            $table->string("break_min")->nullable();

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

        Schema::create("classtimetable", function (Blueprint $table) {
            $table->increments("id");
            $table
                ->integer("academic_year")
                ->unsigned()
                ->nullable();

            $table
                ->integer("period_id")
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
            $table
                ->integer("subject_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("teacher_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("dept_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("term_id")
                ->unsigned()
                ->nullable();
            $table
                ->string("colorcode")

                ->nullable();
            $table
                ->string("no_of_days")

                ->nullable();
            $table->enum("day", [1, 2, 3, 4, 5, 6, 7])->nullable();

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

            $table
                ->foreign("class_id")
                ->references("id")
                ->on("lclass");

            $table
                ->foreign("subject_id")
                ->references("id")
                ->on("subject");

            $table
                ->foreign("section_id")
                ->references("id")
                ->on("section");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("classtimetable");
    }
}
