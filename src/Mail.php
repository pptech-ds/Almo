<?php
namespace App;

use Mailjet\Client;
use Mailjet\Resources;


class Mail
{
    /**
     * Les privés API MailJet
     */
    private $api_key = 'afd4b6c487b6c9a9ac8283d112491d77';
    private $api_key_secret = '317b672564831176eba828996379e0cf';

    /**
     * Méthode d'envoie de mail
     */
    public function send($to_email, $to_name, $subject, $content)
    {
        //Notre objet MailJet
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
        //Corps du mail
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => 'almo.prasdev@bbox.fr',
                        'Name' => 'Almo',
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name,
                        ],
                    ],
                    //L'id du template crée sur Mailjet
                    'TemplateID' => 3074942,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    //Les variables à envoyer au template MailJet
                    'Variables' => [
                        'content' => $content,
                    ],
                ],
            ],
        ];

        // dd($mj->post(Resources::$Email, ['body' => $body]));
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        // $response->success();
        $response->success();
    }
}