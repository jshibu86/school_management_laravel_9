<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("academic_fees", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("academic_year")->unsigned();
            $table->integer("student_id")->unsigned();
            $table->integer("model_id")->nullable();
            $table->string("model_name")->nullable();
            $table->string("added_date")->nullable();
            $table->string("type")->nullable();
            $table->string("fee_name")->nullable();
            $table->string("due_amount");
            $table->string("paid_amount")->default(0);
            $table->string("paid_date")->nullable();
            $table->string("pending_amount")->default(0);
            $table->text("month_info")->nullable();
            $table->string("leaved_date")->nullable();

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
        //
    }
}
