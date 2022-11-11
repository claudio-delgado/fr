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
                        <div class='error d-none alert alert-danger m-0 py-1' role='alert'>
                            <span id='text'>Mensaje de error</span>
                        </div>
                        <a href='/home'><div class="logo"></div></a>
                        <div class="">
                            <p class='app-title text-center text-lg text-light m-0 mb-2 d-flex flex-wrap'>
                                <span class='col-sm-6 col-12 text-sm-end pe-sm-2 p-0'>Nuevo test</span>
                                <span class='col-sm-6 col-12 text-sm-start text-dark fw-bold ps-sm-2 p-0'>{{$testSetting->difficultyName}}</span>
                            </p>
                            <p class='text-center fw-bold text-sm text-dark fs-6 m-0 mb-1 p-0'>
                                En este tipo de test
                                @if(!$testSetting->categorySelectionAllowed && !$testSetting->complexitySelectionAllowed)
                                no
                                @endif
                                podés:</p>
                            <div class='text-center text-sm text-dark fs-6 m-0 mb-0 p-0 d-flex flex-wrap justify-content-center'>
                                <p class='col-6 d-flex flex-wrap'>
                                    @if($testSetting->categorySelectionAllowed)
                                    <select id='categoria' class='w-100 form-select mb-2 text-nowrap'>
                                        <option value='0'>Seleccionar categoría</option>
                                        @foreach($categories as $category)
                                        <option value='{{$category->code}}'>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                    @if($testSetting->complexitySelectionAllowed)
                                    <select id='complejidad' class='w-100 form-select mb-2 text-nowrap'>
                                        <option value='0'>Seleccionar complejidad</option>
                                        @foreach($complexities as $complexity)
                                        <option value='{{$complexity->code}}'>{{$complexity->name}}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                    @if(!$testSetting->categorySelectionAllowed && !$testSetting->complexitySelectionAllowed)
                                    <span class='col-12'>Seleccionar la&nbsp;<b>categoría</b>,</span>
                                    <span class='col-12'>ni la&nbsp;<b>complejidad</b>.</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="pb-0">
                            <div class='px-0 d-flex flex-wrap justify-content-center'>
                                <button id='nuevo' type='button' class='btn btn-md btn-success'>Comenzar</button>
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
                let categoria = $("#categoria").length ? $("#categoria").val() : "0";
                let complejidad = $("#complejidad").length ? $("#complejidad").val() : "0";
                location = "/newTest/{{$testSetting->difficultyCode}}/"+categoria+"/"+complejidad;
            });

        </script>
    </body>
</html>
