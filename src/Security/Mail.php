<?php

namespace App\Security;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    public function send($to_email, $to_name, $subject, $address, $email, $password): void
    {
        $mj = new Client(getenv('MAIL_API_KEY'), getenv('MAIL_API_SECRET'),true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "arthur_berthou@hotmail.com",
                        'Name' => "Haltère Ego Admin"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4211335,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        "address" => $address,
                        "email" => $email,
                        "password" => $password
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
    }

    public function sendPermissions($to_email, $to_name, $subject): void
    {
        $mj = new Client(getenv('MAIL_API_KEY'), getenv('MAIL_API_SECRET'),true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "arthur_berthou@hotmail.com",
                        'Name' => "Haltère Ego Admin"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4247600,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
    }
}