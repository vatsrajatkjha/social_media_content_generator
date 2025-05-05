<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ContentGeneratorService
{
    protected $client;
    protected $apiUrl;
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = config('services.anthropic.url');
        $this->apiKey = config('services.anthropic.key');
        $this->model = config('services.anthropic.model');
    }

    public function generateContent($prompt, $platform, $retryCount = 0)
    {
        try {
            $systemPrompt = $this->getSystemPromptForPlatform($platform);
            $maxRetries = 3;

            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->apiKey,
                    'anthropic-version' => '2023-06-01'
                ],
                'json' => [
                    'model' => $this->model,
                    'max_tokens' => 1000,
                    'system' => $systemPrompt,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ]
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);
            $generatedContent = $responseData['content'][0]['text'] ?? '';

            // Validate the generated content
            if (!$this->validateContent($generatedContent, $platform)) {
                if ($retryCount < $maxRetries) {
                    Log::warning("Content validation failed, retrying... Attempt: " . ($retryCount + 1));
                    return $this->generateContent($prompt, $platform, $retryCount + 1);
                }
                throw new \Exception("Failed to generate valid content after {$maxRetries} attempts");
            }

            return [
                'success' => true,
                'content' => $generatedContent,
                'platform' => $platform,
                'prompt' => $prompt
            ];
        } catch (\Exception $e) {
            Log::error('Content generation error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to generate content: ' . $e->getMessage(),
                'platform' => $platform,
                'prompt' => $prompt,
                'can_retry' => $retryCount < 3
            ];
        }
    }

    protected function validateContent($content, $platform)
    {
        if (empty($content)) {
            return false;
        }

        // Platform-specific validation
        switch ($platform) {
            case 'twitter':
                return strlen($content) <= 280;
            case 'linkedin':
                return strlen($content) >= 50 && strlen($content) <= 3000;
            case 'instagram':
                return strlen($content) >= 20 && strlen($content) <= 2200;
            case 'facebook':
                return strlen($content) >= 20 && strlen($content) <= 5000;
            default:
                return true;
        }
    }

    protected function getSystemPromptForPlatform($platform)
    {
        $prompts = [
            'twitter' => "You are a social media expert who creates engaging Twitter/X posts. Keep responses under 280 characters, use relevant hashtags, and create content that drives engagement. Make it conversational and aligned with Twitter's style. Include 1-3 relevant hashtags.",

            'linkedin' => 'You are a professional content creator for LinkedIn. Create professional, informative posts that would be appropriate for a business setting. Include relevant hashtags, and structure the content to maximize professional engagement. Keep the tone professional and authoritative.',

            'instagram' => 'You are an Instagram content specialist. Create engaging, visual-friendly captions that would complement images well. Include relevant hashtags and emoji where appropriate. Make the content conversational and engaging. Include 5-10 relevant hashtags.',

            'facebook' => 'You are a Facebook content expert. Create engaging posts that encourage community interaction. The tone should be friendly and conversational, with content structured to drive comments and shares. Include 2-4 relevant hashtags.',
        ];

        return $prompts[$platform] ?? 'Create an engaging social media post based on the provided prompt.';
    }
}
