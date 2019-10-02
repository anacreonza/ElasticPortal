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
    @component('user.links')
    @endcomponent
    <div class="story-background">
        <h2>Edit user</h2>
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
            <button type="submit" class="btn btn-primary">Update</button>
            {{-- <a href="/user/delete/{{$user->id}}" class="btn btn-warning" onclick="confirmDelete({{$user->id}})" role="button">Delete</a> --}}
            <input type="button" class="btn btn-cancel" value="Cancel">
            <input type="button" class="btn btn-warning" onclick="confirmDelete({{$user->id}})" value="Delete">
        </form>
    </div>
</div>
@endsection