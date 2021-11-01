@extends('layouts.main')
@section('pageTitle', 'User edit')

@section('content')
<h1>User edit</h1>
<div class="button"><a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">All Users admin</a></div>
<div class="contrainer">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verification Image') }}</div>
                    <div class="card-body">
                        <img src="{{ route('admin.showimg','show_'.$user->verification_img) }}" alt="{{ $user->username }}">
                        <a href="{{ route('admin.download',$user->verification_img)}}" class="btn btn-outline-dark fa fa-download"></a>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
    