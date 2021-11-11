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
        <p>Balance: {{$balance ?? 0}} EUR</p>

        <div class="contrainer">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Edit') }}</div>
                        <div class="card-body">
                            <div id="form" class="form">
                                <form action="#" method="POST"">
                                    @csrf
                                    {{-- @method('PUT') --}}

                                    <div class="form-group row mb-2">
                                        <label for="balance" class="col-md-4 col-form-label text-md-right">Deposit/Withdraw</label>
                                        <div class="col-md-6">
                                            <input type="number" min="0" step=".01" class="form-control @error('username') is-invalid @enderror" name="balance" id="balance">
                                            @error('balance')
                                                <span class="invalid-feedback">
                                                {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-dark">speichern</button>
                                </form>
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