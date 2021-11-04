@extends('layouts.main')
@section('pageTitle', 'Balance')

@section('content')
    @can('user_verified_gate')
        
        <h1>My Balance</h1>
    @endcan
    @can('usergate')
        <p> Please verify your account by adding a verification image.  <a href="{{ route('user.edit', Auth::user()->id ) }}">Click here</a></p>
    @endcan
@endsection

@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif
@endsection