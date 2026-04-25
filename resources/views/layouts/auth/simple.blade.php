<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased dark:bg-slate-50 dark:text-slate-900">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        {{ $slot }}
    </main>

    @fluxScripts
</body>
</html>