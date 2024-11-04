<?php
namespace cms\core\user\Database\seeds;
use Illuminate\Database\Seeder;
use DB;
use Hash;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Seeding users and Roles");
        //user groups
        DB::table("user_groups")->insert([
            [
                "group" => "Super Admin",
                "status" => 1,
            ],
            [
                "group" => "Principal",
                "status" => 1,
            ],
            [
                "group" => "Teacher",
                "status" => 1,
            ],
            [
                "group" => "Student",
                "status" => 1,
            ],
            [
                "group" => "Parent",
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
                "name" => "admin",
                "username" => "admin",
                "email" => "admin@admin.com",
                "password" => Hash::make("admin123"),
                "status" => 1,
            ],
            [
                "name" => "teacher",
                "username" => "teacher",
                "email" => "teacher@teacher.com",
                "password" => Hash::make("teacher123"),
                "status" => 1,
            ],
            [
                "name" => "student",
                "username" => "student",
                "email" => "student@student.com",
                "password" => Hash::make("student123"),
                "status" => 1,
            ],
            [
                "name" => "parent",
                "username" => "parent",
                "email" => "parent@parent.com",
                "password" => Hash::make("parent123"),
                "status" => 1,
            ],
            [
                "name" => "accountant",
                "username" => "accountant",
                "email" => "accountant@accountant.com",
                "password" => Hash::make("accountant123"),
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
                "group_id" => 3,
            ],
            [
                "user_id" => 3,
                "group_id" => 4,
            ],
            [
                "user_id" => 4,
                "group_id" => 5,
            ],
            [
                "user_id" => 5,
                "group_id" => 6,
            ],
        ];
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        DB::table("user_group_map")->insert($groupMaparray);
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");
    }
}
