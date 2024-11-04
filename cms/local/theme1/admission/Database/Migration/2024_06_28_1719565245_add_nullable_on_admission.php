<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableOnAdmission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admission', function (Blueprint $table) {        
            $table->string("first_name")->nullable()->change();
            $table->string("last_name")->nullable()->change();
            $table->string("email")->nullable()->change();
            $table->string("mobile")->nullable()->change();
            $table->string("gender")->nullable()->change();
            $table->string("dob")->nullable()->change();
            $table->string("blood_group")->nullable()->change();
            $table->string("handicapped")->nullable()->change();
            $table->string("national_id_number")->nullable()->change();
	        $table->string("parent_name")->nullable()->change();
            $table->string("parent_email")->nullable()->change();
	        $table->string("house_no")->nullable()->change();
            $table->text("street")->nullable()->change();
	        $table->string("country")->nullable()->change();
	        $table->string("city")->nullable()->change();
            $table->string("postal_code")->nullable()->change();
            $table->string("previous_class_id")->nullable()->change();
            $table->string("current_class_id")->nullable()->change();
            $table->string("previous_school")->nullable()->change();
        }
    );
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
