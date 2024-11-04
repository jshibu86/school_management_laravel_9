<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassteacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("classteacher", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("academic_year");
            $table->integer("teacher_id")->unsigned();
            $table->integer("class_id")->unsigned();
            $table->integer("section_id")->unsigned();
            $table->string("position")->nullable();
            $table->timestamp("created_at")->useCurrent();
            $table
                ->timestamp("updated_at")
                ->default(
                    DB::raw("CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
                );
            $table
                ->integer("status")
                ->default(1)
                ->comment("-1=>trash,0=>disable,1=>active");
            $table->userstamps();
            $table->softUserstamps();
            $table->softDeletes();

            $table
                ->foreign("teacher_id")
                ->references("id")
                ->on("teacher")
                ->onDelete("cascade");
            $table
                ->foreign("class_id")
                ->references("id")
                ->on("lclass");
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
        Schema::dropIfExists("classteacher");
    }
}
