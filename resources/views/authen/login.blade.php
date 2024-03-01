@extends('layout.app')
@section('content')
    <div class="container login-box-wrapper mt-3 p-5">
        <div class="text-center">
            <h2>Login Here</h2>
        </div>
        <div class="login-form">
            <form action="{{route('login.attempt')}}" method="POST">
                @csrf
                <div>
                    <label>Email</label>
                    <input class="form-control" name="email" type="email" placeholder="Type here...">    
                    
                </div>
                <div class="mt-3">
                    <label>Password</label>
                    <input class="form-control" name="password" type="password" placeholder="Type here">
                </div>
                <div class="mt-3 text-center">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
                </div>
                
            </form>
        </div>
        
    </div>
@endsection