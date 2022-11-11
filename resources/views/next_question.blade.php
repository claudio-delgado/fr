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
                        <div class="d-flex flex-wrap justify-content-center">
                            <p class='app-title col-12 text-center text-lg text-light m-0 mb-2 d-flex flex-wrap'>
                                <span class='col-sm-6 col-12 text-sm-end pe-sm-2 p-0'>Nuevo test</span>
                                <span class='col-sm-6 col-12 text-sm-start text-dark fw-bold ps-sm-2 p-0'>{{$difficultyName}}</span>
                            </p>
                            <div class='col-5 fs-7 fw-bold alert alert-info p-2 mb-1'>
                                <span class='questionTime'>{{$question->questionTime}}</span> segs.
                            </div>
                            <div class='col-12 d-flex justify-content-center mb-1'>
                                <progress class='progress col-10 border border-1 border-dark bg-light' value='0' max='100'>0%</progress>
                            </div>
                            <div class='col-10'>
                                <div class='col-12 alert alert-primary text-start p-1 px-4 m-0 fw-bold'>PREGUNTA {{$test->questionsAnswered+1}}/{{$test->questionsRequired}}</div>
                                <div class='col-12 alert alert-info text-start p-1 px-4 m-0 fw-bold'>{{$question->description}}</div>
                                <div id='respuestasTitulo' class='col-12 d-none alert alert-primary text-start p-1 px-4 m-0 fw-bold'>RESPUESTA/S</div>
                                @foreach($answers as $answer)
                                <div class='col-12 d-none respuesta alert alert-secondary text-start p-1 px-4 m-0 fw-bold'>
                                    <input id='answer{{$answer->answer_id}}' type='checkbox'>&nbsp;{{$answer->description}}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="pb-0 mt-1">
                            <div class='px-0 d-flex flex-wrap justify-content-center'>
                                <button id='respuestas' type='button' class='btn btn-md btn-primary'>Continuar en <span class='questionTime'>{{$question->questionTime}}</span></button>
                                <button id='confirmar' type='button' class='d-none btn btn-md btn-success'>Confirmar</button>
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
            let questionTime = document.querySelector('.questionTime').innerHTML;
            let timer = 1;
            let totaltime;
            totaltime = setInterval(reduceTime, 10);
            function reduceTime() {
                let currentTime = document.querySelector('.questionTime').innerHTML;
                let watches = document.querySelectorAll('.questionTime');
                watches.forEach(watch => {
                    watch.innerHTML = (Math.round((currentTime - .01) * 100) / 100).toFixed(2).toString().padStart(5, '0');
                })
                let ellapsedPercent = Math.round(((questionTime - currentTime) / questionTime) * 100);
                let progress = document.querySelector('progress');
                progress.setAttribute('value', ellapsedPercent);
                if(ellapsedPercent > 35 && progress.classList.contains('progress')){
                    progress.classList.remove('progress');
                    progress.classList.add('progress-warning');
                }
                if(ellapsedPercent > 55 && progress.classList.contains('progress-warning')){
                    progress.classList.remove('progress-warning');
                    progress.classList.add('progress-caution');
                }
                if(ellapsedPercent > 77 && progress.classList.contains('progress-caution')){
                    progress.classList.remove('progress-caution');
                    progress.classList.add('progress-critical');
                }
                if(currentTime <= 0){
                    clearInterval(totaltime);
                    totaltime = null;
                    document.querySelector('.questionTime').innerHTML = '0';
                    if(timer == 1){ //Finalizó el tiempo de mostrar pregunta sin respuestas.
                        document.getElementById('respuestas').remove();
                        document.querySelector('.questionTime').innerHTML = '{{$question->time}}';
                        document.getElementById('confirmar').classList.remove('d-none');
                        document.getElementById('respuestasTitulo').classList.remove('d-none');
                        let answers = document.querySelectorAll('.respuesta');
                        answers.forEach(answer => {
                            answer.classList.remove('d-none');
                        })
                        //Volver a activar el timer para mostrar respuestas.
                        progress.classList.add('progress');
                        progress.classList.remove('progress-critical');
                        progress.setAttribute('value', 0);
                        timer = 2;
                        questionTime = document.querySelector('.questionTime').innerHTML;
                        totaltime = setInterval(reduceTime, 10);
                    } else {
                        //Tiempo concluído => Debe darse por desaprobada la pregunta.
                        document.getElementById('confirmar').remove();
                        let urlToCall = "/test/expireQuestion/{{$question->id}}";
                        let dataToSend = {
                            _token: '{{ csrf_token() }}',
                            id: {{$test->id}}
                        }
                        fetch(urlToCall, {
                            method: 'POST',
                            body: JSON.stringify(dataToSend),
                            headers: {'Content-type': 'application/json'}
                        }).then(res => res.json())
                        .catch(error => {
                            //debugger;
                            Swal.fire({title: 'Error', html: 'Error al confirmar respuestas'});
                        })
                        .then(response => {
                            if(response != undefined){
                                //debugger;
                                Swal.fire({title: 'Oh no!', html: 'No llegaste a contestar y se acabó el tiempo.'})
                                    .then(function(){
                                    location.reload();
                                });
                            }
                        })
                    }
                }
            }

            document.querySelector('#confirmar').addEventListener("click", event => {
                //Registrar respuestas elegidas para la pregunta y
                //corroborar si es correcta la elección para registrarla en test_logs para el test actual.
                document.getElementById('confirmar').remove();
                //Obtener respuestas elegidas
                let selectedAnswers = [];
                var checkedInputs = document.querySelectorAll("input[type='checkbox']:checked");
                checkedInputs.forEach(input => {
                    selectedAnswers.push(input.id.replace('answer',''));
                })
                let urlToCall = "/test/processQuestion/{{$question->id}}";
                let dataToSend = {
                    _token: '{{ csrf_token() }}',
                    id: {{$test->id}},
                    selectedAnswers: selectedAnswers,
                    remainingTime: document.querySelector('.questionTime').innerHTML
                }
                fetch(urlToCall, {
                    method: 'POST',
                    body: JSON.stringify(dataToSend),
                    headers: {'Content-type': 'application/json'}
                }).then(res => res.json())
                .catch(error => {
                    //debugger;
                    Swal.fire({title: 'Error', html: 'Error al confirmar respuestas'});
                })
                .then(response => {
                    if(response != undefined){
                        //Si aún restan preguntas => continuar con una nueva. 
                        //debugger;
                        /*Swal.fire({title: 'Genial!', html: 'Lograste contestar antes de finalizar el tiempo.'})
                            .then(function(){*/
                            location.reload();
                        //});
                    }
                })
            });

            document.querySelector('#respuestas').addEventListener("click", event => {
                //Cancelar timer 1 (tiempo de pregunta) y habilitar el timer 2 (tiempo de respuestas)
                //debugger;
                clearInterval(totaltime);
                totaltime = null;
                document.getElementById('respuestas').remove();
                document.querySelector('.questionTime').innerHTML = '{{$question->time}}';
                questionTime = document.querySelector('.questionTime').innerHTML;
                document.getElementById('confirmar').classList.remove('d-none');
                document.getElementById('respuestasTitulo').classList.remove('d-none');
                let answers = document.querySelectorAll('.respuesta');
                answers.forEach(answer => {
                    answer.classList.remove('d-none');
                })
                let progress = document.querySelector('progress');
                progress.classList.add('progress');
                progress.classList.remove('progress-critical');
                progress.classList.remove('progress-warning');
                progress.classList.remove('progress-caution');
                progress.setAttribute('value', 0);
                timer = 2;
                totaltime = setInterval(reduceTime, 10);
            });

        </script>
    </body>
</html>
