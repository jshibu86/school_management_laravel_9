<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTransportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("transport_staff", function (Blueprint $table) {
            $table->increments("id");
            $table
                ->integer("user_id")
                ->unsigned()
                ->nullable();

            $table->string("email");
            $table->string("mobile");
            $table->string("employee_code")->nullable();
            $table->string("employee_name");
            $table->string("gender");
            $table->string("dob")->nullable();
            $table->string("national_id_number");
            $table->text("address_communication")->nullable();
            $table->string("image")->nullable();
            $table->string("date_ofjoin")->nullable();
            $table->string("blood_group");
            $table->string("maritial_status");
            $table->string("license_no");

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
                ->foreign("user_id")
                ->references("id")
                ->on("users");
        });
        Schema::create("transport_vehicle", function (Blueprint $table) {
            $table->increments("id");

            $table
                ->integer("staff_id")
                ->unsigned()
                ->nullable();
            $table->string("vehicle_type")->nullable();
            $table->string("vehicle_name")->nullable();
            $table->string("bus_no");
            $table->string("image")->nullable();
            $table->string("vehicle_description")->nullable();

            $table->string("capacity")->nullable();
            $table->string("vehicle_reg_no")->nullable();
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
                ->foreign("staff_id")
                ->references("id")
                ->on("transport_staff");
        });

        Schema::create("transport_stop", function (Blueprint $table) {
            $table->increments("id");
            $table->string("stop_name");
            $table->string("type")->nullable();
            $table->text("fare")->nullable();
            $table->string("pickup_time")->nullable();
            $table->string("drop_time")->nullable();
            $table->string("fare_amount");

            $table->text("stop_description")->nullable();
            $table
                ->integer("status")
                ->default(1)
                ->comment("-1=>trash,0=>disable,1=>active");
            $table->timestamps();
        });

        Schema::create("transport_route", function (Blueprint $table) {
            $table->increments("id");
            $table->string("from");
            $table->string("to");
            $table->string("route_description");

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

        Schema::create("transport_route_stop_mapping", function (
            Blueprint $table
        ) {
            $table->increments("id");
            $table->unsignedInteger("transport_route_id");
            $table->unsignedInteger("transport_stop_id");

            $table
                ->integer("status")
                ->default(1)
                ->comment("-1=>trash,0=>disable,1=>active");
            $table->timestamps();

            $table
                ->foreign("transport_route_id")
                ->references("id")
                ->on("transport_route")
                ->onDelete("cascade");
            $table
                ->foreign("transport_stop_id")
                ->references("id")
                ->on("transport_stop")
                ->onDelete("cascade");
        });

        Schema::create("transport_route_bus_mapping", function (
            Blueprint $table
        ) {
            $table->increments("id");
            $table->unsignedInteger("transport_route_id");
            $table->unsignedInteger("transport_vehicle_id");

            $table
                ->integer("status")
                ->default(1)
                ->comment("-1=>trash,0=>disable,1=>active");
            $table->timestamps();
            $table
                ->foreign("transport_route_id")
                ->references("id")
                ->on("transport_route")
                ->onDelete("cascade");
            $table
                ->foreign("transport_vehicle_id")
                ->references("id")
                ->on("transport_vehicle")
                ->onDelete("cascade");
        });

        Schema::create("transport_students", function (Blueprint $table) {
            $table->increments("id");
            $table
                ->integer("academic_year")
                ->unsigned()
                ->nullable();
            $table
                ->integer("student_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("transport_stop_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("transport_vehicle_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("transport_route_id")
                ->unsigned()
                ->nullable();

            $table->string("date_of_reg")->nullable();

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
        Schema::dropIfExists("transport");
    }
}
