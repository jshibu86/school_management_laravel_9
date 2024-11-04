<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLclassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create("lclass", function (Blueprint $table) {
            $table->increments("id");
            $table->string("name");
            $table->integer("academic_year");
            $table->text("note")->nullable();
            $table
                ->integer("status")
                ->default(1)
                ->comment("-1=>trash,0=>disable,1=>active");
            $table->timestamp("created_at")->useCurrent();
            $table
                ->timestamp("updated_at")
                ->default(
                    DB::raw("CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
                );
            $table->userstamps();
            $table->softUserstamps();
            $table->softDeletes();
        });
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    public function down()
    {
        Schema::dropIfExists("lclass");
    }
}
