<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDecripitionOnIndividualMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gmail_individual_messages', function (Blueprint $table) {
            $table->text('message')->change(); 
        });

        Schema::table('gmail_individual_messages_mapping', function (Blueprint $table) {
            $table->text('message')->change(); 
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
