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

<body class="min-h-screen flex items-center justify-center bg-zinc-50 dark:bg-zinc-900">
    <div class="w-full max-w-md px-4 py-8">
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
            <div class="flex justify-center mb-8">
                <img src="{{ asset('assets/logo.png') }}" alt="App Logo" class="w-25">
            </div>
            <h1 class="text-2xl font-bold text-center text-zinc-900 dark:text-white mb-6">Verify Your Account</h1>

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

            <form method="POST" action="{{ route('otp.check', $user->id) }}" class="flex flex-col items-center space-y-6" onsubmit="showLoader(event)">
                @csrf
                <div class="w-full">
                    <label for="otp" class="block text-center text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-3">
                        Enter 6-digit OTP
                    </label>
                    <div class="flex justify-center gap-2">
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" oninput="moveToNext(this, 1)" data-index="1" disabled>
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" oninput="moveToNext(this, 2)" data-index="2" disabled>
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" oninput="moveToNext(this, 3)" data-index="3" disabled>
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" oninput="moveToNext(this, 4)" data-index="4" disabled>
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" oninput="moveToNext(this, 5)" data-index="5" disabled>
                        <input type="text" maxlength="1" class="w-12 h-12 text-center text-xl border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" oninput="moveToNext(this, 6)" data-index="6" disabled>
                        <input type="hidden" name="otp" id="otp">
                    </div>
                    @if($errors->has('otp'))
                        <div class="mt-2 text-sm text-red-600 text-center">
                            {{ $errors->first('otp') }}
                        </div>
                    @endif
                </div>
                <button type="submit" id="verifyBtn" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-md transition duration-200 text-lg flex items-center justify-center" disabled>
                    <span id="verifyText">Verify</span>
                    <svg id="verifyLoader" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
            <form method="POST" action="{{ route('logout') }}" class="mt-2 w-full flex flex-col items-center">
                @csrf
                <button type="submit" class="w-full bg-red-200 hover:bg-red-300 text-red-800 font-medium py-2 px-4 rounded-md transition duration-200 text-base">
                    Log out
                </button>
            </form>
            <form method="POST" action="{{ route('otp.resend', $user->id) }}" class="mt-4 w-full flex flex-col items-center">
                @csrf
                <button type="submit" class="w-full bg-zinc-200 hover:bg-zinc-300 text-zinc-800 font-medium py-2 px-4 rounded-md transition duration-200 text-base">
                    Resend OTP
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

        function showLoader(event) {
            combineOTP(event);
            const verifyBtn = document.getElementById('verifyBtn');
            const verifyText = document.getElementById('verifyText');
            const verifyLoader = document.getElementById('verifyLoader');
            verifyText.textContent = 'Verifying...';
            verifyLoader.classList.remove('hidden');
            verifyBtn.disabled = true;
        }
    </script>
</body>

</html>
