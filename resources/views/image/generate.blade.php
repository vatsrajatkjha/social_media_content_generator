<!DOCTYPE html>
<html>

<head>
    <title>Generate Image with DALL·E</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">DALL·E Image Generator</h1>
        <form method="POST" action="{{ route('image.generate') }}">
            @csrf
            <input type="text" name="prompt" placeholder="Enter prompt..." class="w-full p-2 mb-4 border rounded"
                value="{{ old('prompt') }}" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Generate</button>
        </form>

        @if ($errors->any())
            <div class="text-red-500 mt-2">{{ $errors->first() }}</div>
        @endif

        @if (!empty($images))
            <div class="mt-6">
                <h2 class="text-xl font-semibold">Generated Image(s):</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    @foreach ($images as $image)
                        <div>
                            <img src="{{ $image['url'] }}" alt="Generated Image" class="rounded shadow-md">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</body>

</html>
