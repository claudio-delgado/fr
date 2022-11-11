<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\User;
use App\Models\Question;
use App\Models\Bonus;
use App\Models\Answer;
use App\Models\QuestionAnswer;
use App\Models\TestLog;
use stdClass;

class TestController extends Controller
{
    public $settings;
    private $test;
    private $testFinished = false; //true => Si el test se evaluó y se persistió su status en la base.
    private $player;
    private $testSettings;
    private $questionsPicked = [];
    
    public function settings(){
        $categories = [];
        if(file_exists(storage_path("category_config.json"))){
            $categories = json_decode(file_get_contents(storage_path("category_config.json")));
        }
        $complexities = [];
        if(file_exists(storage_path("complexity_config.json"))){
            $complexities = json_decode(file_get_contents(storage_path("complexity_config.json")));
        }
        if(file_exists(storage_path("test_config.json"))){
            $this->settings = json_decode(file_get_contents(storage_path("test_config.json")));
            $this->settings['categories'] = $categories;
            $this->settings['complexities'] = $complexities;
            return true;
        } else {
            return false;
        }
    }

    private function getCategoryByCode($categoryCode){
        foreach($this->settings['categories'] as $index => $aCategory){
            if($aCategory->code == $categoryCode){
                return $aCategory;
            }
        }
        return false;
    }
    private function getComplexityByCode($complexityCode){
        foreach($this->settings['complexities'] as $index => $aComplexity){
            if($aComplexity->code == $complexityCode){
                return $aComplexity;
            }
        }
        return false;
    }
    private function getDifficultyByCode($difficultyCode){
        foreach($this->settings as $setting){
            if($setting->difficultyCode == $difficultyCode){
                return $setting;
            }
        }
        return false;
    }
    private function getTestCategory(){
        if($this->test->category){
            return $this->test->category;
        } else {
            $categories = $this->settings['categories'];
            return floor(rand(1, count($categories)));
        }
    }
    private function getTestComplexity(){
        if($this->test->complexity < 4){
            return $this->test->complexity;
        } else {
            $complexities = $this->settings['complexities'];
            return floor(rand(1, count($complexities)));
        }
    }
    private function getTestSetting(){
        $testSetting = new stdClass;
        foreach($this->settings as $setting){
            if($setting->difficultyCode == $this->test->difficulty){
                return $setting;
            }
        }
        return false;
    }
    private function processQuestion_answers($question, $answers){
        //Obtener si ya se ha respondido alguna pregunta.
        $previousTestLog = TestLog::where('test_id', $this->test->id)
                                    ->where('ellapsed_time', 0)
                                    ->first();
        if(!$previousTestLog){
            //Registrar primer pregunta del test.
            $testLog = new TestLog;
            $testLog->test_id = $this->test->id;
            $testLog->ellapsed_time = 0;
            $testLog->question_id = $question->id;
            $testLog->answered_ok = null;
            $testLog->created_at = Date('Y/m/d H:i:s');
            $testLog->updated_at = null;
            $testLog->save();
            $questionExpired = false;
        } else { 
            //Se hizo refresh o no es la primera vez que se está accediendo a la pantalla de la pregunta
            $previousTestLog->question_id = $question->id;
            $previousTestLog->save();
            //Chequear que no haya caducado el tiempo.
            $questionCreatedAt = strtotime($previousTestLog->created_at);
            $currentDatetime = strtotime(Date('Y/m/d H:i:s'));
            $questionTotalTime = $question->time*1;
            //Analizar vigencia de la pregunta en este test
            $questionExpired = $currentDatetime - $questionCreatedAt > $questionTotalTime;
        }
        //¿La pregunta no caducó?
        if(!$questionExpired){
            //Pasar datos a la vista.
            $question->questionTime = round(strlen($question->description) / 10, 0);
            $difficulty = $this->getDifficultyByCode($this->test->difficulty);
            return view('next_question', array(
                'test' => $this->test,
                'difficultyName' => $difficulty->difficultyName, 
                'question' => $question, 
                'answers' => $answers
            ));
        } else {
            echo "Pregunta caducada para este test.";
        }
    }
    private function processQuestion_question($question){
        //Obtener respuestas posibles para la pregunta obtenida.
        $answers = QuestionAnswer::where('question_id', $question->id)
                                    ->join('answers', 'answer_id', '=', 'answers.id')
                                    ->select('question_answers.id', 'question_id', 'answer_id', 
                                            'is_correct', 'answers.description')
                                    ->inRandomOrder()
                                    ->get();
        //echo "<pre>"; var_dump($answers); die();
        if($answers){
            return $this->processQuestion_answers($question, $answers);
        } else { //Error al obtener respuestas de la pregunta.
            echo "<pre>No pudo obtenerse respuestas a la pregunta obtenida para este test.<br/>";
            echo "question_id: $question->id.<br/>";
            var_dump($answers);
        }
    }
    private function winningTestBonus(){
        $bonusData = Bonus::where('difficulty', $this->test->difficulty)
                                ->where('event', 'all_answers_ok')->first();
        $bonus = new stdClass;
        $bonus->type = 'Scoring';
        $bonus->description = $bonusData->description;
        $bonus->score = $bonusData->score_bonus;
        return $bonus;
    }
    private function winningStreakReport(){
        $bonus = new stdClass;
        $bonus->type = 'Report';
        $bonus->class = 'alert-success text-success fw-bold';
        return $bonus;
    }
    private function winningStreak3Bonus(){
        $bonusData = Bonus::where('event', 'winning_streak_reached')
                            ->where('event_value', '3')->first();
        $bonus = new stdClass;
        $bonus->type = 'Scoring';
        $bonus->description = $bonusData->description;
        $bonus->class='alert-success text-success fw-bold';
        $bonus->score = $bonusData->score_bonus;
        return $bonus;
    }
    private function winningStreak5Bonus(){
        $bonusData = Bonus::where('event', 'winning_streak_reached')
                            ->where('event_value', '5')->first();
        $bonus = new stdClass;
        $bonus->type = 'Scoring';
        $bonus->description = $bonusData->description;
        $bonus->class='alert-success text-success fw-bold';
        $bonus->score = $bonusData->score_bonus;
        return $bonus;
    }
    private function losingTestPenality(){
        $bonusData = Bonus::where('difficulty', $this->test->difficulty)
                            ->where('event', 'failed_test')->first();
        $bonus = new stdClass;
        $bonus->type = 'Scoring';
        $bonus->description = $bonusData->description;
        $bonus->score = $bonusData->score_bonus;
        $bonus->class='alert-danger text-danger fw-bold';
        return $bonus;
    }
    private function losingStreakReport(){
        $bonus = new stdClass;
        $bonus->type = 'Report';
        $bonus->class = 'alert-danger text-danger fw-bold';
        return $bonus;
    }
    private function losingStreak3Penality(){
        $bonusData = Bonus::where('event', 'losing_streak_reached')
                                ->where('event_value', '3')->first();
        $bonus = new stdClass;
        $bonus->type = 'Scoring';
        $bonus->description = $bonusData->description;
        $bonus->class='alert-danger text-danger fw-bold';
        $bonus->score = $bonusData->score_bonus;
        return $bonus;
    }
    private function losingStreak5Penality(){
        $bonusData = Bonus::where('event', 'losing_streak_reached')
                                ->where('event_value', '5')->first();
        $bonus = new stdClass;
        $bonus->type = 'Scoring';
        $bonus->description = $bonusData->description;
        $bonus->class='alert-danger text-danger fw-bold';
        $bonus->score = $bonusData->score_bonus;
        return $bonus;
    }
    private function winningTestBonuses(){
        //Premio por aprobar test.
        $bonus = $this->winningTestBonus();
        $bonuses[] = $bonus;
        $totalBonusesScore = $bonus->score;
        //Reporte por actualizar racha ganadora.
        $bonus = $this->winningStreakReport();
        $streakText = (!$this->player->winning_streak ? "se estableció en " : "creció a ").($this->player->winning_streak + 1);
        $this->player->winning_streak++;
        $this->player->losing_streak = 0;
        $bonus->description = "Tu racha ganadora $streakText";
        $bonuses[] = $bonus;
        //Si es la mejor racha ganadora, actualizarla en el usuario.
        $this->player->best_winning_streak = $this->player->winning_streak > $this->player->best_winning_streak ? $this->player->winning_streak : $this->player->best_winning_streak; 
        if($this->player->winning_streak && !($this->player->winning_streak % 3)){
            //Premio por acumular 3 tests aprobados consecutivos.
            $bonus = $this->winningStreak3Bonus();
            $bonuses[] = $bonus;
            $totalBonusesScore+= $bonus->score;
        }
        if($this->player->winning_streak && !($this->player->winning_streak % 5)){
            //Premio por acumular 5 tests aprobados consecutivos.
            $bonus = $this->winningStreak5Bonus();
            $bonuses[] = $bonus;
            $totalBonusesScore+= $bonus->score;
        }
        $response = new stdClass;
        $response->bonuses = $bonuses;
        $response->totalBonusesScore = $totalBonusesScore;
        return $response;
    }
    private function losingTestPenalities(){
        //Penalización por desaprobar test.
        $bonus = $this->losingTestPenality();
        $bonuses[] = $bonus;
        $totalPenalitiesScore = $bonus->score;
        //Reporte por actualizar racha perdedora.
        $bonus = $this->losingStreakReport();
        $streakText = (!$this->player->losing_streak ? "se estableció en " : "creció a ").($this->player->losing_streak + 1);
        $this->player->losing_streak++;
        $this->player->winning_streak = 0;
        $bonus->description = "Tu racha perdedora $streakText";
        $bonuses[] = $bonus;
        //Si es la peor racha perdedora, actualizarla en el usuario.
        $this->player->worst_losing_streak = $this->player->losing_streak > $this->player->worst_losing_streak ? $this->player->losing_streak : $this->player->worst_losing_streak; 
        if($this->player->losing_streak && !($this->player->losing_streak % 3)){
            //Penalización por acumular 3 tests desaprobados consecutivos.
            $bonus = $this->losingStreak3Penality();
            $bonuses[] = $bonus;
            $totalPenalitiesScore+= $bonus->score;
        }
        if($this->player->losing_streak && !($this->player->losing_streak % 5)){
            //Premio por acumular 5 tests desaprobados consecutivos.
            $bonus = $this->losingStreak5Penality();
            $bonuses[] = $bonus;
            $totalPenalitiesScore+= $bonus->score;
        }
        $response = new stdClass;
        $response->bonuses = $bonuses;
        $response->totalPenalitiesScore = $totalPenalitiesScore;
        return $response;
    }
    private function calculateTestBonuses(){
        $response = new stdClass;
        $totalBonusScore = 0;
        $bonuses = array();
        //¿Test aprobado?
        if($this->test->status == 2){
            $bonusResponse = $this->winningTestBonuses();
            $bonuses = $bonusResponse->bonuses;
            $totalBonusScore = $bonusResponse->totalBonusesScore;
        }
        //¿Test desaprobado?
        if($this->test->status == 3){
            $bonusResponse = $this->losingTestPenalities();
            $bonuses = $bonusResponse->bonuses;
            $totalBonusScore = $bonusResponse->totalPenalitiesScore;
        }
        $response->bonuses = $bonuses;
        $response->totalBonusScore = $totalBonusScore;
        
        return $response;
    }
    private function calculateQuestionBonuses($enableCorrectQuestionBonus, $questionNumber, $testLogRow){
        $response = new stdClass;
        $bonuses = array();
        $totalBonusScore = 0;
        //¿Se puede generar bonus por PREGUNTA CORRECTA?...
        if($enableCorrectQuestionBonus){
            $bonusData = Bonus::where('complexity', $testLogRow->complexity)
                                ->where('difficulty', $this->test->difficulty)->first();
            $bonus = new stdClass;
            $bonus->type = 'Scoring';
            $bonus->score = ($bonusData ? $bonusData->score_bonus: 0);
            $tokens = ['<questionNumber>'];
            $bonus->description = str_replace($tokens, $questionNumber, ($bonusData ? $bonusData->description: ""));
            //echo "<br>".$bonus->description;
            $bonuses[] = $bonus;
            $totalBonusScore+= $bonus->score;
            
            //Evaluar si corresponde bonus por responder correcta y rápidamente.
            if($testLogRow->answered_ok && $testLogRow->ellapsed_percent <= 75){
                $bonus = new stdClass;
                $bonus->type = 'Scoring';
                $bonus->time = $testLogRow->ellapsed_percent;
                $percent = 0;
                if($testLogRow->ellapsed_percent <= 25){
                    $percent = 25;
                } else {
                    if($testLogRow->ellapsed_percent <= 50){
                        $percent = 50;
                    } else {
                        if($testLogRow->ellapsed_percent <= 75){
                            $percent = 75;
                        }
                    }
                }
                $bonusData = Bonus::where('complexity', $testLogRow->complexity)
                                    ->where('event_value', $percent)->first();
                $bonus->score = $bonusData->score_bonus;
                $bonus->description = $bonusData->description;
                //echo "<br>".$bonus->description."(".$testLogRow->ellapsed_percent.")";
                $bonuses[] = $bonus;
                $totalBonusScore+=$bonus->score;
            }
        }
        
        $response->bonuses = $bonuses;
        $response->totalBonusScore = $totalBonusScore;
        return $response;
    }
    private function setSummaryQuestionData($questionId, $testLogRow){
        $testSummary = new stdClass;
        $testSummary->question = new stdClass;
        $testSummary->question->id = $questionId;
        $testSummary->question->description = $testLogRow->question;
        $testSummary->question->complexity = $testLogRow->complexity;
        $testSummary->question->ellapsed_time = $testLogRow->ellapsed_time;
        $testSummary->question->time = $testLogRow->time;
        $testSummary->question->ellapsed_percent = round(($testLogRow->ellapsed_time / $testLogRow->time) * 100, 2);
        $testSummary->question->answered_ok = $testLogRow->answered_ok;
        $testSummary->answers = array();
        return $testSummary;
    }
    private function getDBTestSummaryData(){
        //Obtener datos para el resumen.
        $testLogRows = TestLog::where('test_id', $this->test->id)
                                ->where('ellapsed_time', ">", 0)
                                ->join('questions', 'test_logs.question_id', '=', 'questions.id')
                                ->join('question_answers', 'test_logs.question_id', '=', 'question_answers.question_id')
                                ->join('answers', 'question_answers.answer_id', '=', 'answers.id')
                                ->select('test_logs.id AS id_test_logs', 
                                        'test_logs.test_id', 
                                        'test_logs.question_id',
                                        'test_logs.ellapsed_time',
                                        'test_logs.answered_ok',
                                        'test_logs.answers_selected',
                                        'test_logs.created_at',
                                        'questions.description AS question',
                                        'questions.complexity AS complexity',
                                        'questions.time AS time',
                                        'answers.id AS answer_id',
                                        'answers.description AS answer',
                                        'question_answers.is_correct')
                                ->get();
        return $testLogRows;
    }
    private function testSummaryLevels(){
        $questionNumber = 0;
        $last_question_id = 0;
        $totalScore = 0;
        $questionBonuses = array();
        $testLogRows = $this->getDBTestSummaryData();
        $testSummary = array();
        foreach($testLogRows as $testLogRow){
            $selected_answers = explode(",", $testLogRow->answers_selected);
            if($last_question_id != $testLogRow->question_id){
                $last_question_id = $testLogRow->question_id;
                $questionNumber++;
                //Obtener datos de la complejidad de la pregunta.
                $complexity = $this->getComplexityByCode($testLogRow->complexity);
                $testSummary[$questionNumber] = new stdClass;
                $testSummary[$questionNumber] = $this->setSummaryQuestionData($last_question_id, $testLogRow);
                //Calcular porcentaje de tiempo empleado al contestar.
                $testLogRow->ellapsed_percent = round(($testLogRow->ellapsed_time / $testLogRow->time) * 100, 2);
                $enableCorrectQuestionBonus = $this->testSettings->gainAnswerScoreIfFailedTest == 'all'
                                                || ($complexity->name == $this->testSettings->gainAnswerScoreIfFailedTest);
                //Si la pregunta actual se respondió ok...
                if($testLogRow->answered_ok){
                    $bonusResponse = $this->calculateQuestionBonuses($enableCorrectQuestionBonus, $questionNumber, $testLogRow);
                    $questionBonuses = array_merge($questionBonuses, $bonusResponse->bonuses);
                    $totalScore+= $bonusResponse->totalBonusScore;
                } else {
                }
                $testSummary[$questionNumber]->aCorrectAnswerSelected = null;
            }
            //Cargar datos de respuesta actual en la iteración (si es correcta, si fue seleccionada, etc.)
            $answer = new stdClass;
            $answer->id = $testLogRow->answer_id;
            $answer->description = $testLogRow->answer;
            $answer->is_correct = $testLogRow->is_correct;
            $answer->selected = in_array($answer->id, $selected_answers);
            $testSummary[$questionNumber]->answers[] = $answer;
            //Si al menos una respuesta correcta fue seleccionada, este flag queda true.
            $testSummary[$questionNumber]->aCorrectAnswerSelected =
                $testSummary[$questionNumber]->aCorrectAnswerSelected ||
                ($answer->is_correct && $answer->selected);
            /*
            echo "<br>questionNumber = $questionNumber, question_id = $last_question_id";
            echo "<br>".$testLogRow->question;
            echo "<br>-------------------------------------------------";
            echo "<br>".$answer->description;
            echo "<br>answer->is_correct = ".($answer->is_correct ? '1' : '0');
            echo "<br>answer->selected = ".($answer->selected ? '1' : '0');
            echo "<br>testSummary[$questionNumber]->aCorrectAnswerSelected = ".($testSummary[$questionNumber]->aCorrectAnswerSelected ? '1' : '0');
            echo "<br>=================================================";
            */
        }
        $response = new stdClass;
        $response->summary = $testSummary;
        $response->totalScore = $totalScore;
        $response->questionBonuses = $questionBonuses;
        return $response;
    }
    private function testSummary(){
        //echo "<pre>";
        $bonuses = array();
        $testBonuses = array();
        //Adaptar estructura de preguntas y respuestas elegidas, en niveles.
        $summaryLevelsResponse = $this->testSummaryLevels();
        $testSummary = $summaryLevelsResponse->summary;
        $totalScore = $summaryLevelsResponse->totalScore;
        $questionBonuses = $summaryLevelsResponse->questionBonuses;
        //Obtener bonuses y penalizaciones del test.
        $testBonusesResponse = $this->calculateTestBonuses();
        $testBonuses = $testBonusesResponse->bonuses;
        
        //Juntar bonuses y penalizaciones del test con los de las preguntas.
        $bonuses = array_merge($testBonuses, $questionBonuses);
        //echo "<pre>"; var_dump($bonuses); die();
        //Acumular puntaje de bonuses del test con los ya acumulados de las preguntas.
        $totalScore+= $testBonusesResponse->totalBonusScore;

        //Actualizar score del usuario en memoria.
        $this->player->score+= $this->player->score + $totalScore > 0 ? $totalScore : 0;
        $this->player->updated_at = Date('Y-m-d H:i:s');
        //Actualizar score del test en memoria.
        $this->test->score_obtained = $totalScore;

        //Pasar al resumen final.
        return view('test_summary', array(
            'test' => $this->test,
            'difficulty' => $this->getDifficultyByCode($this->test->difficulty),
            'testSetting' => $this->testSettings, 
            'testSummary' => $testSummary,
            'totalScore' => $totalScore,
            'bonuses' => $bonuses,
            'message' => $this->test->status == 2 ? "¡Bravo! ¡Aprobaste el test!" : "¡Oh no! Has reprobado el test",
            'messageClass' => $this->test->status == 2 ? "alert-success" : "alert-danger"
        ));
    }
    private function evaluateTest($testAnsweredQuestions){
        //Si no se evaluó el test anteriormente, hacerlo.
        if(!in_array($this->test->status, [2, 3])){
            //Evaluar si todas las preguntas fueron respondidas correctamente.
            $answeredOk = 0;
            foreach($testAnsweredQuestions as $answer){
                $answeredOk += ($answer->answered_ok);
            }
            //Actualizar status del test.
            //Test aprobado => Status = 2, Test reprobado => Status = 3
            $this->test->status = ($answeredOk == $this->testSettings->questionsRequired) ? 2 : 3;
        }
        //Obtener usuario logueado.
        $this->player = User::find(session('id'));
        //Obtener resumen del test con los bonuses y penalizaciones obtenidas.
        //Se actualizan las rachas del jugador para luego ser persistidas en la base.
        $testSummary = $this->testSummary();
        //echo "<pre>"; var_dump($this->test); die();
        if(!$this->testFinished){
            //"Corregir" test. Es decir, persistir el status en la base.
            //Quitar campos agregados que no son de la tabla de tests (para poder persistir datos en la base).
            unset($this->test->questionsAnswered);
            unset($this->test->questionsRequired);
            $this->test->save();
            //Persistir rachas del jugador.
            $this->player->save();
        }
        //echo "<pre>"; var_dump($testSummary); die();
        return $testSummary;
    }
    
