<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

class CookieController extends Controller
{
    static function initialise_cookie(){
        $cookie_lifespan = time()+(3600*12); // last number = number of hours

        $user_prefs = UserController::get_user_prefs();

        $user_prefs = \json_encode($user_prefs);
        \setcookie('user_prefs', $user_prefs, $cookie_lifespan);
    }
    static function clear_cookies(){
        if (isset($_COOKIE)){
            foreach($_COOKIE as $name => $value){
                setcookie($name, '', 1);
            }
        }
    }
}
