<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StableDiffusionService
{
    protected $client;
    protected $models = [
        'stabilityai/sdxl-turbo',
        'stabilityai/stable-diffusion-xl-base-1.0',
        'runwayml/stable-diffusion-v1-5'
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function createImageFromPrompt(
        string $prompt,
        string $imageType,
        string $style,
        ?string $negativePrompt = null,
        int $width = 512,
        int $height = 512,
        int $numImages = 1
    ): array {
        $attempts = 2;
        $delay = 3;
        $lastException = null;
        $imageUrls = [];

        // Remove emojis from prompt
        $prompt = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]+/u', '', $prompt);

        // Strong negative prompt
        $defaultNegativePrompt = 'blurry, low quality, distorted, deformed, cartoon, abstract, text, watermark, bad anatomy, cropped, out of frame, worst quality, lowres, jpeg artifacts, signature, error, extra limbs, mutated hands, poorly drawn, disfigured';
        $negativePrompt = $negativePrompt ?? $defaultNegativePrompt;

        // Use enhanced prompt
        $enhancedPrompt = $this->enhancePrompt($prompt, $imageType, $style);

        foreach ($this->models as $model) {
            for ($i = 0; $i < $attempts; $i++) {
                try {
                    $apiKey = env('HUGGING_FACE_API_KEY');

                    if (empty($apiKey)) {
                        throw new Exception("API key not configured. Please set HUGGING_FACE_API_KEY in your .env file.");
                    }

                    Log::info("Attempting to generate image with model: " . $model);

                    $response = $this->client->post("https://api-inference.huggingface.co/models/{$model}", [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $apiKey,
                            'Accept' => 'application/json'
                        ],
                        'json' => [
                            'inputs' => $enhancedPrompt,
                            'parameters' => [
                                'negative_prompt' => $negativePrompt,
                                'num_inference_steps' => 20,
                                'guidance_scale' => 7.0,
                                'width' => $width,
                                'height' => $height,
                                'num_images_per_prompt' => $numImages
                            ]
                        ],
                        'timeout' => 45,
                        'connect_timeout' => 7,
                        'read_timeout' => 45
                    ]);

                    $contentType = $response->getHeaderLine('Content-Type');

                    if (strstr($contentType, 'application/json')) {
                        $data = json_decode($response->getBody()->getContents(), true);
                        Log::info('API response (JSON): ' . json_encode($data));

                        if (isset($data[0]['url'])) {
                            Log::info("Successfully generated image with model: " . $model);
                            return array_map(fn($item) => $item['url'], $data);
                        } else if (isset($data['error'])) {
                            throw new Exception("API Error: " . $data['error']);
                        }

                        Log::error('Unexpected API JSON response: ' . json_encode($data));
                        throw new Exception("Failed to generate image: Unexpected JSON response");
                    } else {
                        $imageData = $response->getBody()->getContents();
                        $directory = 'images/generated';
                        $path = public_path($directory);

                        if (!file_exists($path)) {
                            mkdir($path, 0755, true);
                        }

                        $filename = 'image_' . uniqid() . '.png';
                        $fullPath = $path . '/' . $filename;

                        file_put_contents($fullPath, $imageData);
                        Log::info('Image saved to: ' . $fullPath);

                        return [asset($directory . '/' . $filename)];
                    }
                } catch (\GuzzleHttp\Exception\ConnectException $e) {
                    $lastException = $e;
                    Log::error("Connection error with model {$model} on attempt " . ($i + 1) . ": " . $e->getMessage());
                    if ($i < $attempts - 1) {
                        sleep($delay);
                    }
                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    $lastException = $e;
                    Log::error("Request error with model {$model} on attempt " . ($i + 1) . ": " . $e->getMessage());
                    if ($i < $attempts - 1) {
                        sleep($delay);
                    }
                } catch (Exception $e) {
                    $lastException = $e;
                    Log::error("API error with model {$model} on attempt " . ($i + 1) . ": " . $e->getMessage());
                    if ($i < $attempts - 1) {
                        sleep($delay);
                    }
                }
            }
        }

        if ($lastException) {
            throw new Exception("Failed to generate image with all available models: " . $lastException->getMessage());
        } else {
            throw new Exception("Unknown error occurred during image generation");
        }
    }

    /**
     * Simplified prompt enhancement
     */
    private function enhancePrompt(string $prompt, string $imageType, string $style): string
    {
        // Enhanced style descriptions
        $styleEnhancements = [
            'realistic' => 'highly detailed, photorealistic, 8k uhd, professional photography, sharp focus, cinematic lighting',
            'artistic' => 'artistic masterpiece, professional digital art, trending on artstation, highly detailed, vibrant colors',
            'minimal' => 'minimalist style, clean lines, modern design, simple composition, elegant',
            'fantasy' => 'fantasy art, epic composition, magical atmosphere, highly detailed, trending on artstation'
        ];

        // Enhanced type descriptions
        $typeEnhancements = [
            'product' => 'professional product photography, studio lighting, commercial photography, high-end product shot',
            'lifestyle' => 'lifestyle photography, natural lighting, candid moment, authentic atmosphere',
            'abstract' => 'abstract art, modern art, contemporary style, unique composition',
            'portrait' => 'professional portrait photography, studio quality, perfect lighting, sharp focus'
        ];

        $styleEnhancement = $styleEnhancements[$style] ?? '';
        $typeEnhancement = $typeEnhancements[$imageType] ?? '';

        // Combine enhancements with proper formatting
        $enhancedPrompt = $prompt;
        if (!empty($styleEnhancement)) {
            $enhancedPrompt .= ", " . $styleEnhancement;
        }
        if (!empty($typeEnhancement)) {
            $enhancedPrompt .= ", " . $typeEnhancement;
        }

        // Add quality boosters
        $enhancedPrompt .= ", high quality, professional, masterpiece, best quality, ultra detailed";

        return $enhancedPrompt;
    }
}
