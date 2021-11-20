@extends('layouts.main')
@section('pageTitle', 'Portfolio')

@section('content')
    @can('user_verified_gate')
        
        <h1>My Portfolio</h1>
    @endcan

    <h1>My Watchlist</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Ticker</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col"></th>
                
            </tr>
        </thead>
        <tbody>
            {{-- Daten aus DS auslesen in controller und hier darstellen --}}
            {{ $count = 1}}
            @foreach ($watchlist as $item)
                <tr>
                    <th scope="row">{{ $count++ }}</th>
                    <td>{{ $item['ticker'] }}</td>
                    <td>{{ $item['ticker_name']}}</td>
                    <td>{{ $item['price'] }} $</td>
                    <td>
                        <form class="addWatchlist" action="{{ route('info.watchlist')}}" method="POST">
                            @csrf
                            <input type="hidden" name="ticker" value="{{$item['ticker']}}">
                            <button type="submit" id="btnHeart" class="btn btn-outline-danger fa fa-heart"></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif
@endsection