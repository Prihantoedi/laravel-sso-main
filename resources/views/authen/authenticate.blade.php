@extends('layout.app')
@section('content')
    {{-- @php dd($auth_data); @endphp --}}
    <div class="container pt-5">
        <div class="text-center"><h5>You're about to authenticate</h5></div>
        <div class="mt-4 d-flex justify-content-center gap-2">
            
            <button class="btn btn-primary" id="auth-btn">Authentication</button>
            <script>
                const token = {!! json_encode($auth_data)!!};
                   
                // redirect to client app with url and access token and client

                let authBtn = document.getElementById('auth-btn');

                authBtn.addEventListener('click', function(){
                    
                });

                window.location.href = `http://127.0.0.1:8080?acc=${token['access']}`;
             
            </script>
            <button class="btn btn-danger" id="cancel-btn">Cancel</button>
        </div>
    </div>
@endsection