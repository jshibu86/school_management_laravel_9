<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpVerificationsUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("otpverifications", function (Blueprint $table) {
            $table->id();
            $table->string("mobile")->nullable();
            $table->string("email")->nullable();
            $table->string("otpverify")->nullable();
            $table->timestamp("send_time")->nullable();
            $table->timestamp("exp_time")->nullable();
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
        Schema::dropIfExists("otpverifications");
    }
}
