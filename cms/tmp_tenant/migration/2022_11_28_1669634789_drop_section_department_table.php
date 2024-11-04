<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class DropSectionDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        Schema::table("section", function (Blueprint $table) {
            if (Schema::hasColumn("section", "department_id")) {
                $table->dropForeign("section_department_id_foreign");
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
