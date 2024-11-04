<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGmailIndividualMessagesMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmail_individual_messages_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("message_id")->unsigned();
            $table->integer("senter")->unsigned();
            $table->string("message")->nullable();
            $table->json("files")->nullable();
            $table->string("time")->nullable();
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
        Schema::dropIfExists('gmail_individual_messages_mapping');
    }
}
