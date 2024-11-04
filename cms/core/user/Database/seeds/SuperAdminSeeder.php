<?php
namespace cms\core\user\Database\seeds;
use Illuminate\Database\Seeder;
use DB;
use Hash;
class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Seeding Cental db users and Roles");
        //user groups
        DB::table("user_groups")->insert([
            [
                "group" => "Super Admin",
                "status" => 1,
            ],
            [
                "group" => "Accountant",
                "status" => 1,
            ],
            [
                "group" => "Receptionist",
                "status" => 1,
            ],
        ]);

        //create admin users
        $usersArray = [
            [
                "name" => "superadmin Central",
                "username" => "admin",
                "email" => "centraladmin@admin.com",
                "password" => Hash::make("admin123"),
                "status" => 1,
            ],

            [
                "name" => "central_accountant",
                "username" => "accountant",
                "email" => "centralaccountant@accountant.com",
                "password" => Hash::make("accountant123"),
                "status" => 1,
            ],

            [
                "name" => "central_receptionist",
                "username" => "receptionist",
                "email" => "centralreceptionist@receptionist.com",
                "password" => Hash::make("receptionist123"),
                "status" => 1,
            ],
        ];
        DB::table("users")->insert($usersArray);
        //map admin user to group
        $groupMaparray = [
            [
                "user_id" => 1,
                "group_id" => 1,
            ],
            [
                "user_id" => 2,
                "group_id" => 2,
            ],
            [
                "user_id" => 3,
                "group_id" => 3,
            ],
        ];
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        DB::table("user_group_map")->insert($groupMaparray);
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");
    }
}
