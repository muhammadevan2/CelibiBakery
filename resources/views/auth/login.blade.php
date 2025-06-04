<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Kasir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- TailwindCSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex justify-center items-center min-h-screen bg-gradient-to-br from-green-100 to-white px-4">

    <form method="POST" action="/login" class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Login Kasir</h2>
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-green-400" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" id="password" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-green-400" required>
        </div>

        @if ($errors->any())
            <div class="text-red-600 text-sm mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <button type="submit" class="w-full bg-green-500 text-white font-semibold py-3 rounded hover:bg-green-600 transition duration-300">
            Login
        </button>
    </form>
</body>
</html>
