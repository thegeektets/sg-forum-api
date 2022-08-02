<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',

        ]);

        if ($validator->fails()) {
            return response('
            { 
             "message": "Validation failed",

            }', 400)
                ->header('Content-Type', 'text/json');
        }

        $email = $request->email;
        $password = $request->password;

        $userData = User::where('email', '=', $email)->firstOrFail();

        if ($userData) {

            if (Hash::check($password, $userData->password)) {
                return response('
                {
                "message": "User login successful",
                "user":' . $userData . '
                "token":"' . $userData->remember_token . '"
                }
                ', 200)
                    ->header('Content-Type', 'text/json');
            }

        }

        return response('
        {
        "message": "Failed to login, kindly check your credentials"
        }
        ', 500)
            ->header('Content-Type', 'text/json');



    }
}
