<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("staff_attendance", function (Blueprint $table) {
            $table->increments("id");
            $table
                ->integer("user_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("group_id")
                ->unsigned()
                ->nullable();

            $table
                ->integer("academic_year")
                ->unsigned()
                ->nullable();

            $table
                ->string("attendance")
                ->comment("1=>present,0=>absent,2=>late");

            $table->string("attendance_date")->nullable();
            $table->string("attendance_month")->nullable();
            $table->string("attendance_year")->nullable();

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
        //
    }
}
