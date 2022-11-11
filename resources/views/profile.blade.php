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
        <!-- Jquery -->
        <script
			  src="https://code.jquery.com/jquery-3.6.0.min.js"
			  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
			  crossorigin="anonymous"></script>
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/main.css') }}"/>
        <link rel="stylesheet" href="{{ asset('css/login.css') }}"/>
        <style>
            .logo {
                background: url("{{ asset('images/logo.png') }}");
            }
        </style>
    </head>
    <body class="antialiased">
        <div class='container h-100'>
            <div class="mx-auto h-100 d-flex flex-wrap align-items-center justify-content-center">
                <div class='col-lg-6 col-md-8 col-12'>
                    <div class="col-12 bg-secondary shadow p-3 rounded my-0 text-center">
                        <div class='error alert @if(!$message) d-none alert-danger @else alert-success @endif m-0 py-1' role='alert'>
                            <span id='text'>{{ $message }}</span>
                        </div>
                        <a href='/home'><div class="logo"></div></a>
                        <div class="">
                            <p class='app-title text-center text-lg text-light m-0'>Mi perfil</p>
                            <p class='text-center fw-bold text-lg text-dark fs-6 m-0 mb-3 p-0'>Completá tus datos para obtener tu usuario</p>
                        </div>
                        <div class="pb-0">
                            <form class='d-flex flex-wrap px-3' method='post' action="{{ route('saveProfile') }}">
                                @csrf
                                <input type='hidden' name='id' value='{{ $user->id }}'/>
                                <div class='col-sm-6 col-12 pe-sm-2 pe-0 my-2'>
                                    <input type="text" name='name' id='nombre' class='form-control' placeholder='¿Tu nombre?' value='{{ $user->name }}'/>
                                </div>
                                <div class='col-sm-6 col-12 ps-sm-2 ps-0 my-2'>
                                    <select name='gender' id='genero' class='form-select col-3'>
                                    @foreach($generos as $genero)
                                        <option value='{{ $genero }}' @if($genero == $user->gender) selected @endif>{{ $genero }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class='col-sm-6 col-12 pe-sm-2 pe-0 my-2'>
                                    <input type="text" name='email' id='email1' class='form-control col-lg-6 col-12' placeholder='¿Tu email?' value='{{ $user->email }}'/>
                                </div>
                                <div class='col-sm-6 col-12 ps-sm-2 ps-0 my-2'>
                                    <input type="text" name='email2' id='email2' class='form-control col-lg-6 col-12' placeholder='Repetí el email' value='{{ $user->email }}'/>
                                </div>
                                <div class='col-sm-6 col-12 pe-sm-2 pe-0 my-2'>
                                    <input type="date" name='birthDate' id='fechaNac' class='form-control col-lg-6 col-12' value='{{ $user->birthDate }}'/>
                                </div>
                                <div class='col-sm-6 col-12 ps-sm-2 ps-0 d-flex align-items-center my-2'>
                                    <div class='col-12 h-100 alert alert-info credits py-0 m-0 fw-bold text-uppercase d-flex align-items-center'>Tu puntaje actual:&nbsp;<span class='text-danger' id='score'>{{ $user->score }}</span></div>
                                </div>
                                <div class='col-sm-6 col-12 pe-sm-2 pe-0 my-2'>
                                    <button id='registrar' type='submit' class='btn btn-md btn-dark w-100'>Confirmar</button>
                                </div>
                                <div class='col-sm-6 col-12 ps-sm-2 ps-0 my-2'>
                                    <button id='activar' disabled class='btn btn-md btn-dark w-100'>Activar usuario</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-center mt-1">
                        <div class="w-100 text-center credits text-secondary">
                            &copy;&nbsp;Claudio Delgado 2022<br/>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        
            /*document.querySelector('#registrar').addEventListener("click", event => {
            });
            document.querySelector('#miPerfil').addEventListener("click", event => {
                location = "/logout";
            });
            document.querySelector('#ranking').addEventListener("click", event => {
                location = "/logout";
            });
            document.querySelector('#cerrar').addEventListener("click", event => {
                location = "/logout";
            });*/

        </script>
    </body>
</html>
