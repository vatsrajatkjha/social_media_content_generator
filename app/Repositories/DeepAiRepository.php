<?php

namespace App\Repositories;

use GuzzleHttp\Client;

class DeepAiRepository
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.deepai.key'); // store API key in config
    }

    public function generateImage(string $prompt)
    {
        $response = $this->client->post('https://api.deepai.org/api/text2img', [
            'headers' => [
                'Api-Key' => $this->apiKey,
            ],
            'form_params' => [
                'text' => $prompt,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
