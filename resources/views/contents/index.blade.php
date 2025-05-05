@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-950 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-indigo-400/10 via-purple-400/10 to-indigo-100/10 dark:from-indigo-900/40 dark:via-purple-900/40 dark:to-indigo-900/10">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Your Content Library</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Manage and view all your generated social media content</p>
                    </div>
                    <a href="{{ route('contents.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Generate New Content
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                @if($contents->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No content yet</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by generating your first social media post.</p>
                        <div class="mt-6">
                            <a href="{{ route('contents.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create your first post
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($contents as $content)
                            <div class="bg-white dark:bg-gray-900 border-2 border-transparent hover:border-indigo-400 hover:shadow-xl dark:hover:border-purple-500 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between animate-fade-in">
                                <div class="p-6 flex-1 flex flex-col">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            {{ $content->platform === 'twitter'
                                                ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                                : ($content->platform === 'linkedin'
                                                    ? 'bg-blue-900 text-white dark:bg-blue-700 dark:text-blue-100'
                                                    : ($content->platform === 'instagram'
                                                        ? 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200'
                                                        : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200')) }}">
                                            {{ ucfirst($content->platform) }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $content->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="mb-4 flex-1">
                                        <p class="text-gray-900 dark:text-gray-100 line-clamp-3">{{ $content->content }}</p>
                                    </div>
                                    <div class="flex items-center justify-between mt-auto">
                                        <a href="{{ route('contents.show', $content->id) }}"
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium transition">View Details →</a>
                                        <button class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 copy-btn transition" data-content="{{ e($content->content) }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                            Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $contents->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.copy-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const text = btn.getAttribute('data-content');
                navigator.clipboard.writeText(text).then(function() {
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                    toast.textContent = 'Content copied to clipboard!';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 2000);
                }).catch(function(err) {
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                    toast.textContent = 'Failed to copy content';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 2000);
                });
            });
        });
    });
    </script>

    <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.7s cubic-bezier(.4,0,.2,1) both; }
    </style>
@endsection
