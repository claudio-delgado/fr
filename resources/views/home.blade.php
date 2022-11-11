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
                        <div class='error d-none alert alert-danger m-0 py-1' role='alert'>
                            <span id='text'>Mensaje de error</span>
                        </div>
                        <div class="logo"></div>
                        <div class="">
                            <p class='app-title text-center text-lg text-light m-0'>Bienvenido/a</p>
                            <p class='text-center fw-bold text-lg text-dark fs-6 m-0 mb-3 p-0'>{{ session('email') }}</p>
                        </div>
                        <div class="pb-0">
                            <div class='px-0 d-flex flex-wrap'>
                                <div class='col-sm-6 col-12 px-1'>
                                    <button id='nuevoTest' type='button' class='w-100 btn btn-md btn-primary mb-2 text-nowrap'>Iniciar test</button>
                                </div>
                                <div class='col-sm-6 col-12 px-1'>
                                    <button id='miPerfil' type='button' class='w-100 btn btn-md btn-danger mb-2'>Mi perfil</button>
                                </div>
                            </div>
                            <div class='px-0 d-flex flex-wrap'>
                                <div class='col-sm-6 col-12 px-1'>
                                    <button id='ranking' type='button' class='btn btn-md btn-success w-100 mb-2'>Ránking</button>
                                </div>
                                <div class='col-sm-6 col-12 px-1'>
                                    <button id='cerrar' type='button' class='btn btn-md btn-dark w-100 mb-2'>Salir</button>
                                </div>
                            </div>
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
        
            document.querySelector('#nuevoTest').addEventListener("click", event => {
                location = "/createTest";
            });
            document.querySelector('#miPerfil').addEventListener("click", event => {
                location = "/profile";
            });
            document.querySelector('#ranking').addEventListener("click", event => {
                location = "/ranking";
            });
            document.querySelector('#cerrar').addEventListener("click", event => {
                location = "/logout";
            });

        </script>
    </body>
</html>
