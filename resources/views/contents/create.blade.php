@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h2 class="text-2xl font-bold text-gray-900">Generate New Social Media Content</h2>
                <p class="mt-1 text-sm text-gray-600">Create engaging content for your social media platforms using AI</p>
            </div>

            <form action="{{ route('contents.store') }}" method="POST" class="p-6" id="contentForm">
                @csrf

                <div class="space-y-6">
                    <!-- Platform Selection -->
                    <div>
                        <label for="platform" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Platform
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ($platforms as $value => $label)
                                <div>
                                    <input type="radio" id="platform_{{ $value }}" name="platform" value="{{ $value }}"
                                        class="sr-only peer" {{ old('platform') == $value ? 'checked' : '' }}>
                                    <label for="platform_{{ $value }}"
                                        class="flex flex-col items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                        <svg class="w-6 h-6 mb-1 text-gray-600 peer-checked:text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                            @if($value === 'twitter')
                                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                            @elseif($value === 'linkedin')
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            @elseif($value === 'instagram')
                                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                            @else
                                                <path d="M22.675 0h-21.35C.765 0 0 .765 0 1.725v20.55C0 23.235.765 24 1.725 24h21.35c.96 0 1.725-.765 1.725-1.725V1.725C24.4.765 23.635 0 22.675 0zm-4.5 7.5c0-1.5-1.2-2.7-2.7-2.7s-2.7 1.2-2.7 2.7 1.2 2.7 2.7 2.7 2.7-1.2 2.7-2.7zm-9 0c0-1.5-1.2-2.7-2.7-2.7s-2.7 1.2-2.7 2.7 1.2 2.7 2.7 2.7 2.7-1.2 2.7-2.7zm4.5 9c0 1.5-1.2 2.7-2.7 2.7s-2.7-1.2-2.7-2.7v-4.5c0-1.5 1.2-2.7 2.7-2.7s2.7 1.2 2.7 2.7v4.5z"/>
                                            @endif
                                        </svg>
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('platform')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Content Type
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <input type="radio" id="content_type_announcement" name="content_type" value="announcement"
                                    class="sr-only peer" {{ old('content_type') == 'announcement' ? 'checked' : '' }}>
                                <label for="content_type_announcement"
                                    class="flex items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                    Announcement
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="content_type_educational" name="content_type" value="educational"
                                    class="sr-only peer" {{ old('content_type') == 'educational' ? 'checked' : '' }}>
                                <label for="content_type_educational"
                                    class="flex items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Educational
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="content_type_promotional" name="content_type" value="promotional"
                                    class="sr-only peer" {{ old('content_type') == 'promotional' ? 'checked' : '' }}>
                                <label for="content_type_promotional"
                                    class="flex items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    Promotional
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="content_type_engagement" name="content_type" value="engagement"
                                    class="sr-only peer" {{ old('content_type') == 'engagement' ? 'checked' : '' }}>
                                <label for="content_type_engagement"
                                    class="flex items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                    </svg>
                                    Engagement
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Prompt Templates -->
                    <div id="promptTemplates" class="space-y-3">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Suggested Prompts</h3>
                            <div class="space-y-2">
                                <button type="button" onclick="useTemplate('announcement')" class="text-left w-full p-2 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                    🎉 Announce a new product or feature
                                </button>
                                <button type="button" onclick="useTemplate('educational')" class="text-left w-full p-2 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                    📚 Share industry insights or tips
                                </button>
                                <button type="button" onclick="useTemplate('promotional')" class="text-left w-full p-2 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                    💰 Promote a special offer or discount
                                </button>
                                <button type="button" onclick="useTemplate('engagement')" class="text-left w-full p-2 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                    💬 Ask an engaging question to your audience
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Prompt Input -->
                    <div>
                        <label for="prompt" class="block text-sm font-medium text-gray-700 mb-2">
                            What would you like to post about?
                        </label>
                        <div class="relative">
                            <textarea name="prompt" id="prompt" rows="4"
                                class="block w-full px-4 py-3 text-gray-900 placeholder-gray-400 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., 'Announce our new product launch', 'Share tips about productivity', etc.">{{ old('prompt') }}</textarea>
                            <div class="absolute bottom-3 right-3">
                                <span class="text-xs text-gray-500" id="charCount">0/500</span>
                            </div>
                        </div>
                        @error('prompt')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <a href="{{ route('contents.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" id="generateBtn"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Generate Content
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Error Message Container -->
    <div id="errorMessage" class="hidden mt-4 bg-red-50 border border-red-200 rounded-lg p-4 text-red-700"></div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
            <p class="mt-2 text-gray-700">Generating content...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('contentForm');
            const generateBtn = document.getElementById('generateBtn');
            const errorMessage = document.getElementById('errorMessage');
            const loadingSpinner = document.getElementById('loadingSpinner');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Reset error message
                errorMessage.classList.add('hidden');
                errorMessage.textContent = '';
                
                // Show loading spinner
                loadingSpinner.classList.remove('hidden');
                generateBtn.disabled = true;
                
                // Get form data
                const formData = new FormData(form);
                
                // Send AJAX request
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to show page
                        window.location.href = data.redirect_url;
                    } else {
                        // Show error message
                        errorMessage.classList.remove('hidden');
                        errorMessage.textContent = data.message;
                        
                        // If there are validation errors, display them
                        if (data.errors) {
                            const errorList = Object.values(data.errors).flat().join('\n');
                            errorMessage.textContent = errorList;
                        }
                    }
                })
                .catch(error => {
                    errorMessage.classList.remove('hidden');
                    errorMessage.textContent = 'An error occurred. Please try again.';
                })
                .finally(() => {
                    // Hide loading spinner
                    loadingSpinner.classList.add('hidden');
                    generateBtn.disabled = false;
                });
            });

            // Character count functionality
            const promptTextarea = document.getElementById('prompt');
            const charCount = document.getElementById('charCount');

            promptTextarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = `${count}/500`;
                if (count > 500) {
                    charCount.classList.add('text-red-500');
                } else {
                    charCount.classList.remove('text-red-500');
                }
            });

            // Update prompt templates based on content type
            const contentTypes = document.querySelectorAll('input[name="content_type"]');
            contentTypes.forEach(type => {
                type.addEventListener('change', function() {
                    updatePromptTemplates(this.value);
                });
            });
        });

        function updatePromptTemplates(type) {
            const templates = {
                announcement: [
                    "🎉 We're excited to announce our new [product/feature]! [Brief description]. Learn more: [link]",
                    "🚀 Big news! Introducing [product/feature] - [value proposition]. Available now!",
                    "📢 Announcing [product/feature] - the solution to [problem]. Try it today!"
                ],
                educational: [
                    "📚 Did you know? [Interesting fact/statistic] about [topic]. Here's why it matters:",
                    "💡 Pro tip: [Actionable advice] to improve your [skill/process]",
                    "🔍 Deep dive: Understanding [topic] and its impact on [industry/field]"
                ],
                promotional: [
                    "💰 Special offer: [Discount/Deal] on [product/service] for a limited time!",
                    "🎁 Exclusive deal: Get [benefit] when you [action] by [date]",
                    "🌟 Limited time: [Special offer] on [product/service] - don't miss out!"
                ],
                engagement: [
                    "💬 What's your biggest challenge with [topic]? Share in the comments!",
                    "🤔 Quick poll: Which [options] do you prefer? Comment below!",
                    "🎯 What's your top tip for [topic]? Let's share knowledge!"
                ]
            };

            const templateContainer = document.getElementById('promptTemplates');
            templateContainer.innerHTML = `
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Suggested Prompts</h3>
                    <div class="space-y-2">
                        ${templates[type].map(template => `
                            <button type="button" onclick="useTemplate('${template}')" 
                                class="text-left w-full p-2 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                ${template}
                            </button>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        function useTemplate(template) {
            document.getElementById('prompt').value = template;
            document.getElementById('prompt').dispatchEvent(new Event('input'));
        }
    </script>
@endsection
