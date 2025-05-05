@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8 py-8 dark:bg-gray-900 dark:text-gray-100">
        <div class="bg-white dark:bg-gray-950 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-900 dark:to-gray-800">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">AI Image Generation</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Create stunning images using Stable Diffusion</p>
            </div>

            <form action="{{ route('stable-diffusion.generate') }}" method="POST" class="p-6" enctype="multipart/form-data" id="imageForm">
                @csrf

                <div class="space-y-6">
                    <!-- Image Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Image Type
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <input type="radio" id="image_type_product" name="image_type" value="product"
                                    class="sr-only peer" {{ old('image_type') == 'product' ? 'checked' : '' }}>
                                <label for="image_type_product"
                                    class="flex flex-col items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-6 h-6 mb-1 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Product
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="image_type_lifestyle" name="image_type" value="lifestyle"
                                    class="sr-only peer" {{ old('image_type') == 'lifestyle' ? 'checked' : '' }}>
                                <label for="image_type_lifestyle"
                                    class="flex flex-col items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-6 h-6 mb-1 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Lifestyle
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="image_type_abstract" name="image_type" value="abstract"
                                    class="sr-only peer" {{ old('image_type') == 'abstract' ? 'checked' : '' }}>
                                <label for="image_type_abstract"
                                    class="flex flex-col items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-6 h-6 mb-1 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Abstract
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="image_type_portrait" name="image_type" value="portrait"
                                    class="sr-only peer" {{ old('image_type') == 'portrait' ? 'checked' : '' }}>
                                <label for="image_type_portrait"
                                    class="flex flex-col items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-6 h-6 mb-1 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Portrait
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Style Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Style
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <input type="radio" id="style_realistic" name="style" value="realistic"
                                    class="sr-only peer" {{ old('style') == 'realistic' ? 'checked' : '' }}>
                                <label for="style_realistic"
                                    class="flex flex-col items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-6 h-6 mb-1 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Realistic
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="style_artistic" name="style" value="artistic"
                                    class="sr-only peer" {{ old('style') == 'artistic' ? 'checked' : '' }}>
                                <label for="style_artistic"
                                    class="flex flex-col items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-6 h-6 mb-1 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                    Artistic
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="style_minimal" name="style" value="minimal"
                                    class="sr-only peer" {{ old('style') == 'minimal' ? 'checked' : '' }}>
                                <label for="style_minimal"
                                    class="flex flex-col items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-6 h-6 mb-1 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Minimal
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="style_fantasy" name="style" value="fantasy"
                                    class="sr-only peer" {{ old('style') == 'fantasy' ? 'checked' : '' }}>
                                <label for="style_fantasy"
                                    class="flex flex-col items-center p-3 text-sm border rounded-lg cursor-pointer border-gray-300 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-50 transition-colors">
                                    <svg class="w-6 h-6 mb-1 text-gray-600 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    Fantasy
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Prompt Templates -->
                    <div id="promptTemplates" class="space-y-3">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Suggested Prompts</h3>
                            <div class="space-y-2">
                                <button type="button" onclick="useTemplate('product')" class="text-left w-full p-2 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                    📦 Product showcase with clean background
                                </button>
                                <button type="button" onclick="useTemplate('lifestyle')" class="text-left w-full p-2 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                    🌟 Lifestyle scene with people enjoying [product/service]
                                </button>
                                <button type="button" onclick="useTemplate('abstract')" class="text-left w-full p-2 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                    🎨 Abstract representation of [concept/idea]
                                </button>
                                <button type="button" onclick="useTemplate('portrait')" class="text-left w-full p-2 text-sm text-gray-600 hover:bg-gray-100 rounded">
                                    👤 Professional portrait in [setting/background]
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Prompt Input -->
                    <div>
                        <label for="prompt" class="block text-sm font-medium text-gray-700 mb-2">
                            Describe your image
                        </label>
                        <div class="relative">
                            <textarea name="prompt" id="prompt" rows="4"
                                class="block w-full px-4 py-3 text-gray-900 placeholder-gray-400 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="E.g., 'A modern smartphone on a white background with soft lighting'">{{ old('prompt') }}</textarea>
                            <div class="absolute bottom-3 right-3">
                                <span class="text-xs text-gray-500" id="charCount">0/500</span>
                            </div>
                        </div>
                        @error('prompt')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Negative Prompt -->
                    <div>
                        <label for="negative_prompt" class="block text-sm font-medium text-gray-700 mb-2">
                            What to avoid in the image (optional)
                        </label>
                        <textarea name="negative_prompt" id="negative_prompt" rows="2"
                            class="block w-full px-4 py-3 text-gray-900 placeholder-gray-400 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="E.g., 'blurry, low quality, text, watermark'">{{ old('negative_prompt') }}</textarea>
                    </div>

                    <!-- Image Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="width" class="block text-sm font-medium text-gray-700 mb-2">
                                Width (px)
                            </label>
                            <input type="number" name="width" id="width" value="{{ old('width', 512) }}"
                                class="block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-2">
                                Height (px)
                            </label>
                            <input type="number" name="height" id="height" value="{{ old('height', 512) }}"
                                class="block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="num_images" class="block text-sm font-medium text-gray-700 mb-2">
                                Number of Images
                            </label>
                            <input type="number" name="num_images" id="num_images" value="{{ old('num_images', 1) }}" min="1" max="4"
                                class="block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <button type="submit" id="generateBtn"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Generate Image
                        </button>
                    </div>
                </div>

                <!-- Error Message Container -->
                <div id="errorMessage" class="hidden mt-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4 text-red-700 dark:text-red-200"></div>
            </form>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                    <p class="mt-2 text-gray-700 dark:text-gray-300">Generating images...</p>
                </div>
            </div>

            <!-- Generated Images Container -->
            <div id="generatedImages" class="mt-8 space-y-6"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('imageForm');
            const generateBtn = document.getElementById('generateBtn');
            const errorMessage = document.getElementById('errorMessage');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const generatedImages = document.getElementById('generatedImages');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Reset error message and generated images
                errorMessage.classList.add('hidden');
                errorMessage.textContent = '';
                generatedImages.innerHTML = '';
                
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
                        // Display generated images
                        const imagesHtml = `
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Generated Images</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    ${data.images.map(image => `
                                        <div class="relative group">
                                            <img src="${image.url}" alt="Generated image" class="w-full h-auto rounded-lg shadow-sm">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                                <button class="text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors copy-image-url-btn" data-url="${image.url}">
                                                    Copy URL
                                                </button>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                        generatedImages.innerHTML = imagesHtml;

                        // Attach copy listeners
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

            function showToast(message, type) {
                const toast = document.createElement('div');
                toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 ${
                    type === 'green' ? 'bg-green-500' : 'bg-red-500'
                } text-white`;
                toast.textContent = message;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }

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

            // Update prompt templates based on image type and style
            const imageTypes = document.querySelectorAll('input[name="image_type"]');
            const styles = document.querySelectorAll('input[name="style"]');

            imageTypes.forEach(type => {
                type.addEventListener('change', function() {
                    updatePromptTemplates(this.value, getSelectedStyle());
                });
            });

            styles.forEach(style => {
                style.addEventListener('change', function() {
                    updatePromptTemplates(getSelectedImageType(), this.value);
                });
            });
        });

        function getSelectedImageType() {
            return document.querySelector('input[name="image_type"]:checked')?.value || 'product';
        }

        function getSelectedStyle() {
            return document.querySelector('input[name="style"]:checked')?.value || 'realistic';
        }

        function updatePromptTemplates(type, style) {
            const templates = {
                product: {
                    realistic: [
                        "📦 Professional product photo of [product] on white background, studio lighting, 8k, highly detailed",
                        "📱 [Product] in natural setting, soft lighting, professional photography, 8k",
                        "🎯 Close-up shot of [product] with dramatic lighting, product photography, 8k"
                    ],
                    artistic: [
                        "🎨 Artistic product shot of [product], creative lighting, unique composition, 8k",
                        "🖼️ [Product] in surreal setting, artistic composition, dramatic lighting, 8k",
                        "✨ Creative product photography of [product], unique perspective, artistic lighting, 8k"
                    ],
                    minimal: [
                        "⚪ Minimal product shot of [product], clean background, simple lighting, 8k",
                        "⬜ [Product] on white background, minimal composition, soft lighting, 8k",
                        "🔲 Clean product photography of [product], minimal design, subtle shadows, 8k"
                    ],
                    fantasy: [
                        "🌟 Magical product shot of [product], glowing effects, fantasy lighting, 8k",
                        "✨ [Product] in fantasy setting, ethereal lighting, magical atmosphere, 8k",
                        "🎆 Fantasy product photography of [product], mystical effects, dreamy lighting, 8k"
                    ]
                },
                lifestyle: {
                    realistic: [
                        "👥 People enjoying [product/service] in natural setting, lifestyle photography, 8k",
                        "🏡 [Product/service] in everyday life, candid moment, natural lighting, 8k",
                        "🌅 Lifestyle shot of people using [product/service], golden hour, 8k"
                    ],
                    artistic: [
                        "🎨 Artistic lifestyle scene with [product/service], creative composition, 8k",
                        "🖼️ People interacting with [product/service] in artistic setting, 8k",
                        "✨ Creative lifestyle photography featuring [product/service], unique perspective, 8k"
                    ],
                    minimal: [
                        "⚪ Minimal lifestyle shot with [product/service], clean composition, 8k",
                        "⬜ People using [product/service] in minimal setting, soft lighting, 8k",
                        "🔲 Clean lifestyle photography with [product/service], simple composition, 8k"
                    ],
                    fantasy: [
                        "🌟 Magical lifestyle scene with [product/service], fantasy elements, 8k",
                        "✨ People enjoying [product/service] in dreamy setting, ethereal lighting, 8k",
                        "🎆 Fantasy lifestyle photography featuring [product/service], mystical atmosphere, 8k"
                    ]
                },
                abstract: {
                    realistic: [
                        "🎨 Abstract representation of [concept], realistic textures, 8k",
                        "🖼️ [Concept] in abstract form, detailed textures, natural lighting, 8k",
                        "✨ Abstract art of [concept], realistic elements, high detail, 8k"
                    ],
                    artistic: [
                        "🎨 Artistic abstract representation of [concept], creative composition, 8k",
                        "🖼️ [Concept] in artistic abstract form, unique style, 8k",
                        "✨ Creative abstract art of [concept], innovative design, 8k"
                    ],
                    minimal: [
                        "⚪ Minimal abstract representation of [concept], clean design, 8k",
                        "⬜ [Concept] in minimal abstract form, simple composition, 8k",
                        "🔲 Clean abstract art of [concept], subtle elements, 8k"
                    ],
                    fantasy: [
                        "🌟 Magical abstract representation of [concept], fantasy elements, 8k",
                        "✨ [Concept] in fantasy abstract form, ethereal lighting, 8k",
                        "🎆 Fantasy abstract art of [concept], mystical atmosphere, 8k"
                    ]
                },
                portrait: {
                    realistic: [
                        "👤 Professional portrait in [setting], natural lighting, 8k",
                        "📸 Portrait of person in [setting], studio lighting, 8k",
                        "🎯 Close-up portrait in [setting], dramatic lighting, 8k"
                    ],
                    artistic: [
                        "🎨 Artistic portrait in [setting], creative lighting, 8k",
                        "🖼️ Portrait with artistic composition in [setting], 8k",
                        "✨ Creative portrait photography in [setting], unique style, 8k"
                    ],
                    minimal: [
                        "⚪ Minimal portrait in [setting], clean background, 8k",
                        "⬜ Portrait with minimal composition in [setting], 8k",
                        "🔲 Clean portrait photography in [setting], simple lighting, 8k"
                    ],
                    fantasy: [
                        "🌟 Magical portrait in [setting], fantasy elements, 8k",
                        "✨ Portrait with ethereal lighting in [setting], 8k",
                        "🎆 Fantasy portrait photography in [setting], mystical atmosphere, 8k"
                    ]
                }
            };

            const templateContainer = document.getElementById('promptTemplates');
            templateContainer.innerHTML = `
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Suggested Prompts</h3>
                    <div class="space-y-2">
                        ${templates[type][style].map(template => `
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
