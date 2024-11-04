<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChapterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("chapter", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("class_id")->unsigned();
            $table->integer("section_id")->unsigned();
            $table->integer("subject_id")->unsigned();
            $table->string("chapter_name");

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
            $table
                ->foreign("class_id")
                ->references("id")
                ->on("lclass")
                ->onDelete("cascade");
            $table
                ->foreign("section_id")
                ->references("id")
                ->on("section")
                ->onDelete("cascade");
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
        Schema::dropIfExists("chapter");
    }
}
