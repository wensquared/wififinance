@extends('layouts.main')
@section('pageTitle', 'Update data')

@section('content')
<h1>User edit</h1>
<div class="button"><a href="{{ route('portfolio.index') }}" class="btn btn-outline-secondary">My Portfolio</a></div>
<div class="contrainer">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit') }}</div>
                <div class="card-body">
                    <div id="form" class="form">
                        <form action="{{ route('user.update',$user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group row mb-2">
                                <label for="username" class="col-md-4 col-form-label text-md-right">Username</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" value="{{ old('username',$user->username) }}">
                                    @error('username')
                                        <span class="invalid-feedback">
                                        {{ $message }}
                                        </span>
                                @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row mb-2">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email',$user->email) }}">
                                    @error('email')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="firstname" class="col-md-4 col-form-label text-md-right">Firstname</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control  @error('firstname') is-invalid @enderror" name="firstname" id="firstname" value="{{ old('firstname', $user->firstname) }}">
                                    @error('firstname')
                                        <span class="invalid-feedback">
                                        {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="lastname" class="col-md-4 col-form-label text-md-right">Lastname</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control  @error('lastname') is-invalid @enderror" name="lastname" id="lastname" value="{{ old('lastname', $user->lastname) }}">
                                    @error('lastname')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="address" class="col-md-4 col-form-label text-md-right">Address</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control  @error('address') is-invalid @enderror" name="address" id="address" value="{{ old('address', $user->address) }}">
                                    @error('address')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="postcode" class="col-md-4 col-form-label text-md-right">Postcode</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control  @error('postcode') is-invalid @enderror" name="postcode" id="postcode" value="{{ old('postcode', $user->postcode) }}">
                                    @error('postcode')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="country_id" class="col-md-4 col-form-label text-md-right">Country</label>
                                <div class="col-md-6">
                                    <select name="country_id" id="country_id" class="form-control @error('country_id') is-invalid @enderror">
                                        <option value="">Choose Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" @if( (old('abteilung_id',$user->country_id) == $country->id) ) selected @endif >{{ $country->country}}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="verification_img" class="col-md-4 col-form-label text-md-right">Upload Verification</label>
                                <div class="col-md-6">
                                    <input type="file" class="form-control @error('verification_img') is-invalid @enderror" name="verification_img" id="verification_img">
                                    @error('verification_img')
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
@endsection
    