    private function processQuestion_test(){       
        //Obtener características del test.
        $this->testSettings = $this->getTestSetting();
        //Calcular cuántas preguntas tiene o tendrá el test.
        $this->test->questionsRequired = $this->testSettings->questionsRequired;
        //Evaluar si es posible generar nueva pregunta.
        //Esto dependerá de cuántas preguntas tiene el test y cuántas ya han sido respondidas.
        $testAnsweredQuestions = TestLog::where('test_id', $this->test->id)
                                        ->where('ellapsed_time', ">", 0)
                                        ->get();
        $questionsAnswered = count($testAnsweredQuestions);
        $this->test->questionsAnswered = $questionsAnswered;
        //echo "<pre>$questionsAnswered <> $this->test->questionsRequired<br>";
        //¿Aún quedan preguntas por contestar en el test?
        if($questionsAnswered < $this->test->questionsRequired){
            //¿Tiene el test categoría definida o debe obtenerse aleatoriamente?
            $aCategory = $this->getTestCategory();
            //¿Tiene el test complejidad definida o debe obtenerse aleatoriamente?
            $aComplexity = $this->getTestComplexity();
            //Obtener pregunta aleatoria con la categoría y complejidad anteriores.
            do {
                $question = Question::where('category', $aCategory)
                                    ->where('complexity', $aComplexity)
                                    ->inRandomOrder()->first();
            } while ($question && in_array($question->id, $this->questionsPicked));
            $this->questionsPicked[] = $question->id;
            //var_dump($question->id); die();
            if($question){
                return $this->processQuestion_question($question);
            } else { //Error al obtener pregunta aleatoria.
                echo "<pre>No pudo obtenerse una pregunta aleatoria para este test.";
                echo "category: $aCategory.<br/>";
                echo "complexity: $aComplexity.<br/>";
                var_dump($question);
            }
        } else { //No hay más preguntas para mostrar en el test.
            return $this->evaluateTest($testAnsweredQuestions);
            //echo "<pre>";var_dump($testStatus); die();
        }
    }

