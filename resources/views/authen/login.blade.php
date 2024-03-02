@extends('layout.app')
@section('content')
    <div class="container login-box-wrapper mt-3 p-5">
        <div class="text-center">
            <h2>SSO Login Here</h2>
        </div>

        @if(Session::get('error'))
            <div class="alert alert-danger" role="alert">
                {{Session::get('error')}}
            </div>
        @endif
        
        <div class="login-form">
            <form action="{{route('login.attempt')}}" method="POST">
                @csrf
                <div>
                    <label>Email</label>
                    <input class="form-control" name="email" type="email" value="@if(isset($old_input['email'])) {{$old_input['email']}}@endif" placeholder="Type here..." required>    
                    
                </div>
                <div class="mt-3">
                    <label>Password</label>
                    <input class="form-control" name="password" type="password" placeholder="Type here" required>
                </div>
                <div class="mt-3 text-center">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
                </div>
                
            </form>
        </div>
        
    </div>
@endsection