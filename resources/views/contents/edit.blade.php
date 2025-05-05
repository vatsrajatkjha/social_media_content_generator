@extends('layouts.app')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Content</h2>

        <form action="{{ route('contents.update', $content->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="platform" class="block text-gray-700 text-sm font-bold mb-2">
                    Platform
                </label>
                <select name="platform" id="platform"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @foreach ($platforms as $value => $label)
                        <option value="{{ $value }}" {{ $content->platform == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('platform')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="prompt" class="block text-gray-700 text-sm font-bold mb-2">
                    Original Prompt
                </label>
                <div class="bg-gray-100 p-3 rounded">
                    {{ $content->prompt }}
                </div>
            </div>

            <div class="mb-6">
                <label for="content" class="block text-gray-700 text-sm font-bold mb-2">
                    Content
                </label>
                <textarea name="content" id="content" rows="6"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('content', $content->content) }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Content
                </button>
                <a href="{{ route('contents.show', $content->id) }}"
                    class="inline-block align-baseline font-bold text-sm text-indigo-600 hover:text-indigo-900">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
