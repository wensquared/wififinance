@extends('layouts.main')

@section('content')
    @can('user_verified_gate')
        
        <h1>My Portfolio</h1>
    @endcan

    <h1>My Watchlist</h1>
@endsection