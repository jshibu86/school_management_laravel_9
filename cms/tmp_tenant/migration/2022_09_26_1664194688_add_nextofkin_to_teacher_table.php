<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNextofkinToTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("teacher", function (Blueprint $table) {
            $table
                ->string("religion")
                ->nullable()
                ->after("blood_group");
            $table
                ->string("emp_name")
                ->nullable()
                ->after("blood_group");
            $table
                ->string("job_role")
                ->nullable()
                ->after("emp_name");
            $table
                ->string("job_description")
                ->nullable()
                ->after("job_role");
            $table
                ->string("net_pay")
                ->nullable()
                ->after("job_description");
            $table
                ->string("location")
                ->nullable()
                ->after("net_pay");
            $table
                ->string("start_date")
                ->nullable()
                ->after("location");
            $table
                ->string("end_date")
                ->nullable()
                ->after("start_date");
            $table
                ->string("kin_fullname")
                ->nullable()
                ->after("end_date");
            $table
                ->string("kin_relationship")
                ->nullable()
                ->after("kin_fullname");
            $table
                ->string("kin_phonenumber")
                ->nullable()
                ->after("kin_relationship");
            $table
                ->string("kin_email")
                ->nullable()
                ->after("kin_phonenumber");
            $table
                ->string("kin_occupation")
                ->nullable()
                ->after("kin_email");
            $table
                ->string("kin_religion")
                ->nullable()
                ->after("kin_occupation");
            $table
                ->string("kin_address")
                ->nullable()
                ->after("kin_religion");
        });

        Schema::table("students", function (Blueprint $table) {
            $table
                ->string("religion")
                ->nullable()
                ->after("blood_group");
        });

        Schema::table("parent", function (Blueprint $table) {
            $table
                ->string("religion")
                ->nullable()
                ->after("father_image");
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
