<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("students", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("user_id")->unsigned();
            $table->string("academic_year");
            $table->integer("class_id")->unsigned();
            $table->integer("section_id")->unsigned();

            $table->string("reg_no");
            $table->string("roll_no")->nullable();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("email");
            $table->string("mobile");
            $table->string("gender");
            $table->string("dob");
            $table->string("blood_group");
            $table->string("student_type");
            $table->string("admission_date");
            $table->string("passport_no")->nullable();
            $table->string("image")->nullable();
            $table->string("national_id_number");
            $table->string("handicapped");
            $table->string("transportation")->default(0);
            $table->string("transportation_zone")->nullable();
            $table->string("vechicle_no")->nullable();
            $table->string("yearly_income")->nullable();
            $table->string("house_name")->nullable();
            $table->string("previous_ins_percentage")->nullable();
            $table->text("address_communication");
            $table->text("address_residence");

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
            // $table
            //     ->foreign("class_id")
            //     ->references("id")
            //     ->on("lclass");
            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users");
            // $table
            //     ->foreign("section_id")
            //     ->references("id")
            //     ->on("section");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("students");
    }
}
