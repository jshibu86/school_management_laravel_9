<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserTableAndSchoolApprovel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("users", function (Blueprint $table) {
            $table
                ->tinyInteger("approval_process")
                ->default(0)
                ->comment("0=>no,1=>yes");
        });

        Schema::create("school_approvals", function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("school_id")->nullable();
            $table->unsignedInteger("user_id")->nullable();
            $table
                ->enum("status", ["pending", "approved", "denied"])
                ->default("pending");
            $table->timestamps();
            $table
                ->foreign("school_id")
                ->references("id")
                ->on("school_profile")
                ->onDelete("set null");
            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("approval_process");
        });

        Schema::table("school_approvals", function (Blueprint $table) {
            Schema::dropIfExists("school_approvals");
        });
    }
}
