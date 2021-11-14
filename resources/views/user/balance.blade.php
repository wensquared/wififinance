@extends('layouts.main')
@section('pageTitle', 'Balance')

@section('content')
    {{-- {{Auth::user()->role->role}} --}}
    @if (Auth::user()->role->role == 'user')
            
    {{-- @can('usergate') --}}
        <p> Please verify your account by adding a verification image.  <a href="{{ route('user.edit', Auth::user()->id ) }}">Click here</a></p>
    {{-- @endcan --}}

        
    @endif 
    
    @can('user_verified_gate')
        
        <h1>My Balance</h1>
        {{-- <p>{{gettype(Auth::user()->balance)}}</p> --}}
        <p>Balance: {{Auth::user()->balance ?? 0}} EUR</p>

        <div class="container">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Deposit</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Withdraw</button>
                </li>
                
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="form" class="form">
                                        <form action="{{ route('balance.update') }}" method="POST">
                                            @csrf
                                            @method('PUT')
        
                                            <div class="form-group row mb-2">
                                                <label for="balance" class="col-md-4 col-form-label text-md-right">Deposit</label>
                                                <div class="col-md-6">
                                                    <input type="number" step=".01" class="form-control @error('balance') is-invalid @enderror" name="balance" id="balance">
                                                    @error('balance')
                                                        <span class="invalid-feedback">
                                                        {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
        
                                            <button type="submit" class="btn btn-dark" name="deposit_form">speichern</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="form" class="form">
                                        <form action="{{ route('balance.update') }}" method="POST">
                                            @csrf
                                            @method('PUT')
        
                                            <div class="form-group row mb-2">
                                                <label for="balance" class="col-md-4 col-form-label text-md-right">Withdraw</label>
                                                <div class="col-md-6">
                                                    <input type="number" step=".01" class="form-control @error('username') is-invalid @enderror" name="balance" id="balance">
                                                    @error('balance')
                                                        <span class="invalid-feedback">
                                                        {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
        
                                            <button type="submit" class="btn btn-dark" name="withdraw_form">speichern</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            {{-- <h3>Balance Transaction History</h3> --}}
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                        Balance Transaction History
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion-body">
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
                                    @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->amount}}</td>
                                                <td>{{ $item->action ? 'Deposit' : 'Withdraw'}}</td>
                                                <td>{{ $item->created_at->format('d.m.Y H:i:s') }}</td>
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination pag_balance justify-content-center">{{ $data->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
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