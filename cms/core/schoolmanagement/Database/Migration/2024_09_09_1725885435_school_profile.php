<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SchoolProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_profile', function (Blueprint $table) {
            $table->increments('id'); 
		    $table->string('reg_no', 100)->nullable();
	        $table->string('school_name', 255)->nullable();	 
            $table->string('email', 255)->nullable();
            $table->string('phoneno', 20)->nullable();
            $table->string('image')->nullable();	
            $table->unsignedInteger('plan_id')->nullable();
            $table->integer('billing_id')->nullable();
            $table->integer('student_count')->nullable();
            $table->integer('discount')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('country')->nullable();
            $table->date('join_date')->nullable();
	        $table->tinyInteger('status')->default(0)->comment('-1=>trash,0=>inactive,1=>active'); 
	        $table->tinyInteger('subscribe_status')->default(0)->comment('0=>expired,1=>subscribed'); 
	        $table->tinyInteger('approval_status')->default(0)->comment('-1=>denied,0=>pending,1=>approved'); 
		    $table->timestamp('created_at')->useCurrent();  
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));   	            
            $table->foreign("plan_id")->references("id")->on("subscription_plan")->onDelete('set null');
            
        });
        Schema::create('school_contact', function (Blueprint $table) {
            $table->increments('id'); 		    
		    $table->unsignedInteger('school_id')->nullable();
	        $table->string('first_name')->nullable();
	        $table->string('last_name')->nullable();	        
            $table->string('email')->nullable();
            $table->string('phoneno')->nullable();
	        $table->string('role')->nullable();
	        $table->string('gender')->nullable();	        
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
            $table->foreign("school_id")->references("id")->on("school_profile")->onDelete('set null');            
        });

        Schema::create('school_planpayment', function (Blueprint $table) {
            $table->increments('id'); 
		    $table->unsignedInteger('school_id')->nullable();
	        $table->decimal('bill_amount')->nullable();
	        $table->decimal('sales_tax')->nullable();
	        $table->decimal('training_fee')->nullable();
		    $table->decimal('discount')->nullable();
  		    $table->decimal('due_amount')->nullable();
            $table->timestamps();
            $table->foreign("school_id")->references("id")->on("school_profile")->onDelete('set null');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_profile');
        Schema::dropIfExists('school_contact');
        Schema::dropIfExists('school_planpayment');
    }
}
