<?php

namespace App\Security;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private string $api_key = '0045c4a14e8928459b09c927ef4ebc6d';
    private string $api_key_secret = '2522cf3a5072dfc0ba5f8239aea53df2';
    public function send($to_email, $to_name, $subject, $address, $email, $password): void
    {
        $mj = new Client('0045c4a14e8928459b09c927ef4ebc6d', '2522cf3a5072dfc0ba5f8239aea53df2',true,['version' => 'v3.1']);
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
                        'address' => $address,
                        'email' => $email,
                        'password' => $password
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }
}