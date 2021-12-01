@extends('layouts.main')
@section('pageTitle', 'All Transactions')

@section('content')
<div class="contrainer">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <form class="row mt-3 mb-2" method="POST" action="{{ route('stocklist.search_ticker_history') }}">
                @csrf
                <div class="col-2"></div>
                <div class="col-6">
                    <input id="ticker" type="text" class="form-control @error('ticker') is-invalid @enderror" name="ticker" value="{{ old('ticker') }}" {{-- required --}} autocomplete="ticker" {{-- autofocus --}}>
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

            <div class="card">
                <div class="card-header">{{ __('Transaction History') }}</div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Ticker</th>
                                    <th scope="col">Price/Share</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">$</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stock_history as $item)
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
                        <div class="pagination pag_balance justify-content-center">{{ $stock_history->links() }}</div>
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