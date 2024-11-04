<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("leave_types", function (Blueprint $table) {
            $table->increments("id");
            $table->string("leave_type");
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
        Schema::create("leave", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("leave_type_id")->unsigned();
            $table
                ->integer("student_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("teacher_id")
                ->unsigned()
                ->nullable();
            $table->string("application_date");
            $table->string("from_date");
            $table->string("to_date");
            $table->integer("no_days")->nullable();
            $table->text("reason");
            $table->string("attachment")->nullable();
            $table
                ->integer("application_status")
                ->default(2)
                ->comment("-1=>rejected,2=>pending,1=>approved");
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
                ->foreign("student_id")
                ->references("id")
                ->on("students");
            $table
                ->foreign("teacher_id")
                ->references("id")
                ->on("teacher");
            $table
                ->foreign("leave_type_id")
                ->references("id")
                ->on("leave_types");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("leave");
        Schema::dropIfExists("leave_types");
    }
}
