@extends('app')

@section('content')
    <h1>{{ config('app_name') }}</h1>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <label>
            Username :
            <input type="text" name="username" placeholder="Your username">
        </label>
        <label>
            Password :
            <input type="password" name="password" placeholder="Your password">
        </label>
        <input type="submit" value="Sign in">
    </form>
@endsection