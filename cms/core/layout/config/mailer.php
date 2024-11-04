<?php
/*
 * mailer lists
 */
return [
    "gmail" => [
        "driver" => "smtp",
        "host" => "smtp.gmail.com",
        "port" => 465,
        "encryption" => "ssl",
    ],
    "mailtrap" => [
        "driver" => "smtp",
        "host" => "sandbox.smtp.mailtrap.io",
        "port" => 2525,
        "encryption" => "tls",
    ],
];
