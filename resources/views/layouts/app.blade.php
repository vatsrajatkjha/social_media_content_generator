<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Social Media Content Generator') }}</title>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 min-h-screen font-sans">
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 shadow-md animate-fade-in">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 15s1.5 2 4 2 4-2 4-2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h.01M15 9h.01" />
                        </svg>
                    </span>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <a href="{{ route('contents.index') }}" class="hover:text-indigo-600 transition-colors">
                            Social Media Content Generator
                        </a>
                    </h1>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('contents.index') }}" 
                       class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                        Home
                    </a>
                    <a href="{{ route('contents.create') }}" 
                       class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                        Generate Content
                    </a>
                    <a href="{{ route('stable-diffusion.index') }}" 
                       class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                        Generate Image
                    </a>
                    <a href="{{ route('generated-images.index') }}" 
                       class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                        All Generated Images
                    </a>
                </nav>
                <div class="flex items-center space-x-2 md:space-x-0">
                    <div class="md:hidden">
                        <button type="button" class="mobile-menu-button p-2 rounded-md text-gray-600 hover:text-indigo-600 hover:bg-gray-100">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile nav -->
            <div class="mobile-menu hidden md:hidden bg-white border-t border-gray-100">
                <nav class="flex flex-col space-y-2 py-4">
                    <a href="{{ route('contents.index') }}" class="px-4 py-2 text-gray-600 hover:text-indigo-600 transition-colors font-medium">Home</a>
                    <a href="{{ route('contents.create') }}" class="px-4 py-2 text-gray-600 hover:text-indigo-600 transition-colors font-medium">Generate Content</a>
                    <a href="{{ route('stable-diffusion.index') }}" class="px-4 py-2 text-gray-600 hover:text-indigo-600 transition-colors font-medium">Generate Image</a>
                    <a href="{{ route('generated-images.index') }}" class="px-4 py-2 text-gray-600 hover:text-indigo-600 transition-colors font-medium">All Generated Images</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8 py-8">
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gradient-to-r from-indigo-50 via-purple-100 to-indigo-50 border-t border-gray-200 mt-12 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-500">
                <p class="text-lg font-semibold bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent mb-2">Create. Inspire. Share.</p>
                <p class="text-sm">&copy; {{ date('Y') }} Social App. Powered by Const-Ant.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
    <style>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.7s cubic-bezier(.4,0,.2,1) both; }
</style>
</body>

</html>
