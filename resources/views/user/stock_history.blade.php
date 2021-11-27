@extends('layouts.main')
@section('pageTitle', 'Stock Buy/Sell History')

@section('content')
    
    @can('user_verified_gate')
        <div class="container">
        
            <table class="table table-striped">
                <thead>
                    <tr>
                        {{-- <th scope="col">#</th> --}}
                        <th scope="col">Price/Share</th>
                        <th scope="col">Amount</th>
                        <th scope="col">$</th>
                        <th scope="col">Action</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Daten aus DS auslesen in controller und hier darstellen --}}
                    @foreach ($stock_history->stocklist_history as $item)
                            <tr>
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
    @endcan
@endsection

@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif
@endsection