<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateHomeworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("homework", function (Blueprint $table) {
            $table->increments("id");
            $table->string("title");
            $table->integer("user_id")->unsigned();

            $table->integer("class_id")->unsigned();
            $table->integer("section_id")->unsigned();
            $table->integer("subject_id")->unsigned();
            $table->string("homework_date");
            $table->string("submission_date");
            $table->string("attachment")->nullable();
            $table->text("homework_description")->nullable();
            $table
                ->integer("homework_status")
                ->default(0)
                ->comment("-1=>notcomplete,1=>completed,0=>pending");
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
                ->foreign("user_id")
                ->references("id")
                ->on("users");
            $table
                ->foreign("class_id")
                ->references("id")
                ->on("lclass");
            $table
                ->foreign("section_id")
                ->references("id")
                ->on("section");
            $table
                ->foreign("subject_id")
                ->references("id")
                ->on("subject");
        });

        Schema::create("homework_submissions", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("homework_id")->unsigned();
            $table
                ->foreign("homework_id")
                ->references("id")
                ->on("homework")
                ->onDelete("cascade");
            $table->integer("student_id")->unsigned();
            $table
                ->foreign("student_id")
                ->references("id")
                ->on("students")
                ->onDelete("cascade");
            $table
                ->string("attachment", 500)
                ->nullable()
                ->default(null);

            $table->integer("count");
            $table
                ->integer("homework_status")
                ->default(0)
                ->comment("-1=>notcomplete,1=>completed,0=>pending");
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
        Schema::dropIfExists("homework");
    }
}
