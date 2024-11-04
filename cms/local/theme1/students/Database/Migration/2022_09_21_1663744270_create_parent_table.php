<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("parent", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("student_id")->unsigned();
            $table->string("username");
            //father details
            $table->string("father_name");
            $table->string("father_email");
            $table->string("father_mobile");
            $table->string("father_occupation")->nullable();
            $table->string("father_image")->nullable();
            $table->string("fathernat_id");

            //mother details
            $table->string("mother_name");
            $table->string("mother_email")->nullable();
            $table->string("mother_mobile")->nullable();
            $table->string("mother_occupation")->nullable();
            $table->string("mother_image")->nullable();
            $table->string("mothernat_id")->nullable();

            //guardian details
            $table->string("guardian_name")->nullable();
            $table->string("guardian_email")->nullable();
            $table->string("guardian_mobile")->nullable();
            $table->string("guardian_occupation")->nullable();
            $table->string("guardian_image")->nullable();
            $table->string("guardiannat_id")->nullable();

            //general
            $table->string("yearly_income")->nullable();
            $table->string("house_name")->nullable();
            $table->string("wallet_amount")->nullable();

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

            $table
                ->foreign("student_id")
                ->references("id")
                ->on("students");
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
