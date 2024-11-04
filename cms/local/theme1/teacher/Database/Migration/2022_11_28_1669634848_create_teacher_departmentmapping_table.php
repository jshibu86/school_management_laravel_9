<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherDepartmentmappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("section", function (Blueprint $table) {
            $table
                ->integer("department_id")
                ->unsigned()
                ->after("class_id")
                ->nullable();

            $table
                ->foreign("department_id")
                ->references("id")
                ->on("department");
        });

        Schema::create("teacher_departmentmapping", function (
            Blueprint $table
        ) {
            $table->increments("id");

            $table->string("teacher_id");
            $table->string("department_id");
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
