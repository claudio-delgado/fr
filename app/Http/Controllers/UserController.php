<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    private function generos(){
        return ["Femenino", "Masculino", "Otro"];
    }

    public function login(){
        return view('login'/*, ['users' => User::all()]*/);
    }

    public function checkLogin(Request $request){
        //Log::info(json_encode($request->input()['email']));
        $users = User::where(['email' => $request->email, 'password' => $request->password])->get();
        if(count($users)){
            $request->session()->put('email', $users[0]->email);
            $request->session()->put('id', $users[0]->id);
            Log::info(json_encode(session('email')));
        }
        return response()->json($users);
    }

    public function logout(Request $request){
        $request->session()->forget('email');
        return redirect('login');
    }

    public function profile(Request $request){
        //Log::info(json_encode($request->input()['email']));
        $user = User::where(['email' => session('email')])->get();
        //echo "<pre>"; var_dump($user[0]->id); die();
        return view('profile', ['user' => $user[0], 'message' => '', 'generos' => $this->generos()]);
    }

    public function ranking(Request $request){
        //Log::info(json_encode($request->input()['email']));
        $users = User::orderByDesc('score')->get();
        //echo "<pre>"; var_dump($user[0]->id); die();
        return view('rank', ['users' => $users]);
    }

    public function save(Request $request){
        //Log::info(json_encode($request->all()));
        $user = User::find($request->id);
        $user->name = $request->name;
        //Si cambió el email, actualizar sesión.
        if($user->email != $request->email){
            $request->session()->put('email', $request->email);
        }
        $user->email = $request->email;
        $user->birthDate = $request->birthDate;
        $user->gender = $request->gender;

        $user->save();
        return view('profile', ['user' => $user, 'message' => 'Usuario actualizado', 'generos' => $this->generos()]);
    }

    public function addUser(Request $request){
        //Log::info(json_encode($request->all()));
        $user = new User();
        $user->name = "default";
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        return view('login', ['users' => User::all()]);
    }
}
