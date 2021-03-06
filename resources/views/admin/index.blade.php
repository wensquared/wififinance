@extends('layouts.main')
@section('pageTitle', 'Userlist')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <form class="row mt-2 mb-2" method="GET" action="{{ route('admin.index')}}">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <input id="user_email" type="text" class="form-control @error('user_email') is-invalid @enderror" name="user_email" value="{{ old('user_email') }}" required autocomplete="user_email" placeholder="User's email">
                        @error('user_email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                    </div>
                </form>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Firstname</th>
                            <th scope="col">Lastname</th>
                            <th scope="col">Address</th>
                            <th scope="col">Postcode</th>
                            <th scope="col">Country</th>
                            <th scope="col">Verif. Image</th>
                            <th scope="col">$</th>
                            <th scope="col">Stocks</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role->role ?? ''}}</td>
                                    <td>{{ $user->firstname }}</td>
                                    <td>{{ $user->lastname }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ $user->postcode }}</td>
                                    <td>{{ $user->country->country }}</td>
                                    <td>
                                        @if ($user->verification_img)
                                            <a href="{{ route('admin.show',$user->id) }}" class="btn btn-outline-dark fa fa-eye"></a>
                                        @else
                                            NA
                                        @endif
                                    </td>
                                    <td ><a href="{{ route('admin.user_history',$user->id)}}" class="btn btn-outline-dark fa fa-history"></a></td>
                                    <td ><a href="{{ route('admin.user_stock_history',$user->id)}}" class="btn btn-outline-dark fa fa-history"></a></td>
                                    @if ( Auth::user()->id != $user->id )
                                        <td><a href="{{ route('admin.edit',$user->id)}}" class="btn btn-outline-dark fa fa-edit"></a></td>
                                        <td>
                                        <form class="delete" action="{{ route('admin.destroy', $user->id) }}" method="POST" data-title="{{ $user->username }}" data-body="Do you really want to delete {{ $user->username}}?">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger fa fa-trash"></button>
                                        </form>
                                        </td>
                                    @endif
                                </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (isset($paginate))
                    <div class="pagination justify-content-center">{{ $users->links() }}</div>
                @endif
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