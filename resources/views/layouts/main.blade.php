<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('pageTitle',config('app.name'))</title>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <header class="p-3 bg-dark text-white">

        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="#" class="nav-link px-2 text-secondary">Home</a></li>
                    <!-- <li><a href="#" class="nav-link px-2 text-white">Features</a></li> -->
                    <li><a href="#" class="nav-link px-2 text-white">FAQs</a></li>
                    @auth
                        @can('admingate')
                            <li><a href="{{ route('admin.index') }}" class="nav-link px-2 text-white">Users</a></li>
                        @endcan
                    @endauth
                </ul>
        
                <div class="text-end">
                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login')}}"><button type="button" class="btn btn-outline-light me-2">Login</button></a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register')}}"><button type="button" class="btn btn-warning">Sign-up</button></a>
                        @endif
                    @else
                        <div class="dropdown show">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->username }}
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('user.edit', Auth::user()->id ) }}">edit</a>
                                
                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')

    </main>
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <p>&copy; 2021 WIFI Finance, Inc. All rights reserved.</p>
        </div>
    </footer>

    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>