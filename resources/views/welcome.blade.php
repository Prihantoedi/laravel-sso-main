@extends('layout.app')
@section('content')
<div class="container pt-5">
    
    @if($is_auth)
        <div class="text-end">
            <a href="{{route('logout')}}">Logout</a>
        </div>
    @endif
    <div class="text-center"><h1>SSO First (Main) App with Laravel</h1></div>

    @if(!$is_auth)
        <div class="not-auth-info text-center mt-5">
            <div>You're still not loggin</div>
            <div class="mt-3">
                <a href="{{route('login')}}" class="btn btn-danger">SSO Login</a>
            </div>
        </div>
    @endif

    <div class="client-app-grp mt-4 d-flex justify-content-center gap-3 p-5" style="border: 1px #e7e7e7 solid;">
        <form action="" method="GET">
            <button class="btn btn-warning" id="dum-2nd-app-btn">Go to 2nd App</button>
            <button id="go-2nd-app" type="submit" hidden></button>
        </form>
        
        <form action="{{route('login')}}" method="GET">
            <button class="btn btn-primary" id="dum-3rd-app-btn" type="submit">Go to 3rd App</button>
            <button id="go-3rd-app" type="submit" hidden></button>
        </form>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    let dum2ndAppBtn = document.getElementById('dum-2nd-app-btn');

    dum2ndAppBtn.addEventListener("click", function(event){
        event.preventDefault();
        window.location.href = 'http://127.0.0.1:8080';
        
    });

    let dum3rdAppBtn = document.getElementById('dum-3rd-app-btn');

    dum3rdAppBtn.addEventListener("click", function(event){
        event.preventDefault();
    });

</script>
@endsection
