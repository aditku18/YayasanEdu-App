<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - 403</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-9xl font-bold text-gray-300">403</h1>
            <p class="text-2xl font-semibold text-gray-700 mt-4">Akses Ditolak</p>
            <p class="text-gray-500 mt-2">@isset($message){{ $message }}@elseAnda tidak memiliki akses ke halaman ini.@endif</p>
            <a href="{{ url('/') }}" class="inline-block mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