    public function createTest(Request $request){
        //Log::info(json_encode($request->input()['email']));
        //$user = User::where(['email' => session('email')])->get();
        //echo "<pre>"; var_dump($user[0]->id); die();
        if(!session('id')){
            return redirect('?lostsession');
        };
        $this->settings();
        //Existe un test del usuario en status nuevo sin terminar?
        $previousTest = Test::where('user_id', session("id"))
                            ->where('status', 1)->first();
        if($previousTest){
            $difficulties = array('Sencillo', 'Normal', 'Complejo', 'Muy complejo');
            $previousTest->difficultyCode = $previousTest->difficulty;
            $previousTest->difficulty = $difficulties[$previousTest->difficulty-1];
            $previousTest->category = $this->getCategoryByCode($previousTest->category);
            //echo "<pre>"; var_dump($previousTest->category); die();
            $previousTest->complexity = $this->getComplexityByCode($previousTest->complexity);
        }
        //echo "<pre>"; var_dump($previousTest); die();
        return view('create_test', array(
            "previousTest" => $previousTest,
            "errorClass" => !$previousTest ? "d-none" : "",
            "errorMessage" => $previousTest ? "Existe un test pendiente de resolver": "",
            "errorDescription" => $previousTest ? "Puedes iniciar ese test o cancelarlo. En ese caso se tomará como desaprobado. No podrás crear un nuevo test hasta finalizar o cancelar el pendiente.": ""
        ));
    }

