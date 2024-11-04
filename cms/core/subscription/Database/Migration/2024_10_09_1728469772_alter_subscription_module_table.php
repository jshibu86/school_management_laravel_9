<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSubscriptionModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("module", function (Blueprint $table) {
            $table
                ->integer("type")
                ->comment("1=>core,2=>local")
                ->after("module_slug");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("module", function (Blueprint $table) {
            $table->dropColumn("type");
        });
    }
}
