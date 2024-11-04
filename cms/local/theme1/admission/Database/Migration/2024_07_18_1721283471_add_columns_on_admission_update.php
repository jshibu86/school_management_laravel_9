<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsOnAdmissionUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("admission", function (Blueprint $table) {
            $table
                ->string("stu_department")
                ->nullable()
                ->after("admission_status");
            $table
                ->string("religion")
                ->nullable()
                ->after("reject_msg");
            $table
                ->string("stu_document_upload1")
                ->nullable()
                ->after("religion");
            $table
                ->string("stu_document_upload2")
                ->nullable()
                ->after("stu_document_upload1");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("admission", function (Blueprint $table) {
            $table->dropColumn("religion");
            $table->dropColumn("alert_message");
            $table->dropColumn("stu_document_upload1");
            $table->dropColumn("stu_document_upload2");
        });
    }
}
