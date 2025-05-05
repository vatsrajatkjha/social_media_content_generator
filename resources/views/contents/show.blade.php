{{-- @extends('layouts.app')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Generated Content</h2>
            <a href="{{ route('contents.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Generate New Content
            </a>
        </div>

        <div class="mb-6">
            <span
                class="text-sm font-semibold inline-block py-1 px-2 uppercase rounded-full 
                  {{ $content->platform === 'twitter'
                      ? 'bg-blue-200 text-blue-800'
                      : ($content->platform === 'linkedin'
                          ? 'bg-blue-900 text-white'
                          : ($content->platform === 'instagram'
                              ? 'bg-pink-200 text-pink-800'
                              : 'bg-blue-500 text-white')) }}">
                {{ ucfirst($content->platform) }}
            </span>
            <span class="ml-2 text-gray-500 text-sm">Generated {{ $content->created_at->diffForHumans() }}</span>
        </div>

        <div class="border rounded-lg p-6 mb-6 bg-gray-50">
            <h3 class="font-bold text-gray-700 mb-2">Original Prompt:</h3>
            <p class="text-gray-600">{{ $content->prompt }}</p>
        </div>

        <div class="border rounded-lg p-6 mb-6">
            <h3 class="font-bold text-gray-700 mb-2">Generated Content:</h3>
            <div class="whitespace-pre-line text-gray-800">{{ $content->content }}</div>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('contents.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Back to All Content
            </a>
            <button onclick="copyToClipboard()"
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Copy to Clipboard
            </button>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const text = `{{ addslashes($content->content) }}`;
            navigator.clipboard.writeText(text).then(function() {
                alert('Content copied to clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
@endsection --}}



@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-900 dark:to-gray-800">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Generated Content</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Platform: {{ ucfirst($content->platform) }}</p>
                    </div>
                    <div class="flex space-x-4">
                        <button id="regenerateBtn" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Regenerate
                        </button>
                        <a href="{{ route('contents.edit', $content->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Prompt</h3>
                    <p class="text-gray-600 dark:text-gray-300">{{ $content->prompt }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Generated Content</h3>
                    <div id="contentDisplay" class="prose dark:prose-invert max-w-none">
                        {!! nl2br(e($content->content)) !!}
                    </div>
                </div>

                <!-- Error Message Container -->
                <div id="errorMessage" class="hidden mt-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4 text-red-700 dark:text-red-200"></div>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 flex items-center justify-center z-50">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                        <p class="mt-2 text-gray-700 dark:text-gray-300">Regenerating content...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const regenerateBtn = document.getElementById('regenerateBtn');
        const errorMessage = document.getElementById('errorMessage');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const contentDisplay = document.getElementById('contentDisplay');

        regenerateBtn.addEventListener('click', function() {
            // Reset error message
            errorMessage.classList.add('hidden');
            errorMessage.textContent = '';
            
            // Show loading spinner
            loadingSpinner.classList.remove('hidden');
            regenerateBtn.disabled = true;
            
            // Send AJAX request
            fetch('{{ route("contents.regenerate", $content->id) }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update content display
                    contentDisplay.innerHTML = data.content.content.replace(/\n/g, '<br>');
                    showToast('Content regenerated successfully!', 'green');
                } else {
                    // Show error message
                    errorMessage.classList.remove('hidden');
                    errorMessage.textContent = data.message;
                    
                    if (data.can_retry) {
                        showToast('Failed to regenerate content. Please try again.', 'red');
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
                regenerateBtn.disabled = false;
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
