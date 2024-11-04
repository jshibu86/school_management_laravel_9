<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualMeetingList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_comunication_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title");
            $table->integer("moderator")->unsigned();
            $table->string("meeting_token")->nullable();
            $table->string("meeting_date")->nullable();
            $table->string("time")->nullable();
            $table->integer("status")->default(1)->comment("-1=>trash,0=>disable,1=>active");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        Schema::create('virtual_comunication_list_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("virtual_comunication_list_id")->unsigned();
            $table->string("participants");
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
        Schema::dropIfExists('virtual_comunication_list');
    }
}
