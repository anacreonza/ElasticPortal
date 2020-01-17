@extends('layouts.app')
@section('header')
    <title>Edit User</title>
    <script>
        function confirmDelete(user){
            var del = confirm('Are you sure you want to delete this user?')
            if (del){
                alert("deleting" user);
            } else {
                return false;
            }
        }
    </script>
@endsection

@section('navbar')
    @component('layouts.navbar')
    @endcomponent
@endsection

@section('content')
<div class="container">
    <div class="linkbar"><a href="/home">Back to user home page</a></div>
    
    <div class="story-background">
        <div class="card">
            <div class="card-header">Edit User</div>
            <div class="card-body">
                <form action="/user/update/{{$user->id}}" method="GET">
                    @csrf
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{$user->name}}"><br>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{$user->email}}"><br>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" value="{{$user->password}}"><br>
                    <label for="password">Confirm Password:</label>
                    <input type="password" name="confirm" id="confirm" class="form-control"  value="{{$user->password}}"><br>
                    <label for="role">Role:</label>
                    <input type="text" name="role" class="form-control" value="{{$user->role}}">
                    <br>
                    <button type="submit" class="btn btn-primary">Update</button>
                    {{-- <a href="/user/delete/{{$user->id}}" class="btn btn-warning" onclick="confirmDelete({{$user->id}})" role="button">Delete</a> --}}
                    <input type="button" class="btn btn-cancel" value="Cancel">
                    <input type="button" class="btn btn-warning" onclick="confirmDelete({{$user->id}})" value="Delete">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection