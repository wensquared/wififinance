@extends('layouts.main')
@section('pageTitle', 'Balance')

@section('content')
    @if (Auth::user()->role == 2 && !(Auth::user()->role == 3))
        
    {{-- @can('usergate') --}}
        <p> Please verify your account by adding a verification image.  <a href="{{ route('user.edit', Auth::user()->id ) }}">Click here</a></p>
    {{-- @endcan --}}
    
        
    @endif 

    @can('user_verified_gate')
        
        <h1>My Balance</h1>
        <p>Balance: {{ $balance }}</p>
    @endcan
@endsection

@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif
@endsection