<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance

    <style>
        /* ===== BACKGROUND ===== */
        body {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        }

        /* ===== CARD ===== */
        .auth-card {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        /* ===== INPUT FIX (สำคัญมาก) ===== */
        input, textarea, select {
            color: #0f172a !important;
            background-color: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            padding: 12px 14px !important;
            transition: 0.2s;
        }

        input::placeholder {
            color: #94a3b8 !important;
        }

        input:focus {
            background-color: #ffffff !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2) !important;
        }

        /* ===== flux override ===== */
        .flux-input input {
            color: #0f172a !important;
            background-color: #f8fafc !important;
        }

        /* ===== BUTTON ===== */
        .btn-login {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* ===== LOGO BOX ===== */
        .logo-box {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            /* background: linear-gradient(135deg, #3b82f6, #6366f1); */
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 10px 20px rgba(59,130,246,0.25);
        }

        .logo-box img {
            width: 50px;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-sm auth-card">
        {{ $slot }}
    </div>

    @fluxScripts
</body>
</html>