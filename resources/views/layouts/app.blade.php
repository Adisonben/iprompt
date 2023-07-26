<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/css/app.css' , 'resources/js/app.js'])

    <style>
        :root{
    --bs-dark: rgb(242, 255, 255);
  }
  
  .theme-container {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    position: fixed;
    bottom: 20px;
    right: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: 0.5s;
  }
  
  .theme-container:hover {
    opacity: 0.8;
  }
  
  @keyframes change {
    0% {
      transform: scale(1);
    }
  
    100% {
      transform: scale(1.4);
    }
  }
  
  .change {
    animation-name: change;
    animation-duration: 1s;
    animation-direction: alternate;
  }
  .bi-moon-stars{
    color: white;
  }
  .nav-light{
    background-color: white;
    color: black;
  }

  .nav-dark{
    background-color: black;
    color: white;
  }
  .nav-dark .navbarMenu{
    color: white;
  }
  .nav-dark .navbarMenu:hover{
    color: pink;
  }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md nav-light shadow-sm" id="navb">
            <div class="container">
                <a id="brand" class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item "><a href="{{ route('home') }}" class="nav-link navbarMenu">Home</a></li>
                        <li class="nav-item "><a href="#" class="nav-link navbarMenu">Imported</a></li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle navbarMenu" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Create
                            </a>
                        
                            <ul class="dropdown-menu dropdown-menu-end ">
                                <div class="d-flex flex-column justify-content-center">
                                    <li><a class="dropdown-item" href="{{ route('mouForm') }}">MOU</a></li>
                                    <li><a class="dropdown-item" href="{{ route('projForm') }}">โครงการ</a></li>
                                    <li><a class="dropdown-item" href="{{ route('annoForm') }}">ประกาศ</a></li>
                                    <li><a class="dropdown-item" href="{{ route('policyForm') }}">Policy</a></li>
                                    <li><a class="dropdown-item" href="{{ route('sopForm') }}">SOP</a></li>
                                    <li><a class="dropdown-item" href="{{ route('wiForm') }}">WI</a></li>
                                </div>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle navbarMenu" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Table
                            </a>
                        
                            <ul class="dropdown-menu dropdown-menu-end ">
                                <div class="d-flex flex-column justify-content-center">
                                    <li><a class="dropdown-item" href="{{ route('mouTable') }}">MOU</a></li>
                                    <li><a class="dropdown-item" href="{{ route('projTable') }}">โครงการ</a></li>
                                    <li><a class="dropdown-item" href="{{ route('annoTable') }}">ประกาศ</a></li>
                                    <li><a class="dropdown-item" href="{{ route('policyTable') }}">Policy</a></li>
                                    <li><a class="dropdown-item" href="{{ route('sopTable') }}">SOP</a></li>
                                    <li><a class="dropdown-item" href="{{ route('wiTable') }}">WI</a></li>
                                </div>
                            </ul>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            <!-- @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif -->
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle navbarMenu" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                            
                                <div class="dropdown-menu dropdown-menu-end " aria-labelledby="navbarDropdown">
                                    <div class="d-flex justify-content-center">
                                        <a class="dropdown-item text-center" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
        <h5 class="fixed-bottom m-5">
            <div class="theme-container shadow-light">
                <i class="bi bi-sun" id="theme-icon"></i>
            </div>
        </h5>
    </div>
</body>

<script>
    document.body.style="background-color: var(--bs-dark);transition: 0.5s;"
const sun = "bi-sun";
const moon = "bi-moon-stars"

var theme = "light";
const root = document.querySelector(":root");
const container = document.getElementsByClassName("theme-container")[0];
const themeIcon = document.getElementById("theme-icon");
const navb = document.getElementById("navb");
container.addEventListener("click", setTheme);

function setTheme() {
    switch (theme) {
        case "dark":
            setLight();
            theme = "light";
            break;
        case "light":
            setDark();
            theme = "dark";
            break;
    }
}

function setLight() {
    root.style.setProperty(
        "--bs-dark",
        "rgb(242, 255, 255)"
    );
    container.classList.remove("shadow-dark");
    themeIcon.classList.remove(moon);
    navb.classList.remove("nav-dark");
    setTimeout(() => {
        container.classList.add("shadow-light");
        themeIcon.classList.remove("change");
    }, 300);
    themeIcon.classList.add("change");
    navb.classList.add("nav-light");
    themeIcon.classList.add(sun);
}

function setDark() {
    root.style.setProperty("--bs-dark", "#212529");
    container.classList.remove("shadow-light");
    themeIcon.classList.remove(sun);
    navb.classList.remove("nav-light");
    setTimeout(() => {
        container.classList.add("shadow-dark");
        themeIcon.classList.remove("change");
    }, 300);
    themeIcon.classList.add("change");
    navb.classList.add("nav-dark");
    themeIcon.classList.add(moon);
}
</script>
</html>
