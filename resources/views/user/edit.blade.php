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

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@section('content')
<div class="container">
    <div class="linkbar"><a href="/users">Back to users</a></div>
    
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
                    <label for="role">Role:</label>
                    <select name="role_id" id="role_id" class="form-control">
                        <option value="1"
                        @if ($user->role_id == "1")
                            selected
                        @endif
                        >Administrator</option>

                        <option value="2"
                        @if ($user->role_id == "2")
                            selected
                        @endif
                        >User</option>
                    </select><br>
                    <input type="submit" class="btn btn-primary" value="Update">
                </form>
                <form action="/user/delete/{{$user->id}}">
                    <input type="submit" class="btn btn-warning" onclick="confirmDelete({{$user->id}})" value="Delete">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection