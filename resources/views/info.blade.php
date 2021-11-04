@extends('layouts.main')

@section('content')
    <h1>Info</h1>
@endsection

@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif
@endsection