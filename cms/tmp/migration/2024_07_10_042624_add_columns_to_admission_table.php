<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAdmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admission', function (Blueprint $table) {
            $table->string("reject_msg")->nullable()->after("previous_school");
            $table->string("admission_status")->nullable()->after("postal_code");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admission', function (Blueprint $table) {
            $table->dropColumn("reject_msg");
            $table->dropColumn("admission_status");
        });
    }
}
