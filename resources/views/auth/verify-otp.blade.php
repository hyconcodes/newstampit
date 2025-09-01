@php
    if ($user->email_verified_at) {
        header("Location: " . route('student.dashboard'));
        exit();
    }
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
    <div class="w-full max-w-md px-4 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex justify-center mb-8">
                <img src="{{ asset('assets/logo.png') }}" alt="App Logo" class="w-25">
            </div>
            <h1 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">Verify Your Account</h1>

            @if(session('error'))
            <div class="mb-4 p-4 text-red-700 bg-red-100 rounded-lg">
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('otp.check', $user->id) }}" class="flex flex-col items-center space-y-6">
                @csrf
                <div class="w-full">
                    <label for="otp" class="block text-center text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                        Enter 6-digit OTP
                    </label>
                    <div class="flex justify-center gap-2">
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" oninput="moveToNext(this, 1)" data-index="1">
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" oninput="moveToNext(this, 2)" data-index="2">
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" oninput="moveToNext(this, 3)" data-index="3">
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" oninput="moveToNext(this, 4)" data-index="4">
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" oninput="moveToNext(this, 5)" data-index="5">
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" oninput="moveToNext(this, 6)" data-index="6">
                        <input type="hidden" name="otp" id="otp">
                    </div>
                </div>
                <button type="submit" onclick="combineOTP(event)"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-md transition duration-200 text-lg">
                    Verify
                </button>
            </form>
        </div>
    </div>

    <script>
        function moveToNext(field, index) {
            field.value = field.value.replace(/[^0-9]/g, '');
            if (field.value && index < 6) {
                const nextField = document.querySelector(`input[data-index="${index + 1}"]`);
                if (nextField) nextField.focus();
            }
        }

        function combineOTP(event) {
            const inputs = document.querySelectorAll('input[data-index]');
            const otpValue = Array.from(inputs).map(input => input.value).join('');
            document.getElementById('otp').value = otpValue;
        }
    </script>
</body>

</html>
