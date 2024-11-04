<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("configurations", function (Blueprint $table) {
            $table->increments("id");
            $table->string("name")->unique();
            $table->string("parm", 15000);
            $table->timestamp("created_at")->useCurrent();
            $table
                ->timestamp("updated_at")
                ->default(
                    DB::raw("CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
                );
        });

        // insert default config

        $config = [
            "school_name" => "S-Management",
            "school_email" => "school@gmail.com",
            "school_phone" => "9889897678",
            "school_landline" => "04651789876",
            "place" => "Nigeria School",
            "city" => "Kano",
            "post" => "Post Office",
            "pin_code" => "700107",
            "country" => "Nigeria",
            "time_zone" => "Asia\/Kolkata",
        ];

        $mailconfig = [
            "from_mail" => "schoolmasterng@gmail.com",
            "from_mailer" => "gmail",
            "from_mail_password" => "amxzZCBsaXp3IGFvdm8gbnhpeQ==",
            "from_mail_name" => "schoolmasterng@gmail.com",
            "mail_trap_from_mail" => "admin",
            "mail_trap_from_mailer" => "gmail",
            "mail_trap_from_mail_password" => null,
            "mail_trap_from_mail_name" => null,
            "mail_trap_from_mail_username" => null,
        ];

        DB::table("configurations")->insert([
            "name" => "mail",
            "parm" => json_encode($mailconfig),
        ]);
        DB::table("configurations")->insert([
            "name" => "site",
            "parm" => json_encode($config),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("configurations");
    }
}
