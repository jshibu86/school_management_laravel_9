<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChapterTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("chapter_topics", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("chapter_id")->unsigned();
            $table->integer("class_id")->unsigned();
            $table->integer("section_id")->unsigned();
            $table->integer("subject_id")->unsigned();
            $table->string("topic_name");
            $table->text("topic_description")->nullable();
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
                ->foreign("chapter_id")
                ->references("id")
                ->on("chapter")
                ->onDelete("cascade");
            $table
                ->foreign("class_id")
                ->references("id")
                ->on("lclass")
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
        //
    }
}
