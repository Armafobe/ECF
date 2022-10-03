<?php

namespace App\Security;

use Mailjet\Client;
use Mailjet\Resources;

class MailStructure
{
    public function send($to_email, $to_name, $subject): void
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