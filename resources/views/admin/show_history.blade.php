@extends('layouts.main')
@section('pageTitle', 'User verification image')

@section('content')
<h1>User History</h1>
<div class="button"><a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">All Users admin</a></div>
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
                                    <th scope="col">Amount</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Daten aus DS auslesen in controller und hier darstellen --}}
                                @foreach ($user_balance_history as $item)
                                        <tr>
                                            <td>{{ $item->amount}}</td>
                                            <td>{{ $item->action ? 'Deposit' : 'Withdraw'}}</td>
                                            <td>{{ $item->created_at->format('d.m.Y H:i:s') }}</td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <div class="pagination pag_balance justify-content-center">{{ $user_balance_history->links() }}</div> --}}
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
    