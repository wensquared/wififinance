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
    {{-- {{Route::currentRouteName()}} --}}
    <header class="p-3 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    @guest
                        <li><a href="/" class="nav-link px-2 {{ Str::startsWith(Route::currentRouteName(), 'mainpage') ? 'text-white' : 'text-secondary' }}">Home</a></li>
                    @else
                        <li><a href="{{route('user.index')}}" class="nav-link px-2 {{ Str::startsWith(Route::currentRouteName(), 'user') ? 'text-white' : 'text-secondary' }}">Home</a></li>
                    @endguest
                    <li><a href="{{ route('info.index')}}" class="nav-link px-2 {{ Str::startsWith(Route::currentRouteName(), 'info') ? 'text-white' : 'text-secondary' }}">Stock Info</a></li>
                    <li><a href="{{ route('faqs')}}" class="nav-link px-2 {{ Str::startsWith(Route::currentRouteName(), 'faqs') ? 'text-white' : 'text-secondary' }}">FAQs</a></li>
                    @auth
                        @can('admingate')
                            <li><a href="{{ route('admin.index') }}" class="nav-link px-2  {{ Str::startsWith(Route::currentRouteName(), 'admin') ? 'text-white' : 'text-secondary' }} ">Users</a></li>
                        @endcan
                    @endauth
                </ul>
        
                @guest
                <div class="text-end">
                    @if (Route::has('login'))
                        <a href="{{ route('login')}}"><button type="button" class="btn btn-outline-light me-2">Login</button></a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register')}}"><button type="button" class="btn btn-warning">Sign-up</button></a>
                    @endif
                @else
                    <a href="{{ route('balance.index')}}"><button type="button" class="btn btn-info">{{Auth::user()->balance ? Auth::user()->balance.' $' : 'Balance'}}</button></a>

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
                </div>
                @endguest
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

    @can('user_verified_gate')
    <div class="modal fade" id="buyModal" tabindex="-1" role="dialog" aria-labelledby="buyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="buyModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <p id="buyNumShares"></p>
            <div id="form" class="form">
                <form class="buy" action="{{ route('stocklist.buy')}}" method="POST" >
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row mb-2">
                            <label for="username" class="col-md-4 col-form-label text-md-right">Buy stock</label>
                            <div class="col-md-6">
                                <input type="number" class="amount form-control @error('amount') is-invalid @enderror" name="amount" id="buyAmount" min="1">
                                @error('amount')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="ticker" id="buyTicker">
                        <input type="hidden" name="price" id="buyPrice">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Buy</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>


    <div class="modal fade" id="sellModal" tabindex="-1" role="dialog" aria-labelledby="sellModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="sellModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <p id="sellNumShares"></p>
            <div id="form" class="form">
                <form class="sell" action="{{ route('stocklist.sell')}}" method="POST" >
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row mb-2">
                            <label for="username" class="col-md-4 col-form-label text-md-right">Sell stock</label>
                            <div class="col-md-6">
                                <input type="number" class="amount form-control @error('amount') is-invalid @enderror" name="amount" id="sellAmount" min="1">
                                @error('amount')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="ticker" id="sellTicker">
                        <input type="hidden" name="price" id="sellPrice">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Sell</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
    @endcan

    @can('admingate')
        <!-- Modal Delete -->
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
    @endcan

    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
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