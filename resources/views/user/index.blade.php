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
                    {{-- <th scope="col">Ticker</th> --}}
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
                {{-- Daten aus DS auslesen in controller und hier darstellen --}}
                
                    <?php $count = 1 ?>
                    @foreach ($stocklist as $item)
                        <tr>
                            <th scope="row">{{ $count++ }}</th>
                            {{-- <td>{{ $item['ticker'] }}</td> --}}
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
                                <button type="button" class="btn btn-primary buybtn" data-toggle="modal" data-target="#buyModal" data-text="{{$item['ticker'] }} | {{ $item['price'] }}">
                                    Buy
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary sellbtn" data-toggle="modal" data-target="#sellModal">
                                    Sell
                                </button>
                            </td>
                        </tr>


                        <div class="modal fade" id="buyModal" tabindex="-1" role="dialog" aria-labelledby="buyModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="buyModalLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div id="form" class="form">
                                    <form class="buy" action="{{ route('stocklist.buy')}}" method="POST" >
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group row mb-2">
                                                <label for="username" class="col-md-4 col-form-label text-md-right">Buy stock</label>
                                                {{-- <p >{{$item['ticker'] }} | {{ $item['price'] }}</p> --}}
                                                {{-- <p>{{(int) (Auth::user()->balance / $item['price'])}}</p> --}}
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" id="amount" min="1" max="{{(int) (Auth::user()->balance / $item['price'])}}">
                                                    @error('amount')
                                                        <span class="invalid-feedback">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="ticker" value="{{ $item['ticker'] }}">
                                            <input type="hidden" name="price" value="{{ $item['price'] }}">
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
                                <div id="form" class="form">
                                    <form class="sell" action="{{ route('stocklist.sell')}}" method="POST" >
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group row mb-2">
                                                <label for="username" class="col-md-4 col-form-label text-md-right">Sell stock</label>
                                                <p >{{$item['ticker'] }} | {{ $item['price'] }}</p>
                                                <p>You have {{$item['stock_amount']}} Stocks</p>
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" id="amount" min="1" max="{{ $item['stock_amount']}}">
                                                    @error('amount')
                                                        <span class="invalid-feedback">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="ticker" value="{{ $item['ticker'] }}">
                                            <input type="hidden" name="price" value="{{ $item['price'] }}">
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Sell</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            </div>
                        </div>
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
                {{-- <th scope="col">Ticker</th> --}}
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col"></th>
                
            </tr>
        </thead>
        <tbody>
            {{-- Daten aus DS auslesen in controller und hier darstellen --}}
            
                <?php $count = 1 ?>
                @foreach ($watchlist as $item)
                    <tr>
                        <th scope="row">{{ $count++ }}</th>
                        {{-- <td>{{ $item['ticker'] }}</td> --}}
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