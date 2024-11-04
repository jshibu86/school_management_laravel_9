<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DropTeacherDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");

        Schema::table("teacher", function (Blueprint $table) {
            // Check if 'department_id' column exists before trying to drop it
            if (Schema::hasColumn("teacher", "department_id")) {
                // Drop the foreign key only if it exists
                $foreignKeys = DB::select(
                    DB::raw("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'teacher' 
            AND COLUMN_NAME = 'department_id'
            AND CONSTRAINT_NAME != 'PRIMARY'
        ")
                );

                if (!empty($foreignKeys)) {
                    $foreignKeyName = $foreignKeys[0]->CONSTRAINT_NAME;
                    $table->dropForeign([$foreignKeyName]); // Drop the foreign key using its name
                }

                // Drop the 'department_id' column
                $table->dropColumn("department_id");
            }
        });

        DB::statement("SET FOREIGN_KEY_CHECKS=1;");
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
