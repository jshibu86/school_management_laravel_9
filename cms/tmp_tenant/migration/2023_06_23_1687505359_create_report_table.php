<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("mark_report", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("academic_year")->unsigned();
            $table->integer("term_id")->unsigned();
            $table->integer("exam_type")->unsigned();
            $table->integer("student_id")->unsigned();
            $table->text("teacher_remark")->nullable();
            $table->string("vaction_date")->nullable();
            $table->string("resumption_date")->nullable();
            $table->json("afdomain")->nullable();
            $table->json("pfdomain")->nullable();
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

            // $table->userstamps();
            // $table->softUserstamps();
            // $table->softDeletes();

            // $table
            // ->foreign("class_id")
            // ->references("id")
            // ->on("lclass");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("report");
    }
}
