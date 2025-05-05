<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ContentRepositoryInterface;
use App\Services\ContentGeneratorService;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    protected $contentRepository;
    protected $contentGenerator;

    public function __construct(
        ContentRepositoryInterface $contentRepository,
        ContentGeneratorService $contentGenerator
    ) {
        $this->contentRepository = $contentRepository;
        $this->contentGenerator = $contentGenerator;
    }

    public function index()
    {
        $contents = $this->contentRepository->getAllContents()->paginate(10);
        return view('contents.index', compact('contents'));
    }

    public function create()
    {
        $platforms = [
            'twitter' => 'Twitter/X',
            'linkedin' => 'LinkedIn',
            'instagram' => 'Instagram',
            'facebook' => 'Facebook'
        ];

        return view('contents.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'prompt' => 'required|string|min:10',
                'platform' => 'required|string|in:twitter,linkedin,instagram,facebook',
                'regenerate' => 'nullable|boolean'
            ]);

            $result = $this->contentGenerator->generateContent(
                $request->prompt,
                $request->platform
            );

            if (!$result['success']) {
                throw new \Exception($result['message'] ?? 'Failed to generate content');
            }

            $content = $this->contentRepository->saveContent([
                'prompt' => $request->prompt,
                'content' => $result['content'],
                'platform' => $request->platform,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Content generated successfully!',
                    'content' => $content,
                    'redirect_url' => route('contents.show', $content->id)
                ]);
            }

            return redirect()->route('contents.show', $content->id)
                ->with('success', 'Content generated successfully!');

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
            \Log::error('Content Generation Error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?? 'An error occurred while generating content',
                    'can_retry' => true
                ], 500);
            }
            
            return back()->withInput()->with('error', $e->getMessage() ?? 'An error occurred while generating content');
        }
    }

    public function show($id)
    {
        $content = $this->contentRepository->getContentById($id);
        return view('contents.show', compact('content'));
    }


    public function edit($id)
    {
        $content = $this->contentRepository->getContentById($id);
        $platforms = [
            'twitter' => 'Twitter/X',
            'linkedin' => 'LinkedIn',
            'instagram' => 'Instagram',
            'facebook' => 'Facebook'
        ];

        return view('contents.edit', compact('content', 'platforms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'platform' => 'required|string|in:twitter,linkedin,instagram,facebook',
        ]);

        $content = $this->contentRepository->updateContent($id, [
            'content' => $request->content,
            'platform' => $request->platform,
        ]);

        return redirect()->route('contents.show', $content->id)
            ->with('success', 'Content updated successfully!');
    }

    public function regenerate($id)
    {
        try {
            $content = $this->contentRepository->getContentById($id);
            
            if (!$content) {
                throw new \Exception('Content not found');
            }

            $result = $this->contentGenerator->generateContent(
                $content->prompt,
                $content->platform
            );

            if (!$result['success']) {
                throw new \Exception($result['message'] ?? 'Failed to regenerate content');
            }

            $content = $this->contentRepository->updateContent($id, [
                'content' => $result['content']
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Content regenerated successfully!',
                    'content' => $content
                ]);
            }

            return redirect()->route('contents.show', $content->id)
                ->with('success', 'Content regenerated successfully!');

        } catch (\Exception $e) {
            \Log::error('Content Regeneration Error: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?? 'An error occurred while regenerating content',
                    'can_retry' => true
                ], 500);
            }
            
            return back()->with('error', $e->getMessage() ?? 'An error occurred while regenerating content');
        }
    }
}
