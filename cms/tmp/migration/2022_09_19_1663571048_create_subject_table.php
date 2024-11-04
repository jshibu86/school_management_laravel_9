<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("subject", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("class_id")->unsigned();
            $table->string("academic_year")->nullable();
            $table->string("name");
            $table->string("subject_code");
            $table->string("type");
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

            $table
                ->foreign("class_id")
                ->references("id")
                ->on("lclass");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("subject");
    }
}
