<?php

namespace Database\Seeders;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use cms\core\configurations\Models\ConfigurationModel;

class ConfigurationMailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existingConfig = ConfigurationModel::where('name', 'mail')->first();

        $data = [
            'name' => 'mail',
            'parm' => json_encode([
                'from_mail' => 'schoolmasterng@gmail.com',
                'from_mailer' => 'gmail',
                'from_mail_password' => 'jlsd lizw aovo nxiy',
                'from_mail_name' => 'schoolmasterng@gmail.com',
                'mail_trap_from_mail' => 'schoolmanagement@gmail.com',
                'mail_trap_from_mailer' => 'mailtrap',
                'mail_trap_from_mail_password' => '8cfa8b4fb9bb04',
                'mail_trap_from_mail_name' => 'Schoolmanagement',
                'mail_trap_from_mail_username' => '2b8008a541a68f'
            ]),
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ];

        if ($existingConfig) {
            // Update existing record
            ConfigurationModel::where('id', $existingConfig->id)->update($data);
        } else {
            // Insert new record
            ConfigurationModel::insert($data);
        }
    }
}
