@extends('layouts.main')
@section('pageTitle', 'User transaction history')

@section('content')
<h1>User History</h1>
<div class="button"><a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">All Users admin</a></div>
<div class="contrainer">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('admin.search_ticker_history') }}">
                @csrf
                <div class="form-group row mb-2 mt-2">
                    <div class="input-group mb-3 col-xs-4">
                        <input id="ticker" type="text" class="form-control @error('ticker') is-invalid @enderror" name="ticker" value="{{ old('ticker') }}" required autocomplete="ticker" autofocus>
                        @error('ticker')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="input-group-append">
                            <input type="hidden" name="user_id_history" value="{{$user_id_history}}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-header">{{ __('Transaction History') }}</div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    {{-- <th scope="col">#</th> --}}
                                    <th scope="col">Ticker</th>
                                    <th scope="col">Price/Share</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">$</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Daten aus DS auslesen in controller und hier darstellen --}}
                                @foreach ($user_stock_history as $item)
                                        <tr>
                                            <td>{{ $item->stocklist->ticker}}</td>
                                            <td>{{ $item->price}}$</td>
                                            <td>{{ $item->amount}}</td>
                                            <td>{{ $item->price * $item->amount}}$</td>
                                            <td>{{ $item->action ? 'Buy' : 'Sell'}}</td>
                                            <td>{{ $item->created_at->format('d.m.Y H:i:s') }}</td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination pag_balance justify-content-center">{{ $user_stock_history->links() }}</div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif
@endsection