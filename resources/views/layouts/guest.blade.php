<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Ingresar · URUS')</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-gray-100 text-gray-800">
  <main class="min-h-full flex items-center justify-center p-6">
    <div class="w-full max-w-md">
      @yield('content')
    </div>
  </main>
</body>
</html>
