<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center">
        <a href="#" class="font-bold text-xl">SkillHub</a>
        <div>
            @auth
                <span class="mr-4">Halo, {{ Auth::user()->username }} ({{ ucfirst(Auth::user()->role) }})</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600 text-sm">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:underline">Login</a>
            @endauth
        </div>
    </nav>

    <div class="container mx-auto p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

</body>
</html>
