<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIImageService
{
    protected $apiKey;
    protected $endpoint = 'https://api.openai.com/v1/images/generations';

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
    }

    public function generateImage(string $prompt, string $size = '1024x1024')
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->endpoint, [
            'prompt' => $prompt,
            'model' => 'dall-e-2', // ✅ Use DALL-E 2 for now
            'size' => $size,
            'response_format' => 'url'
        ]);

        if ($response->successful()) {
            return $response->json('data');
        }

        Log::error('OpenAI image generation failed', $response->json());
        throw new \Exception('OpenAI API request failed: ' . $response->body());
    }
}
