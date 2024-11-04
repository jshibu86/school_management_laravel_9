<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTenantIdOnSchoolProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("school_profile", function (Blueprint $table) {
            $table
                ->text("tenant_id")
                ->nullable()
                ->after("join_date");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("school_profile", function (Blueprint $table) {
            Schema::dropIfExists("tenant_id");
        });
    }
}
