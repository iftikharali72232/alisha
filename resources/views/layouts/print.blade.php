<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                font-size: 12px;
                line-height: 1.4;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-white text-black">
    @yield('content')
</body>
</html>