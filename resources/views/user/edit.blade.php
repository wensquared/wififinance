@extends('layouts.main')
@section('pageTitle', 'User edit')

@section('content')
<h1>User edit</h1>
<div class="button"><a href="{{ route('user.index') }}" class="btn btn-outline-secondary">All Users</a></div>
<div class="contrainer">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit') }}</div>

                <div class="card-body">

    <div id="form" class="form">
        <form action="{{ route('user.update',$user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group row">
            <label for="username" class="col-md-4 col-form-label text-md-right">Username</label>
            <div class="col-md-6">
            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" value="{{ old('username',$user->username) }}">
            @error('username')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
            @enderror
            </div>
            </div>
            <div class="form-group row">
                <label for="role_id" class="col-md-4 col-form-label text-md-right">Abteilung</label>
                <div class="col-md-6">
                <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
                    <option value="">Choose Role</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @if( (old('role_id',$user->role_id) == $role->id) ) selected @endif >{{ $role->role}}</option>
                    @endforeach
                </select>
                @error('role_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>
                <div class="col-md-6">
                <input type="text" class="form-control" name="email" id="email" value="{{ old('email',$user->email) }}">
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" autocomplete="new-password">
                </div>
            </div>
            <div class="form-group row">
            <label for="firstname" class="col-md-4 col-form-label text-md-right">Firstname</label>
            <div class="col-md-6">
            <input type="text" class="form-control  @error('firstname') is-invalid @enderror" name="firstname" id="firstname" value="{{ old('firstname', $user->firstname) }}">
            @error('firstname')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
            @enderror
            </div>
            </div>
            <div class="form-group row">
                <label for="lastname" class="col-md-4 col-form-label text-md-right">Lastname</label>
                <div class="col-md-6">
                <input type="text" class="form-control  @error('lastname') is-invalid @enderror" name="lastname" id="lastname" value="{{ old('lastname', $user->lastname) }}">
                @error('lastname')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-md-4 col-form-label text-md-right">Address</label>
                <div class="col-md-6">
                <input type="text" class="form-control  @error('address') is-invalid @enderror" name="address" id="address" value="{{ old('address', $user->address) }}">
                @error('address')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="postcode" class="col-md-4 col-form-label text-md-right">Postcode</label>
                <div class="col-md-6">
                <input type="text" class="form-control  @error('postcode') is-invalid @enderror" name="postcode" id="postcode" value="{{ old('postcode', $user->postcode) }}">
                @error('postcode')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="country_id" class="col-md-4 col-form-label text-md-right">Country</label>
                <div class="col-md-6">
                <select name="country_id" id="country_id" class="form-control @error('country_id') is-invalid @enderror">
                    <option value="">Choose Country</option>
                    @foreach ($countries as $country)
                    <option value="{{ $country->id }}" @if( (old('abteilung_id',$user->country_id) == $country->id) ) selected @endif >{{ $country->country}}</option>
                    @endforeach
                </select>
                @error('country_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
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
@endsection
    