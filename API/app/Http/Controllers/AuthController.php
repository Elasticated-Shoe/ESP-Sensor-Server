<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\users;
use App\sensorMetaData;

class AuthController extends Controller {
    public function getToken(Request $request) {
        $validatedData = $this->validate($request, [
            'userEmail'     => 'required',
            'userPass'  => 'required'
        ]);

        $user = users::where("userEmail", $validatedData["userEmail"] )->first();

        if($user === null) {
            return array(
                "Message" => "Sensor {$id} Not Found"
            );
        }

        if( password_verify($validatedData["userPass"], $user["userPass"] ) ) {
            $payload = [
                'iss' => "esp-jwt",
                'sub' => $user["userId"],
                'iat' => time(),
                'exp' => time() + 60*60
            ];

            return response()->json([
                'token' => JWT::encode($payload, env('JWT_SECRET'))
            ], 200);
        }

        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }
    public function testToken(Request $request) {
        return array(
            "Message" => "This Token Is Valid"
        );
    }
}