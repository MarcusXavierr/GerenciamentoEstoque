<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Gerenciamento de produtos</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    @notifyCss
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="data:image/png;base64,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" width="100" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                        <li class="nav-item">
                            <a href="{{route('product.index')}}" class="nav-link">Produtos</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('stock.index')}}" class="nav-link">Controle de estoque</a>
                        </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <x:notify-messages />
            @yield('content')
        </main>
    </div>
    @notifyJs
    @yield('scripts')
</body>
</html>
