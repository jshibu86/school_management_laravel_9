<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("exam_section", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("exam_id")->unsigned();
            $table->string("section_name")->nullable();
            $table->string("section_mark")->nullable();
            $table->string("section_order")->nullable();
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

        Schema::table("exam_questions", function (Blueprint $table) {
            $table
                ->integer("section_id")
                ->unsigned()
                ->after("exam_id");
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
