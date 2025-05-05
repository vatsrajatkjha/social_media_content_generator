<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIImageService;
use Illuminate\Support\Facades\Log;

class ImageGenerationController extends Controller
{
    protected $imageService;

    public function __construct(OpenAIImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function showForm()
    {
        return view('image.generate');
    }



    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:255',
        ]);

        try {
            $results = $this->imageService->generateImage($request->prompt);
            return view('image.generate', ['images' => $results]);
        } catch (\Exception $e) {
            Log::error('Image generation failed for prompt: ' . $request->prompt, [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
