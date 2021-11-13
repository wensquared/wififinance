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
        <p>{{gettype(Auth::user()->balance)}}</p>
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
                                                    <input type="number" min="1" step=".01" class="form-control @error('username') is-invalid @enderror" name="balance" id="balance">
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
                                                    <input type="number" min="1" step=".01" class="form-control @error('username') is-invalid @enderror" name="balance" id="balance">
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
    @endcan
@endsection

@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif
@endsection