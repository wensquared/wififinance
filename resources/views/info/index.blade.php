@extends('layouts.main')
@section('pageTitle', 'Stock Info')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row mt-5 mb-2">
                <div class="col-2"></div>
                <div class="col-3">
                    <h3>Search ticker:</h3>
                </div>
            </div>
            <form class="row" method="POST" action="{{ route('info.result') }}">
                @csrf
                <div class="col-2"></div>
                <div class="col-6">
                    <input id="ticker" type="text" class="form-control @error('ticker') is-invalid @enderror" name="ticker" value="{{ old('ticker') }}" required autocomplete="ticker" autofocus>
                    @error('ticker')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-4 mb-2">
                    <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    @if( session('success') )
        window.myToastr('success', '{{ session('success') }}' );
    @elseif ( session('error'))
        window.myToastr('error', '{{ session('error') }}' );
    @endif
@endsection