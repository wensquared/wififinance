@extends('layouts.main')
@section('pageTitle', 'Stock Info')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row mt-1 mb-2">
                    {{-- <div class="col-2"></div> --}}
                    <div class="col-3">
                        <h3>Search ticker:</h3>
                    </div>
                </div>
                <form class="row" method="POST" action="{{ route('info.result') }}">
                    @csrf
                    {{-- <div class="col-2"></div> --}}
                    <div class="col-6">
                        <input id="ticker" type="text" class="form-control @error('ticker') is-invalid @enderror" name="ticker" value="{{ old('ticker') }}" required autocomplete="ticker" autofocus>
                        @error('ticker')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-4 mb-2">
                        <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @guest
        <div class="container mt-4">
            <div class="row">
                <h3>{{$ticker_name}}: {{ $now_price }}$</h3>
            </div>
        </div>
    @endguest

    @can('user_verified_gate')
        <div class="container mt-4">
            <div class="row">
                <h3>{{$ticker_name}}: {{ $now_price }}$</h3>
            </div>
            @if ($num_of_stocks)
                <div class="row">
                    <h4>Number of shares in portfolio: {{$num_of_stocks}}</h4>
                </div>
            @endif
        </div>
    @endcan
    
    @auth
        <div class="container mt-2 mb-2">
            <div class="row">
                <div class="col-3">
                    <form class="addWatchlist" action="{{ route('info.watchlist')}}" method="POST" data-ticker="{{$ticker}}">
                        @csrf
                        <input type="hidden" name="ticker" value="{{$ticker}}">
                        <button type="submit" id="btnHeart" class="btn {{ $user_has_ticker->isEmpty() ? 'btn-outline-secondary' : 'btn-outline-danger'}} fa fa-heart"></button>
                        
                        @can('user_verified_gate')
                            <button type="button" class="btn btn-primary buybtn" data-toggle="modal" data-target="#buyModal" data-ticker="{{$ticker}}" data-price="{{$now_price}}" data-balance="{{Auth::user()->balance}}" data-amount="{{$num_of_stocks}}">
                                Buy
                            </button>
                            @if ($num_of_stocks)
                                <button type="button" class="btn btn-primary sellbtn" data-toggle="modal" data-target="#sellModal" data-ticker="{{$ticker}}" data-price="{{$now_price}}" data-balance="{{Auth::user()->balance}}" data-amount="{{$num_of_stocks}}">
                                    Sell
                                </button>
                            @endif
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    @endauth

    <div class="container">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    More Information about {{$ticker_name}}
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        {{ $description }}
                    </div>
                </div>
            </div> 
        </div>
    </div>    

    <div class="container">
        <canvas id="myChart"></canvas>
    </div>
@endsection



@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif
                
        const labels = <?php echo $dates;?>;
        const data = {
            labels: labels,
            datasets: [{
            label: 'Price Chart',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            
            data: {{ $close_prices }}
            }]
        };
        const config = {
            type: 'line',
            data: data,
            options: {}
        };
        var myChart = new Chart(
                    document.getElementById('myChart'),
                    config
                    );
@endsection