    public function initTest(Request $request, $difficulty){
        if(!session('id')){
            return redirect('/?lostsession');
        };
        $this->settings();
        $setting = $this->getDifficultyByCode($difficulty);
        $categories = $this->settings['categories'];
        $complexities = $this->settings['complexities'];
        return view('init_test', array(
            "testSetting" => $setting, 
            "categories" => $categories,
            "complexities" => $complexities
        ));
    }

    public function newTest(Request $request, $difficulty, $categoryCode, $complexityCode){
        if(!session('id')){
            return redirect('/?lostsession');
        };
        $this->settings();
        $setting = $this->getDifficultyByCode($difficulty);

        //Existe un test del usuario en status nuevo sin terminar?
        $previousTest = Test::where('user_id', session("id"))
                            ->where('status', 1)->first();
        if($previousTest){
            //Obtener datos del test pendiente.
            $id = $previousTest->id;
            $difficulty = $previousTest->difficulty;
            $category = $this->getCategoryByCode($previousTest->category);
            $complexity = $this->getComplexityByCode($previousTest->complexity);
            if(!$complexity){
                 //No se seleccionó complejidad, entonces definir como aleatoria.
                 $complexity = new stdClass;
                 $complexity->name = 'Aleatoria';
                 $complexity->code = 4;
            }
        } else {
            //No hay test pendiente. Configurar test nuevo.
            if($difficulty > 1){
                //No se seleccionó complejidad, entonces definir como aleatoria.
                $complexity = new stdClass;
                $complexity->name = 'Aleatoria';
                $complexity->code = 4;
                if($difficulty > 2){
                    //No se seleccionó categoría, entonces definir como aleatoria.
                    $category = new stdClass;
                    $category->name = 'Aleatoria';
                    $category->code = 4;
                    /*
                    //No se seleccionó categoría, entonces definir aleatoriamente una.
                    $categoriesAmount = count($this->settings['categories']);
                    $categoryIndex = rand(4, $categoriesAmount-1);
                    $category = $this->settings['categories'][$categoryIndex];
                    */
                } else {
                    //Se seleccionó categoría, obtener sus datos.
                    $category = $this->getCategoryByCode($categoryCode);
                }
            } else {
                //Se seleccionó categoría y complejidad, obtener sus datos.
                $category = $this->getCategoryByCode($categoryCode);
                $complexity = $this->getComplexityByCode($complexityCode);
            }
        }
        
        //echo "<pre>"; var_dump(count($this->settings['complexities'])); die();
        if(!$previousTest){
            //Crear test en la base.
            $test = new Test;
            $test->status = 1; //Nuevo
            $test->difficulty = $difficulty;
            $test->category = $categoryCode;
            //Grabar complejidad sólo si fue elegida (dificultad 1)
            $test->complexity = $difficulty < 2 ? $complexityCode : 4;
            $test->user_id = session('id');
            $test->save();
            $id = $test->id;
        }
        //echo $category; die();
        
        return view('new_test', array(
            "testSetting" => $setting, 
            "difficulty" => $difficulty,
            "category" => $category,
            "complexity" => $complexity,
            "id" => $id
        ));
    }

