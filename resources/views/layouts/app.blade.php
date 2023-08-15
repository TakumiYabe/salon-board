<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>サロンボード</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    @viteReactRefresh
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const csrfToken = "{{ csrf_token() }}";
    </script>

</head>
<body>
<main>
    @include('layouts.sidebar')
    @yield('content')

</main>
</body>
</html>
