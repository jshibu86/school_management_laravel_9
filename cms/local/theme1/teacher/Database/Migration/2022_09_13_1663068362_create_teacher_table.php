<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("teacher", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("user_id")->unsigned();
            $table->integer("designation_id")->unsigned();
            $table->string("email");
            $table->string("mobile");
            $table->string("employee_code");
            $table->string("teacher_name");
            $table->string("gender");
            $table->string("dob");
            $table->string("national_id_number");
            $table->text("address_communication");
            $table->text("address_residence");
            $table->string("qualification");
            $table->string("image")->nullable();
            $table->string("date_ofjoin")->nullable();
            $table->string("date_ofreleave")->nullable();
            $table->text("reason_forleave")->nullable();
            $table->string("guardian_name");
            $table->string("relation");
            $table->string("guardian_mobile");
            $table->string("blood_group");
            $table->string("handicapped");
            $table->string("maritial_status");

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
                ->foreign("designation_id")
                ->references("id")
                ->on("designation");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("teacher");
    }
}
