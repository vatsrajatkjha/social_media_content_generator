<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StableDiffusionService;
use Illuminate\Support\Facades\Log;
use App\Models\GeneratedImage;

class StableDiffusionController extends Controller
{
    protected $stableDiffusionService;

    /**
     * Inject StableDiffusionService into the controller
     */
    public function __construct(StableDiffusionService $stableDiffusionService)
    {
        $this->stableDiffusionService = $stableDiffusionService;
    }

    /**
     * Show the form for generating images
     */
    public function index()
    {
        return view('stable-diffusion');
    }

    /**
     * Handle the image generation request
     */
    public function generate(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'prompt' => 'required|string|max:500',
                'image_type' => 'required|in:product,lifestyle,abstract,portrait',
                'style' => 'required|in:realistic,artistic,minimal,fantasy',
                'negative_prompt' => 'nullable|string|max:500',
                'width' => 'required|integer|min:256|max:1024',
                'height' => 'required|integer|min:256|max:1024',
                'num_images' => 'required|integer|min:1|max:4'
            ]);

            // Set a longer execution time limit
            set_time_limit(180); // 3 minutes

            // Get the image URL(s) from the service
            $imageUrls = $this->stableDiffusionService->createImageFromPrompt(
                prompt: $validated['prompt'],
                imageType: $validated['image_type'],
                style: $validated['style'],
                negativePrompt: $validated['negative_prompt'] ?? null,
                width: $validated['width'],
                height: $validated['height'],
                numImages: $validated['num_images']
            );

            if (empty($imageUrls)) {
                throw new \Exception('No images were generated');
            }

            // Save each generated image to the database
            $savedImages = [];
            foreach ($imageUrls as $url) {
                $savedImages[] = GeneratedImage::create([
                    'url' => $url,
                    'prompt' => $validated['prompt'],
                    'image_type' => $validated['image_type'],
                    'style' => $validated['style'],
                    'width' => $validated['width'],
                    'height' => $validated['height'],
                    'error_message' => null,
                ]);
            }

            // If it's an AJAX request, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Images generated successfully!',
                    'images' => $savedImages,
                    'prompt' => $validated['prompt'],
                    'image_type' => $validated['image_type'],
                    'style' => $validated['style']
                ]);
            }

            // For regular form submissions, redirect back with the results
            return back()->with([
                'success' => 'Images generated successfully!',
                'image_urls' => $imageUrls,
                'prompt' => $validated['prompt']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Image Generation Error: ' . $e->getMessage());
            
            // Only log to the database if prompt is present and not empty
            $prompt = $request->input('prompt');
            if (!empty($prompt)) {
                GeneratedImage::create([
                    'url' => null,
                    'prompt' => $prompt,
                    'image_type' => $request->input('image_type'),
                    'style' => $request->input('style'),
                    'width' => $request->input('width'),
                    'height' => $request->input('height'),
                    'error_message' => $e->getMessage(),
                ]);
            }

            $errorMessage = 'Failed to generate image. Please try again with a simpler prompt or different settings.';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => $errorMessage])->withInput();
        }
    }

    /**
     * List all generated images
     */
    public function list()
    {
        $images = GeneratedImage::orderByDesc('created_at')->paginate(20);
        return view('generated-images.index', compact('images'));
    }

    public function regenerate($id)
    {
        try {
            $image = GeneratedImage::findOrFail($id);
            
            // Set a longer execution time limit
            set_time_limit(180); // 3 minutes

            // Get the image URL(s) from the service
            $imageUrls = $this->stableDiffusionService->createImageFromPrompt(
                prompt: $image->prompt,
                imageType: $image->image_type,
                style: $image->style,
                negativePrompt: $image->negative_prompt,
                width: $image->width,
                height: $image->height,
                numImages: 1
            );

            if (empty($imageUrls)) {
                throw new \Exception('No images were generated');
            }

            // Update the existing image record
            $image->update([
                'url' => $imageUrls[0],
                'error_message' => null,
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image regenerated successfully!',
                    'image' => $image,
                ]);
            }

            return redirect()->route('generated-images.index')
                ->with('success', 'Image regenerated successfully!');

        } catch (\Exception $e) {
            Log::error('Image Regeneration Error: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?? 'An error occurred while regenerating the image',
                    'can_retry' => true
                ], 500);
            }
            
            return back()->with('error', $e->getMessage() ?? 'An error occurred while regenerating the image');
        }
    }
}
