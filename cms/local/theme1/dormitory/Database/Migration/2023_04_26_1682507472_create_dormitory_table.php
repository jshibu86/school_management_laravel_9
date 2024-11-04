<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDormitoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("dormitory_room_type", function (Blueprint $table) {
            $table->increments("id");
            $table->string("room_type");
            $table->text("room_type_description")->nullable();
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
        });

        Schema::create("dormitory", function (Blueprint $table) {
            $table->increments("id");
            $table->string("dormitory_name");
            $table->string("dormitory_type");
            $table->text("dormitory_address");
            $table->text("dormitory_description")->nullable();
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
        });

        Schema::create("dormitory_rooms", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("dormitory_id")->unsigned();
            $table->integer("room_type")->unsigned();
            $table->string("room_number");
            $table->string("number_of_bed")->nullable();
            $table->text("cost_per_bed");
            $table->text("room_description")->nullable();
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
        });
        Schema::create("dormitory_students", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("student_id")->unsigned();
            $table->integer("dormitory_id")->unsigned();
            $table->integer("room_id")->unsigned();

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("dormitory_room_type");
        Schema::dropIfExists("dormitory");
    }
}
