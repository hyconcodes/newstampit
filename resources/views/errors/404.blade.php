<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-500 dark:bg-gray-900 text-green-900 dark:text-white min-h-screen m-0 flex items-center justify-center flex-col font-sans">
    <img src="{{ asset('assets/logo.png') }}" alt="University Logo" class="w-32 md:w-48 mb-8">
    <h1 class="text-5xl md:text-8xl mb-0 text-red-500 dark:text-red-400 font-bold">404</h1>
    <p class="text-lg md:text-xl mt-0 font-bold text-center px-4">Sorry, the page you are looking for could not be found.</p>
    <a href="{{ url()->previous() }}" class="mt-5 inline-flex items-center text-blue-600 dark:text-blue-400 font-bold hover:underline">
        <i class="ri-arrow-left-line mr-2"></i>
        Go back.....
    </a>
</body>
</html>
