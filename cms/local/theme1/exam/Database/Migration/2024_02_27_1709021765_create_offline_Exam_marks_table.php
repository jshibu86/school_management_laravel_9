<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineExamMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("offline_exam_mark", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("student_id")->unsigned();
            $table
                ->integer("academic_year_id")
                ->unsigned()
                ->nullable();
            $table->integer("exam_id")->unsigned();
            $table
                ->string("score")
                ->default(0)
                ->nullable();

            $table->string("total_score")->nullable();
            $table
                ->integer("mark_status")
                ->comment("1=>pass,2=>fail")
                ->nullable();
            $table->string("position")->nullable();
            $table->string("entry_date")->nullable();

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
