@extends('layouts.main')
@section('pageTitle', 'Stock Info')
@section('content')
    <h1>Info result</h1>
    <h3>Price now of {{$ticker_name}}: {{ $now_price }}$</h3>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                
                <form method="POST" action="{{ route('info.result') }}">
                    @csrf

                    <div class="form-group row mb-2">
                        <div class="col-md-6">
                            <input id="ticker" type="ticker" class="form-control @error('ticker') is-invalid @enderror" name="ticker" value="{{ old('ticker') }}" required autocomplete="ticker" autofocus>
                            @error('ticker')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </div>
                </form>
                
        </div>
    </div>
    <p>{{ $description }}</p>

    <div class="container">
        {{-- <button type="submit" class="btn btn-outline-danger fa fa-heart"></button> --}}
        {{-- <button type="submit" class="btn btn-outline-secondary fa fa-heart"></button> --}}
    </div>
    @auth
        <div class="container">
            <form class="addWatchlist" action="{{ route('info.watchlist')}}" method="POST" data-ticker="{{$ticker}}">
                @csrf
                <input type="hidden" name="ticker" value="{{$ticker}}">
                <button type="submit" id="btnHeart" class="btn {{ $user_has_ticker->isEmpty() ? 'btn-outline-secondary' : 'btn-outline-danger'}} fa fa-heart"></button>
            </form>
        </div>

        @can('user_verified_gate')
            <div class="container">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary buybtn" data-toggle="modal" data-target="#buyModal">
                    Buy
                </button>
            </div>
        @endcan
        
    @endauth

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

        
        {{-- const labels = [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                ]; --}}
                
        const labels = <?php echo $dates;?>;
        const data = {
            labels: labels,
            datasets: [{
            label: 'Price Chart',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            {{-- data: [0, 10, 5, 2, 20], --}}
            
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