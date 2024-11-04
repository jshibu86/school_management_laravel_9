<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectteachermappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("subject_teachermapping", function (Blueprint $table) {
            $table->increments("id");
            $table->string("academic_year")->nullable();
            $table->integer("class_id")->unsigned();
            $table->integer("section_id")->unsigned();
            $table->integer("subject_id")->unsigned();
            $table->integer("teacher_id")->unsigned();

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
            $table
                ->foreign("teacher_id")
                ->references("id")
                ->on("teacher");
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
