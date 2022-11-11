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
        <link rel="stylesheet" href="{{ asset('css/grid.css') }}"/>
        <link rel="stylesheet" href="{{ asset('css/login.css') }}"/>
        <style>
            .logo {
                background: url("{{ asset('images/logo.png') }}");
            }
            ._table {
                display: table;
            }
            ._table-row {
                display: table-row;
            }
            ._table-cell {
                display: table-cell;
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
                        <a href='/home'><div class="logo"></div></a>
                        <div class="">
                            <p class='app-title text-center text-lg text-light m-0'>Ránking de jugadores</p>
                        </div>
                        <div class="_table pb-0 w-100">
                            <div class='_table-row bg-dark text-light'>
                                <div class='_table-cell border border-1 px-1'>#</div>
                                <div class='_table-cell border border-1 px-1'>Ptos</div>
                                <div class='_table-cell border border-1 px-1'>Mejor racha</div>
                                <div class='_table-cell border border-1 px-1'>Peor racha</div>
                                <div class='_table-cell border border-1 px-1'>Jugador</div>
                            </div>
                            @for($i = 0; $i < count($users); $i++)
                            <div class='_table-row bg-dark text-light'>
                                <div class='_table-cell border border-1 bg-success-light px-1'>{{$i+1}}</div>
                                <div class='_table-cell border border-1 bg-success-light px-1'>{{$users[$i]->score}}</div>
                                <div class='_table-cell border border-1 bg-success-light px-1'>{{$users[$i]->winning_streak}}</div>
                                <div class='_table-cell border border-1 bg-success-light px-1'>{{$users[$i]->losing_streak}}</div>
                                <div class='_table-cell border border-1 bg-success-light px-1'>{{$users[$i]->name}}</div>
                            </div>
                            @endfor
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
        
            document.querySelector('#sencillo').addEventListener("click", event => {
                Swal.fire({title: 'Test sencillo',
                           confirmButtonText: 'Me animo!',
                           showCancelButton: true,
                           cancelButtonText: 'Mmh...mejor no!',
                           html: '<ul class="text-start">'+
                                 '<li>Ofrece 3 preguntas a responder.</li>'+
                                 '<li>Si hay mas de una respuesta correcta a la pregunta, con responder una de ellas es suficiente para considerarla aprobada.</li>'+
                                 '<li>Se puede elegir la categoria.</li>'+
                                 '<li>Se puede elegir la complejidad.</li>'+
                                 '</ul>'
                });
            });

        </script>
    </body>
</html>