    //Proceso para mostrar pantalla con la próxima pregunta del test (Si es que aún resta alguna)
    public function nextQuestion(Request $request, $id){

        function doWithoutTest($instance, $test){}

        if(!session('id')){
            return redirect('/?lostsession');
        };
        $this->settings();
        //Obtener test
        $test = Test::where('id', $id)->first();
        //¿Existe el test?
        if($test){
            //echo "<pre>"; var_dump($this); die();
            $this->test = $test;
            $this->testFinished = $this->test->status == 2;
            $this->questionsPicked = [];
            return $this->processQuestion_test();
        } else {
            $this->test = false;
            doWithoutTest($this, $test);
        }
    }

    public function disapprove(Request $request){
        $id = $request->input('id');/*$request->all()['id']*/
        //Actualizar rachas del usuario.
        $updatedUser = User::where('id', session("id"))->first();
        //echo "<pre>"; var_dump($updatedUser); die();
        //Aumentar racha perdedora.
        $updatedUser->losing_streak++;
        //Aumentar de ser necesario la peor racha perdedora.
        $updatedUser->worst_losing_streak+= $updatedUser->losing_streak > $updatedUser->worst_losing_streak ? 1 : 0;
        //Reiniciar racha ganadora.
        $updatedUser->winning_streak = 0;
        $updatedUser->updated_at = Date('Y-m-d H:i:s');
        $updatedUser->save();
        
        //Eliminar logs del test, de la base.
        $logsToDelete = TestLog::where('test_id', $id)->first()
                                ->delete();
        //Eliminar test, de la base.
        $testToDelete = Test::where('user_id', session("id"))
                            ->where('id', $id)->first()
                            ->delete();
        $responseJson = new stdClass;
        $responseJson->code = $testToDelete && $updatedUser ? 200 : 500;
        $responseJson->message = $testToDelete && $updatedUser ? "Test cancelado correctamente" : "Error al cancelar test";
        return response()->json($responseJson);
    }

