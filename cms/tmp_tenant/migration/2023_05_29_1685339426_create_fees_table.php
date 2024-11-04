<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("fee_types", function (Blueprint $table) {
            $table->increments("id");
            $table->string("type_name");
            $table->string("type_slug");
            $table->text("type_description")->nullable();
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

            // $table
            // ->foreign("class_id")
            // ->references("id")
            // ->on("lclass");
        });

        Schema::table("students", function (Blueprint $table) {
            $table
                ->string("scholarship")
                ->nullable()
                ->after("dob");
            $table
                ->text("scholarship_note")
                ->nullable()
                ->after("scholarship");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("fees");
    }
}
