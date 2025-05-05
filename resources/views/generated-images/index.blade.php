@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8 py-8 dark:bg-gray-900 dark:text-gray-100">
    <div class="bg-white dark:bg-gray-950 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-900 dark:to-gray-800 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">All Generated Images</h2>
            <a href="{{ route('stable-diffusion.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">Generate New Image</a>
        </div>
        <div class="p-6">
            @if($images->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No images generated yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start by generating your first image.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($images as $image)
                        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg shadow-sm p-4 flex flex-col">
                            @if($image->url)
                                <img src="{{ $image->url }}" alt="Generated image" class="w-full h-64 object-cover rounded mb-4">
                                <div class="flex space-x-2 mb-2">
                                    <a href="{{ $image->url }}" download class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Download</a>
                                    <button class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 copy-image-url-btn transition" data-url="{{ $image->url }}">Copy URL</button>
                                </div>
                            @else
                                <div class="h-64 flex items-center justify-center bg-red-50 dark:bg-red-900 rounded mb-4">
                                    <span class="text-red-500 dark:text-red-200 font-semibold">Generation Failed</span>
                                </div>
                            @endif
                            <div class="text-xs text-gray-500 dark:text-gray-300 mb-1">Prompt: <span class="text-gray-700 dark:text-gray-100">{{ $image->prompt }}</span></div>
                            <div class="flex flex-wrap gap-2 text-xs text-gray-400 dark:text-gray-400 mb-1">
                                <span>Type: {{ $image->image_type }}</span>
                                <span>Style: {{ $image->style }}</span>
                                <span>Size: {{ $image->width }}x{{ $image->height }}</span>
                            </div>
                            @if($image->error_message)
                                <div class="text-xs text-red-500 dark:text-red-200">Error: {{ $image->error_message }}</div>
                            @endif
                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">Generated {{ $image->created_at->diffForHumans() }}</div>
                            <div class="mt-2">
                                <button class="regenerate-btn inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-id="{{ $image->id }}">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Regenerate
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $images->links() }}</div>
            @endif
        </div>
    </div>
</div>

<!-- Error Message Container -->
<div id="errorMessage" class="hidden mt-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4 text-red-700 dark:text-red-200"></div>

<!-- Loading Spinner -->
<div id="loadingSpinner" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        <p class="mt-2 text-gray-700 dark:text-gray-300">Regenerating image...</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const regenerateBtns = document.querySelectorAll('.regenerate-btn');
    const errorMessage = document.getElementById('errorMessage');
    const loadingSpinner = document.getElementById('loadingSpinner');

    regenerateBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const imageId = this.getAttribute('data-id');
            const imageCard = this.closest('.bg-white');
            
            // Reset error message
            errorMessage.classList.add('hidden');
            errorMessage.textContent = '';
            
            // Show loading spinner
            loadingSpinner.classList.remove('hidden');
            btn.disabled = true;
            
            // Send AJAX request
            fetch(`/generate/${imageId}/regenerate`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update image
                    const img = imageCard.querySelector('img');
                    img.src = data.image.url;
                    showToast('Image regenerated successfully!', 'green');
                } else {
                    // Show error message
                    errorMessage.classList.remove('hidden');
                    errorMessage.textContent = data.message;
                    
                    if (data.can_retry) {
                        showToast('Failed to regenerate image. Please try again.', 'red');
                    }
                }
            })
            .catch(error => {
                errorMessage.classList.remove('hidden');
                errorMessage.textContent = 'An error occurred. Please try again.';
                showToast('An error occurred. Please try again.', 'red');
            })
            .finally(() => {
                // Hide loading spinner
                loadingSpinner.classList.add('hidden');
                btn.disabled = false;
            });
        });
    });

    // Copy URL functionality
    document.querySelectorAll('.copy-image-url-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                showToast('URL copied to clipboard!', 'green');
            }).catch(() => {
                showToast('Failed to copy URL', 'red');
            });
        });
    });

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 ${
            type === 'green' ? 'bg-green-500' : 'bg-red-500'
        } text-white`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
});
</script>
@endsection 