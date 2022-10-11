<?php

namespace App\Security;

use Mailjet\Client;
use Mailjet\Resources;

class MailStructure
{
    public function send($to_email, $to_name, $subject): void
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
                    'TemplateID' => 4247328,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }

    public function toFranchise($to_email, $to_name, $subject, $address): void
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
                    'TemplateID' => 4247814,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'address' => $address
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }
}