<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function viewLogin(){
        return view('login');
    }
    public function viewRecuperar(){
        return view('app.recuperar');
    }
    public function viewChange(){
        if(\Auth::check())
        {
            $user = \Auth::user();
            if($user->Changed_at == null && $user->Usuario_id > 1)
            {
                return view('app.change.initial');
            }
            else abort(404);
        }
        return redirect('/');
    }

    public function viewCambiar(){
        return view('app.change.change');
    }

    /**/

}
