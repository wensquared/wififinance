@extends('layouts.main')
@section('pageTitle', 'User verification image')

@section('content')
<h1>All Stock Transaction</h1>
<div class="contrainer">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                        {{-- <div class="pagination pag_balance justify-content-center">{{ $data->links() }}</div> --}}
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
    