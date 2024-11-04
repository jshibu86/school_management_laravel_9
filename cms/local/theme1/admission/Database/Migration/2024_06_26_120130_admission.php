<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Admission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission', function (Blueprint $table) {
            $table->increments('id');                        
            $table->string("first_name");
            $table->string("last_name");
            $table->string("email");
            $table->string("mobile");
            $table->string("gender");
            $table->string("dob");
            $table->string("blood_group");
            $table->string("handicapped");
	        $table->string("image")->nullable();
            $table->string("national_id_number");
	        $table->string("parent_name");
            $table->string("parent_email");
	        $table->string("house_no");
	        $table->string("country");
	        $table->string("city");
            $table->string("postal_code"); 
            $table->string("previous_class_id");
            $table->string("current_class_id");
            $table->string("previous_school");   
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
