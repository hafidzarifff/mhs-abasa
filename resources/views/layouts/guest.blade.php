<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased" style="background: linear-gradient(145deg, #e8e9f3 0%, #ecedf8 40%, #e4e5f0 100%);">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8">

            <div class="w-full" style="max-width: 420px;">
                <!-- Card -->
                <div style="
                    background: #ffffff;
                    border-radius: 28px;
                    padding: 40px 36px 36px 36px;
                    box-shadow: 0 8px 40px rgba(100, 100, 160, 0.13), 0 1.5px 6px rgba(0,0,0,0.04);
                ">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>