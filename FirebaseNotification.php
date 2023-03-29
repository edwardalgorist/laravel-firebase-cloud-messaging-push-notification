<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class FirebaseNotification
{

    private Client $client;

    public function __construct($key = null)
    {

        if($key !== null)
            $this->init($key);

    }

    public function init($key): void
    {
        $this->client = new Client($this->getConfig($key));
    }

    /**
     * @throws GuzzleException
     */
    public function send($token, $title, $body, $priority = 'high', $mutable_content = true, $sound = 'default', $data = []): stdClass
    {

        $data = [
            "to"=> $token,
            "priority" => $priority,
            "notification"=> [
                "title"=> $title,
                "body"=> $body,
                "mutable_content"=> $mutable_content,
                "sound"=> $sound
            ],
            "data" => $data
        ];

        $response = $this->client->post('send', ['body' => json_encode($data)])->getBody()->getContents();

        return json_decode($response);

    }

    protected function getConfig($key): array
    {

        $headers = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'key='.$key
        ];

        return [
            'base_uri' => 'https://fcm.googleapis.com/fcm/',
            'headers' => $headers
        ];

    }

}

