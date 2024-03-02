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


        @if($client != '')
            <div class="login-form">
                <div>
                    <label>Email</label>
                    <input class="form-control" name="email" id="sso-email" type="email" placeholder="Type here..." required>    
                    
                </div>
                <div class="mt-3">
                    <label>Password</label>
                    <input class="form-control" name="password" id="sso-password" type="password" placeholder="Type here" required>
                </div>
                <div class="mt-3 text-center">
                    <button type="submit" class="btn btn-primary" id="btn-login" style="width: 100%;">Login</button>
                </div>
            </div>

            <script>
                const btnLogin = document.getElementById('btn-login');
                btnLogin.addEventListener('click', function(){
                    const uri = window.location.href;
                    const uriSplitter = uri.split('app=');
                    const client_app = uriSplitter[uriSplitter.length - 1];
                    const email = document.getElementById('sso-email').value;
                    const password = document.getElementById('sso-password').value;
                    
                    const data = {
                        email : email,
                        password: password,
                        client_app : client_app
                    };


                    let formData = new FormData();

                    formData.append('credential', JSON.stringify(data));


                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', 'http://127.0.0.1:8000/api/v1/login/validation');
                    
                    xhr.onreadystatechange = function(){
                        if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200){
                            const res = JSON.parse(xhr.responseText);
                            if(res.message === 'login success'){
                                // console.log(res.data);
                                let destination = '';
                                if(client_app === 'first_client_app'){
                                    destination = `http://127.0.0.1:8080/transit?acc=${res.data.access}`;
                                    
                                }   
                                // console.log(destination);
                                window.location.href = destination;
                            }
                        }
                    };

                    xhr.send(formData);




                    
                });
            </script>
        @else
            <div class="login-form">
                <form action= "{{route('login.attempt')}}" method="POST">
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
        @endif
        
        
    </div>
@endsection