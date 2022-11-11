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
        <!-- Sweetalert2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <a href='/home'><div class="logo"></div></a>
                        <div class="">
                            <p class='app-title text-center text-lg text-light m-0'>Nuevo test</p>
                            <div class='error {{ $errorClass }} alert alert-danger m-0 py-1' role='alert'>
                                <span id='text'>{{ $errorMessage }}</span>
                            </div>
                            @if(!$previousTest)
                            <p class='text-center fw-bold text-lg text-dark fs-6 m-0 mb-3 p-0'>Elegí la dificultad</p>
                            @endif
                        </div>
                        <div class="pb-0">
                            @if(!$previousTest)
                            <div class='px-0 d-flex flex-wrap'>
                                <div class='col-sm-6 col-12 px-1'>
                                    <button id='sencillo' type='button' class='w-100 btn btn-md btn-success mb-2 text-nowrap'>Sencillo</button>
                                </div>
                                <div class='col-sm-6 col-12 px-1'>
                                    <button id='normal' type='button' class='w-100 btn btn-md btn-primary mb-2'>Normal</button>
                                </div>
                            </div>
                            <div class='px-0 d-flex flex-wrap'>
                                <div class='col-sm-6 col-12 px-1'>
                                    <button id='complejo' type='button' class='btn btn-md btn-warning w-100 mb-2'>Complejo</button>
                                </div>
                                <div class='col-sm-6 col-12 px-1'>
                                    <button id='muycomplejo' type='button' class='btn btn-md btn-danger w-100 mb-2'>Muy complejo</button>
                                </div>
                            </div>
                            @else
                            <p class='text-center alert alert-info fs-6 m-0 mt-1 mb-3 p-0'>{{$errorDescription}}</p>
                            <p class='text-center text-lg text-dark fs-6 m-0 mb-1 p-0'>Datos del test:</p>
                            <p class='text-center fw-bold text-lg text-dark fs-6 m-0 mb-1 p-0'>Dificultad: {{$previousTest->difficulty}}</p>
                            <p class='text-center fw-bold text-lg text-dark fs-6 m-0 mb-1 p-0'>Categoría: {{$previousTest->category->name}}</p>
                            <p class='text-center fw-bold text-lg text-dark fs-6 m-0 mb-1 p-0'>Complejidad: {{$previousTest->complexity->name}}</p>
                            <div class='px-0 d-flex flex-wrap'>
                                <div class='col-sm-6 col-12 px-1'>
                                    <button id='continuar' type='button' class='btn btn-md btn-warning w-100 mb-2'>Iniciar</button>
                                </div>
                                <div class='col-sm-6 col-12 px-1'>
                                    <button id='cancelar' type='button' class='btn btn-md btn-danger w-100 mb-2'>Cancelar</button>
                                </div>
                            </div>                            @endif
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

        @if(!$previousTest)
            document.querySelector('#sencillo').addEventListener("click", event => {
                Swal.fire({title: 'Test sencillo',
                           confirmButtonText: 'Me animo!',
                           showCancelButton: true,
                           cancelButtonText: 'Mmh...mejor no!',
                           html: '<ul class="text-start">'+
                                 '<li>Ofrece 3 preguntas a responder.</li>'+
                                 '<li>Si hay mas de una respuesta correcta a cada pregunta, con responder una de ellas es suficiente para considerarla aprobada.</li>'+
                                 '<li>Se puede elegir la categoria de las preguntas.</li>'+
                                 '<li>Se puede elegir la complejidad de las preguntas.</li>'+
                                 '<li>Este test aprobado premia con <b>1 punto</b> a tu score.</li>'+
                                 '<li>Por cada acierto fácil <b>+1 punto</b> adicional.</li>'+
                                 '<li>Por cada acierto normal <b>+2 puntos</b> adicionales.</li>'+
                                 '<li>Por cada acierto difícil <b>+3 puntos</b> adicionales.</li>'+
                                 '<li class="text-success">Si no se acierta el 100% del test, se asignan de todas formas los premios de las preguntas acertadas.</li>'+
                                 '<li>Premio total: mínimo <b>4 puntos</b>, máximo <b>10 puntos</b>.</li>'+
                                 '</ul>'
                }).then((result) => {
                    if(result.isConfirmed){
                        location = '/initTest/1';
                    }
                });
            });
            document.querySelector('#normal').addEventListener("click", event => {
                Swal.fire({title: 'Test normal',
                           confirmButtonText: 'Me animo!',
                           showCancelButton: true,
                           cancelButtonText: 'Mmh...mejor no!',
                           html: '<ul class="text-start">'+
                                 '<li>Ofrece 5 preguntas a responder.</li>'+
                                 '<li>Todas las respuestas correctas deben ser respondidas.</li>'+
                                 '<li>Se puede elegir la categoria de las preguntas.</li>'+
                                 '<li>No se puede elegir la complejidad de las preguntas.</li>'+
                                 '<li>La complejidad se asigna automaticamente y puede ser: <b>fácil</b>, <b>normal</b>, o <b>difícil</b>.</li>'+
                                 '<li>Este test aprobado premia con <b>3 puntos</b> a tu score.</li>'+
                                 '<li>Por cada acierto fácil <b>+1 punto</b> adicional.</li>'+
                                 '<li>Por cada acierto normal <b>+2 puntos</b> adicionales.</li>'+
                                 '<li>Por cada acierto difícil <b>+4 puntos</b> adicionales.</li>'+
                                 '<li class="text-success">Si no se acierta el 100% del test, se asignan de todas formas los premios de las preguntas acertadas.</li>'+
                                 '<li>Premio total: mínimo <b>8 puntos</b>, máximo <b>23 puntos</b>.</li>'+
                                 '</ul>'
                }).then((result) => {
                    if(result.isConfirmed){
                        location = '/initTest/2';
                    }
                });
            });
            document.querySelector('#complejo').addEventListener("click", event => {
                Swal.fire({title: 'Test complejo',
                           confirmButtonText: 'Me animo!',
                           showCancelButton: true,
                           cancelButtonText: 'Mmh...mejor no!',
                           html: '<ul class="text-start">'+
                                 '<li>Ofrece 8 preguntas a responder.</li>'+
                                 '<li>Todas las respuestas correctas deben ser respondidas.</li>'+
                                 '<li>No se puede elegir la categoria ni la complejidad de las preguntas.</li>'+
                                 '<li>La complejidad se asigna automaticamente y puede ser: <b>normal</b>, o <b>difícil</b>.</li>'+
                                 '<li>Este test aprobado premia con <b>6 puntos</b> a tu score.</li>'+
                                 '<li>Por cada acierto normal <b>+3 puntos</b> adicionales.</li>'+
                                 '<li>Por cada acierto difícil <b>+5 puntos</b> adicionales.</li>'+
                                 '<li class="text-danger">Si no se acierta el 100% del test, se pierden los premios de las preguntas acertadas normales.</li>'+
                                 '<li>Premio total: mínimo <b>30 puntos</b>, máximo <b>46 puntos</b>.</li>'+
                                 '</ul>'
                }).then((result) => {
                    if(result.isConfirmed){
                        location = '/initTest/3';
                    }
                });
            });
            document.querySelector('#muycomplejo').addEventListener("click", event => {
                Swal.fire({title: 'Test muy complejo',
                           confirmButtonText: 'Me animo!',
                           showCancelButton: true,
                           cancelButtonText: 'Mmh...mejor no!',
                           html: '<ul class="text-start">'+
                                 '<li>Ofrece 12 preguntas a responder.</li>'+
                                 '<li>Todas las respuestas correctas deben ser respondidas.</li>'+
                                 '<li>No se puede elegir la categoria ni la complejidad de las preguntas.</li>'+
                                 '<li>La complejidad se asigna automaticamente en: <b>difícil</b>.</li>'+
                                 '<li>Este tipo de test premia con <b>12 puntos</b> a tu score.</li>'+
                                 '<li>Por cada acierto difícil <b>+6 puntos</b> adicionales.</li>'+
                                 '<li class="text-danger">Si no se acierta el 100% del test, se pierden todos los premios de las preguntas acertadas.</li>'+
                                 '<li>Premio total: <b>84 puntos</b>.</li>'+
                                 '</ul>'
                }).then((result) => {
                    if(result.isConfirmed){
                        location = '/initTest/4';
                    }
                });
            });
        @else
            document.querySelector('#continuar').addEventListener("click", event => {
                location = "/newTest/{{$previousTest->difficultyCode}}/{{$previousTest->category->code}}/{{$previousTest->complexity->code}}";
            });
            document.querySelector('#cancelar').addEventListener("click", event => {
                let urlToCall = "/test/disapprove/";
                let dataToSend = {
                    _token: '{{ csrf_token() }}',
                    id: {{$previousTest ? $previousTest->id : 0}}
                }
                fetch(urlToCall, {
                    method: 'POST',
                    body: JSON.stringify(dataToSend),
                    headers: {'Content-type': 'application/json'}
                }).then(res => res.json())
                .catch(error => {
                    //debugger;
                    Swal.fire({title: 'Error', html: 'Error al cancelar test'});
                })
                .then(response => {
                    if(response != undefined){
                        Swal.fire({title: 'Test cancelado', html: 'El test fue cancelado con éxito'});
                    }
                })
            })
        @endif
        </script>
    </body>
</html>
