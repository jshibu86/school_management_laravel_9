<?php
namespace cms\core\layout\helpers;

use Config;
use Configurations;
use Cms;

use cms\core\configurations\Models\ConfigurationModel;
use Illuminate\Support\Facades\Log;
class CmsMail
{
    /*
     * set mail config
     */
    public static function setMailConfig($config = [])
    {
        if (count((array) $config) == 0) {
            $mailer = Configurations::getConfig("mail");
            $mail = (object) self::getMailerList($mailer->from_mailer);
            $config = [
                "driver" => $mail->driver,
                "host" => $mail->host,
                "port" => $mail->port,
                "from" => [
                    "address" => $mailer->from_mail,
                    "name" => "Admin",
                ],
                "encryption" => $mail->encryption,
                "username" => $mailer->from_mail_name,
                "password" => base64_decode($mailer->from_mail_password),
                "sendmail" => "/usr/sbin/sendmail -bs",
                "pretend" => false,
            ];
        }
        Log::info("Setting MailTrap Config:", [
            "mailer" => $mailer,
            "mail" => $mail,
            "initial_config" => $config,
        ]);
        Config::set("mail", $config);
    }
    public static function setMailTrapConfig($config = [])
    {
        if (count((array) $config) == 0) {
            $mailer = Configurations::getConfig("mail");
            if (isset($mailer->mail_trap_from_mailer)) {
                $mail = (object) self::getMailerList(
                    $mailer->mail_trap_from_mailer
                );
                // dd($mailer, $mail);
                if (config("app.env") === "local") {
                    $config = [
                        "driver" => "smtp",
                        "host" => "smtp.mailtrap.io",
                        "port" => 2525,
                        "from" => [
                            "address" => $mailer->mail_trap_from_mailer,
                            "name" => "Admin",
                        ],
                        "encryption" => "tls",
                        "username" => "fcdfbe4ad8a91a",
                        "password" => "3d8bfcc38ffc37",
                        "pretend" => false,
                    ];
                } else {
                    $config = [
                        "driver" => $mail->driver,
                        "host" => "sandbox.smtp.mailtrap.io",
                        "port" => $mail->port,
                        "from" => [
                            "address" => $mailer->mail_trap_from_mailer,
                            "name" => "Admin",
                        ],
                        "encryption" => $mail->encryption,
                        "username" => $mailer->mail_trap_from_mail_username,
                        "password" => $mailer->mail_trap_from_mail_password,
                        "sendmail" => "/usr/sbin/sendmail -bs",
                        "pretend" => false,
                    ];
                }
            }
        }

        Config::set("mail", $config);
    }
    /*
     * get mailer list
     */
    public static function getMailerList($mailer_name = "")
    {
        $data = include Cms::module("layout")->getCorePath() .
            DIRECTORY_SEPARATOR .
            "config" .
            DIRECTORY_SEPARATOR .
            "mailer.php";
        if ($mailer_name != "") {
            $data = $data[$mailer_name];
        }
        return $data;
    }

    public static function setExternalMailTrapConfig(
        $senter_mail,
        $senter_name,
        $config = []
    ) {
        // dd($senter_mail,$senter_name);
        if (count((array) $config) == 0) {
            $mailer = Configurations::getConfig("mail");
            if (isset($mailer->mail_trap_from_mailer)) {
                $mail = (object) self::getMailerList(
                    $mailer->mail_trap_from_mailer
                );
                // dd($mail->driver,$mail->port,$senter_mail,$senter_name,$mail->encryption);
                $config = [
                    "driver" => $mail->driver,
                    "host" => "sandbox.smtp.mailtrap.io",
                    "port" => $mail->port,
                    "from" => [
                        "address" => $senter_mail,
                        "name" => $senter_name,
                    ],
                    "encryption" => $mail->encryption,
                    "username" => "fcdfbe4ad8a91a",
                    "password" => "3d8bfcc38ffc37",
                    "sendmail" => "/usr/sbin/sendmail -bs",
                    "pretend" => false,
                ];
            }
        }

        Config::set("mail", $config);
    }
}
