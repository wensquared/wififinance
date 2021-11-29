@extends('layouts.main')
@section('pageTitle', 'Portfolio')

@section('content')
    @can('user_verified_gate')
        
        <h1>My Portfolio</h1>
        @if (isset($stocklist))
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Avg. Cost/share</th>
                    <th scope="col">Shares</th>
                    <th scope="col">Holdings Value</th>
                    <th scope="col">Profit/Loss</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    
                </tr>
            </thead>
            <tbody>
                <?php $count = 1 ?>
                @foreach ($stocklist as $item)
                    <tr>
                        <th scope="row">{{ $count++ }}</th>
                        <td>
                            <form method="POST" action="{{ route('info.result') }}">
                                @csrf
                                <input type="hidden" name="ticker" value="{{ $item['ticker'] }}">
                                <button type="submit" class="btn-link">{{ $item['ticker_name'].' ('.$item['ticker'].')' }}</button>
                            </form>
                        </td>
                        <td>{{ $item['price'] }} $</td>
                        <td>{{ $item['avg_price_per_share'] }} $</td>
                        <td>{{ $item['stock_amount'] }}</td>
                        <td>{{ $item['holding_value'] }} $</td>
                        <td>{{ $item['profit_loss'] }} %</td>
                        <td><a href="{{ route('stocklist.show', $item['ticker']) }}" class="btn btn-outline-dark fa fa-history"></a></td>
                        <td>
                            <button type="button" class="btn btn-primary buybtn" data-toggle="modal" data-target="#buyModal" data-ticker="{{$item['ticker']}}" data-price="{{$item['price']}}" data-balance="{{Auth::user()->balance}}" data-amount="{{ $item['stock_amount'] }}">
                                Buy
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary sellbtn" data-toggle="modal" data-target="#sellModal" data-ticker="{{$item['ticker']}}" data-price="{{$item['price']}}" data-balance="{{Auth::user()->balance}}" data-amount="{{ $item['stock_amount'] }}">
                                Sell
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        <a href="{{ route('stocklist.stock_history')}}" class="btn btn-primary">History</a>
    @endcan


    <h1>My Watchlist</h1>
    @if (isset($watchlist))

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col"></th>
                
            </tr>
        </thead>
        <tbody>
            <?php $count = 1 ?>
            @foreach ($watchlist as $item)
                <tr>
                    <th scope="row">{{ $count++ }}</th>
                    <td>
                        <form method="POST" action="{{ route('info.result') }}">
                            @csrf
                            <input type="hidden" name="ticker" value="{{ $item['ticker'] }}">
                            <button type="submit" class="btn-link">{{ $item['ticker_name'].' ('.$item['ticker'].')' }}</button>
                        </form>
                    </td>
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
    @endif
@endsection

@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif
@endsection