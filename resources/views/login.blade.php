<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Quizzer</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/normalize.css') }}"/>
        <link rel="stylesheet" href="{{ asset('css/login.css') }}"/>
        <style>
            .logo {
                background: url("{{ asset('images/logo.png') }}");
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                
                <div class="mt-8 bg-secondary overflow-hidden shadow sm:rounded-lg text-center ">
                    <div class='error hidden alert alert-danger py-1' role='alert'>
                        <span id='text'>Mensaje de error</span>
                    </div>
                    <div class="logo"></div>
                    <div class="">
                        <p class='app-title text-center text-lg text-light'>Quizzer</p>
                    </div>
                    <div class="form pb-3">
                        <form class='form px-3' method='post' action="{{ route('checkLogin') }}">
                            @csrf
                            {{ csrf_field() }}
                            <input type="text" name='email' id='email' class='form-control w-100 my-2' placeholder='¿Tu email?'/>
                            <input type="password" name='password' id='password' class='form-control w-100 my-2' placeholder='¿Tu clave?'/>
                            <button id='login' type='button' class='btn btn-md btn-dark w-100 mb-2'>Ingresar</button>
                            <button class='btn btn-md btn-dark w-100 mb-3'>Registrarme</button>
                        </form>
                        <a class='' href=''>Olvidé mis credenciales!</a>
                    </div>
                </div>

                <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                    <div class="w-100 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                        &copy;&nbsp;Claudio Delgado 2022<br/>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                </div>
                
            </div>
        </div>
        <script>
            document.querySelector('#login').addEventListener("click", event => {
                let urlToCall = "/checkLogin";
                let dataToSend = {
                    "_token": '{{ csrf_token() }}', 
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value
                }
                fetch(urlToCall, {
                    method: 'POST',
                    body: JSON.stringify(dataToSend),
                    headers: {'Content-type': 'application/json'}
                }).then(res => res.json())
                .catch(error => {
                    //debugger;
                    const msjBox = document.querySelector(".error");
                    const msjTxt = document.querySelector(".error span");
                    msjBox.classList.remove('hidden');
                    msjBox.classList.remove('alert-success');
                    msjBox.classList.remove('alert-danger');
                    msjBox.classList.add('alert-danger');
                    msjTxt.innerHTML = error;
                    //console.error('Error:', error)
                })
                .then(response => {
                    if(response != undefined){
                        /**/
                        if(response.length){
                            location = '/home';
                            /*msjBox.classList.add('alert-success');
                            msjTxt.innerHTML = "Credenciales correctas";*/
                        } else {
                            const msjBox = document.querySelector(".error");
                            const msjTxt = document.querySelector(".error span");
                            msjBox.classList.remove('hidden');
                            msjBox.classList.remove('alert-success');
                            msjBox.classList.remove('alert-danger');
                            msjBox.classList.add('alert-danger');
                            msjTxt.innerHTML = "Credenciales inválidas";
                        }
                    }
                })
            });
        </script>
    </body>
</html>
