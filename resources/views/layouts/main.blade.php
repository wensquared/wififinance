<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('pageTitle',config('app.name'))</title>
    <link rel="stylesheet" href="/css/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/toastr.min.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <header class="p-3 bg-dark text-white">

        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    @guest
                        <li><a href="/" class="nav-link px-2 text-white">Home</a></li>
                    @else
                        <li><a href="{{route('portfolio.index')}}" class="nav-link px-2 {{ Str::startsWith(Route::currentRouteName(), 'portfolio') ? 'text-white' : 'text-secondary' }}">Home</a></li>
                    @endguest
                    <li><a href="#" class="nav-link px-2 {{ Str::startsWith(Route::currentRouteName(), 'portfolio') ? 'text-white' : 'text-secondary' }}">FAQs</a></li>
                    @auth
                        @can('admingate')
                            <li><a href="{{ route('admin.index') }}" class="nav-link px-2  {{ Str::startsWith(Route::currentRouteName(), 'admin') ? 'text-white' : 'text-secondary' }} ">Users</a></li>
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

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="deleteModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/js/toastr.min.js"></script>
    <script src="/js/script.js"></script>
    <script>
        "use strict";
        (function($){
            $(document).ready(function(){
                @yield('javascript','')
            });
        })(jQuery);
    </script>
</body>
</html>