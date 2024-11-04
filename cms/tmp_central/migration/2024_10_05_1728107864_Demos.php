<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Demos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demos', function (Blueprint $table) {
            $table->increments('id'); 
		    $table->string('demo_id', 100)->nullable();
	        $table->string('school_name', 255)->nullable();	 
            $table->string('contact_name', 255)->nullable();
            $table->string('phoneno', 20)->nullable();
            $table->string('email', 255)->nullable();                                            
            $table->string('role', 20)->nullable();   
            $table->string('gender', 20)->nullable();   
            $table->string('address', 255)->nullable();   
            $table->string('city', 20)->nullable();   
            $table->string('pincode', 20)->nullable();   
            $table->string('country', 20)->nullable();   
            $table->string('demo_date', 20)->nullable();
            $table->string('demo_time', 20)->nullable();
            $table->string('setting_message', 255)->nullable();
	        $table->tinyInteger('status')->default(0)->comment(' -1=>expired,0=>pending,1=>scheduled, 2=>attend, 3=>customer'); 	        
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
        Schema::dropIfExists('demos');        
    }
}

