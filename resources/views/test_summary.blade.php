<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Quizzer</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
        
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
                        <div class="d-flex flex-wrap justify-content-center">
                            <div class='{{$messageClass}} fw-bold alert alert-info p-2 mb-1'>
                                <span class=''>{{$message}}</span>
                            </div>
                            <p class='app-title col-12 text-center text-lg text-light m-0 my-2 d-flex flex-wrap'>
                                <span class='col-sm-12 col-12 pe-sm-2 p-0'>Resumen</span>
                            </p>

                            <div class='col-10'>
                                @foreach($testSummary as $number => $response)
                                <div class='alert alert-primary d-flex p-0 pe-2 mt-2 m-0 justify-content-between'>
                                    <div class='text-start p-1 px-4 m-0 fw-bold'>
                                        PREGUNTA {{$number}}
                                    </div>
                                    <div class='d-flex align-items-center text-start m-0 fw-bold'>
                                    @if($response->question->complexity == 1)
                                        <span class='badge bg-success px-2 py-1 fs-tiny me-1' title='Complejidad fácil'><i class='fa fa-thumbs-up'></i></span>
                                    @endif
                                    @if($response->question->complexity == 2)
                                        <span class='badge bg-danger px-2 py-1 fs-tiny me-1' title='Complejidad normal'><i class='fa fa-exclamation'></i></span>
                                    @endif
                                    @if($response->question->complexity == 3)
                                        <span class='badge bg-danger px-2 py-1 fs-tiny me-1' title='Complejidad difícil'><i class='fa fa-exclamation'></i><i class='fa fa-exclamation'></i></span>
                                    @endif
                                    @if($response->question->answered_ok)
                                        @if($response->question->ellapsed_percent < 25)
                                        <span class='badge bg-dark text-light fs-tiny px-1 py-1 me-1' title='Respondida antes del 25% del tiempo'><i class='fa fa-truck-fast me-1'></i>25%</span>
                                        @elseif($response->question->ellapsed_percent < 50)
                                        <span class='badge bg-danger text-light fs-tiny px-1 py-1 me-1' title='Respondida antes del 50% del tiempo'><i class='fa fa-truck-fast me-1'></i>50%</span>
                                        @elseif($response->question->ellapsed_percent < 75)
                                        <span class='badge bg-primary text-light fs-tiny px-1 py-1 me-1' title='Respondida antes del 75% del tiempo'><i class='fa fa-truck-fast me-1'></i>75%</span>
                                        @endif
                                    @endif
                                        <span class='badge bg-{{$response->question->answered_ok ? "success" : "danger"}}'>{{$response->question->answered_ok ? "APROBADA" : "REPROBADA"}}</span>
                                    </div>
                                </div>
                                <div class='col-12 alert alert-info text-start p-1 px-4 m-0 fw-bold'>{{$response->question->description}}</div>
                                <div class='col-12 bg-gray-200 text-start p-1 px-4 m-0 mt-1 fw-bold fs-tiny'>Respondiste en: {{$response->question->ellapsed_time}} segs. ({{$response->question->ellapsed_percent}}%)</div>
                                <div class='col-12 alert alert-primary text-start p-1 px-4 mt-1 m-0 fw-bold'>RESPUESTA/S</div>
                                    @foreach($response->answers as $key => $answer)
                                <div class='d-flex flex-wrap col-12 {{$answer->is_correct ? 'alert-success' : ($answer->selected ? 'alert-danger' : 'alert-light')}} respuesta alert text-start p-1 px-4 m-0 fw-bold'>
                                    <!--input type='checkbox' disabled {{$answer->selected ? 'checked' : ''}}>&nbsp;{{$answer->description}}-->
                                    <span class='col-12'><i class='fw-bold {{$answer->selected && $answer->is_correct ? 'me-2 fa fa-check text-success' : ($answer->selected ? 'me-2 fa fa-times text-danger' : '')}}'></i>{{++$key}})&nbsp;{{$answer->description}}</span>
                                    @if($answer->selected && $answer->is_correct)
                                    <span class='col-12 m-0' style='font-size:.5rem'>Esta respuesta era correcta y la seleccionaste</span>
                                    @endif
                                    @if(!$answer->selected && $answer->is_correct)
                                    <span class='col-12 m-0' style='font-size:.5rem'>Esta respuesta era correcta y <span class='text-danger'>no la seleccionaste</span>{{!$testSetting->allTrueAnswersRequired && $response->aCorrectAnswerSelected ? ' (No afecta si elegiste otra también correcta)' : ''}}</span>
                                    @endif
                                    @if($answer->selected && !$answer->is_correct)
                                    <span class='col-12 m-0 text-danger' style='font-size:.5rem'>Esta respuesta era incorrecta y la seleccionaste</span>
                                    @endif
                                </div>
                                    @endforeach
                                @endforeach
                            </div>
                            <div class='col-10'>
                                <div class='alert alert-warning d-flex p-0 pe-2 mt-2 m-0 justify-content-between'>
                                    <div class='text-start p-1 px-4 m-0 fw-bold'>
                                        Informes, premios y penalizaciones
                                    </div>
                                </div>
                                @foreach($bonuses as $bonus)
                                <div class='alert alert-light d-flex p-0 mt-1 m-0 justify-content-between'>
                                    @if($bonus->type == 'Scoring')
                                    <div class='text-light bg-{{$bonus->score > 0 ? "success" : "danger"}} border fw-bold px-4'>
                                        {{$bonus->score > 0 ? "+".$bonus->score : $bonus->score}}
                                    </div>
                                    @endif
                                    <div class='{{isset($bonus->class) ? $bonus->class : "alert-success"}} flex-fill border px-4 fs-small d-flex justify-content-center align-items-center'>
                                        {{$bonus->description}}
                                    </div>
                                </div>
                                @endforeach
                                <div class='alert alert-dark border d-flex p-0 mt-1 m-0 justify-content-between'>
                                    <div class='text-light bg-dark border fw-bold px-4'>
                                        {{$totalScore*1 > 0 ? "+$totalScore" : "$totalScore"}}
                                    </div>
                                    <div class='alert-success flex-fill border px-4'>
                                        Total puntaje ganado.
                                    </div>
                                </div>
                                
                            </div>
                            <div class="pb-0 mt-2">
                                <div class='px-0 d-flex flex-wrap justify-content-center'>
                                    <button id='nuevo' type='button' class='btn btn-md btn-success'>Nuevo test</button>
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

            document.querySelector('#nuevo').addEventListener("click", event => {
                location = '/createTest'
            });

        </script>
    </body>
</html>