    public function expireQuestion(Request $request, $question_id){
        $test_id = $request->input('id');
        //Obtener tiempo de la pregunta original.
        $question = Question::where('id', $question_id)->first();
        //echo "<pre>"; var_dump($question); die();
        //Actualizar log en el test.
        $testLog = TestLog::where('test_id', $test_id)->where('question_id', $question_id)->first();
        $testLog->ellapsed_time = $question->time;
        $testLog->answered_ok = 0;
        $testLog->updated_at = Date("Y/m/d H:i:s");
        $savedOK = $testLog->save();
        $responseJson = new stdClass;
        $responseJson->code = $savedOK ? 200 : 500;
        $responseJson->message = $savedOK ? "Pregunta expirada correctamente" : "Error al expirar pregunta";
        return response()->json($responseJson);
    }
    public function processQuestion(Request $request, $question_id){
        //Obtener test
        $test_id = $request->input('id');
        //Obtener array de respuestas seleccionadas.
        $arraySelectedAnswers = $request->input('selectedAnswers');
        $stringSelectedAnswers = implode(",", $arraySelectedAnswers);
        //Obtener la pregunta a procesar y sus respuestas.
        $question = Question::where('id', $question_id)->first();
        $questionAnswers = QuestionAnswer::where('question_id', $question_id)->get();
        //Tiempo que tomó contestar la pregunta.
        $ellapsedTime = $question->time - $request->input('remainingTime');
        //echo "<pre>"; var_dump($question); die();
        //Actualizar log en el test.
        $testLog = TestLog::where('test_id', $test_id)->where('question_id', $question_id)->first();
        $testLog->ellapsed_time = $ellapsedTime;
        //Evaluar respuestas del usuario.
        $questionApproved = true;
        $testing = new stdClass;
        $testing->questionID = $question->id;
        $testing->question = $question->description;
        $testing->answers = array();
        foreach($questionAnswers as $questionAnswer){
            //¿El usuario seleccionó una respuesta que es correcta?
            $currentAnswerRight = $questionAnswer->is_correct;
            $currentAnswerSelected = in_array($questionAnswer->answer_id, $arraySelectedAnswers);
            $rightAnswerSelected = $currentAnswerRight && $currentAnswerSelected;
            //¿El usuario no seleccionó una respuesta que es incorrecta?
            $wrongAnswerUnselected = !$currentAnswerRight && !$currentAnswerSelected;
            $questionApproved = $questionApproved && ($rightAnswerSelected || $wrongAnswerUnselected);
            
            $structure = new stdClass;
            $structure->answerId = $questionAnswer->answer_id;
            $structure->currentAnswerRight = $currentAnswerRight;
            $structure->currentAnswerSelected = $currentAnswerSelected;
            $structure->rightAnswerSelected = $rightAnswerSelected;
            $structure->wrongAnswerUnselected = $wrongAnswerUnselected;
            $structure->questionApproved = $questionApproved;
            $testing->answers[] = $structure;
        }
        $testLog->answered_ok = $questionApproved ? 1 : 0;
        $testLog->answers_selected = $stringSelectedAnswers;
        $testLog->updated_at = Date("Y/m/d H:i:s");
        $savedOK = $testLog->save();
        $responseJson = new stdClass;
        $responseJson->code = $savedOK ? 200 : 500;
        $responseJson->message = $savedOK ? "Respuesta/s registrada/s correctamente" : "Error al registrar respuesta/s a pregunta";
        $responseJson->questionAnswers = $questionAnswers;
        $responseJson->arraySelectedAnswers = $arraySelectedAnswers;
        $responseJson->testing = $testing;
        return response()->json($responseJson);
    }
}