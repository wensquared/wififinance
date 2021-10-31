@extends('layouts.main')

@section('content')
    <h1>Userlist</h1>
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
                <th scope="col">Verification Image</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            {{-- Daten aus DS auslesen in controller und hier darstellen --}}
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
                        <td>img</td>
        
                        @if ( Auth::user()->id != $user->id )
                            <td><a href="{{ route('user.edit',$user->id)}}" class="btn btn-outline-dark fa fa-edit"></a></td>
                            <td>
                            <form class="delete" action="{{ route('user.destroy', $user->id) }}" method="POST" data-title="{{ $user->name }}" data-body="Wollen Sie User: {{ $user->name }} <strong>wirklich</strong> loeschen">
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
@endsection