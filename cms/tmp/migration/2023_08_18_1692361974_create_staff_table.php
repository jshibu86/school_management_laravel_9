<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("staff", function (Blueprint $table) {
            $table->increments("id");
            $table
                ->integer("user_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("group_id")
                ->unsigned()
                ->nullable();

            $table
                ->integer("designation_id")
                ->unsigned()
                ->nullable();

            $table->string("email");
            $table->string("mobile");
            $table->string("employee_code")->nullable();
            $table->string("employee_name");
            $table->string("gender");
            $table->string("dob")->nullable();
            $table->string("qualification")->nullable();
            $table->text("address_communication")->nullable();
            $table->string("national_id_number");
            $table->string("image")->nullable();
            $table->string("date_ofjoin")->nullable();
            $table->string("blood_group")->nullable();
            $table->string("maritial_status");
            $table->string("license_no")->nullable();

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
        Schema::dropIfExists("staff");
    }
}
