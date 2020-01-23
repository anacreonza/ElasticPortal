@extends('site')
@section('header')
    <title>Admin | Create New User</title>
@endsection
@section('content')
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="linkbar"><a href="/home">Back to user home page</a></div>
    <h2>Create new user</h2>
    <div class="story-background">
        <form action="/user/store" method="GET">
            @csrf
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control"><br>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control"><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control"><br>
            <label for="password">Confirm Password:</label>
            <input type="password" name="confirm" id="confirm" class="form-control"><br>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection