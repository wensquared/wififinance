@extends('layouts.main')
@section('pageTitle', 'Portfolio')

@section('content')
    @can('user_verified_gate')
        
        <h1>My Portfolio</h1>
    @endcan

    <h1>My Watchlist</h1>
@endsection

@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif
@endsection