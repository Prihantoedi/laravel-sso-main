@extends('layout.app')
@section('content')
    <div class="container pt-5">
        <div class="text-center"><h5>You're about to authenticate</h5></div>
        <div class="mt-4 d-flex justify-content-center gap-2">
            
            <button class="btn btn-primary" id="auth-btn">Authentication</button>
            
            <button class="btn btn-danger" id="cancel-btn">Cancel</button>
        </div>
    </div>
@endsection