@extends('layouts.app')
@section('header')
    <title>Archive | Admins</title>
@endsection
@section('navbar')
    @component('layouts.navbar')
    @slot('enable_search')
        no
    @endslot
    @endcomponent
@endsection
@section('content')
    <div class="container">
        <div class="linkbar"><a href="/home">Back to Admin page</a></div>
        <div class="card">
            <div class="card-header">Registered Users</div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <th>email</th>
                        <th>Created</th>
                        <th>Updated</th>
                    </tr>
                    @foreach ($users as $user)               
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->created_at->diffForHumans()}}</td>
                            <td>{{$user->updated_at->diffForHumans()}}</td>
                            <td><a href="/user/edit/{{$user->id}}"><i class="fas fa-edit"></i></a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection