<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\users;
use App\sensorMetaData;

class AuthController extends Controller {
    public function getToken(Request $request) {
        $validatedData = $this->validate($request, [
            'userEmail' => 'required',
            'userPass'  => 'required',
            'app' => 'bool'
        ]);

        $user = users::where("userEmail", $validatedData["userEmail"] )->first();

        if($user === null) {
            return response()->json([
                'error' => 'Username does not exist'
            ], 404);
        }

        if( !password_verify($validatedData["userPass"], $user["userPass"] ) ) {
            return response()->json([
                'error' => 'Email or password is wrong.'
            ], 401);
        }

        $ownedSensors = sensorMetadata::where("sensorOwner", $user["userId"])->get()->toArray();
        $ownedSensorIdArray = array_column($ownedSensors, 'sensorId');

        $payload = [
            'iss' => "esp-jwt",
            'sub' => $user["userId"],
            'iat' => time(),
            'exp' => $validatedData["app"] ? time() + 60*60*24*365*100 : time() + 60*60,
            'owned' => $ownedSensorIdArray
        ];

        return response()->json([
            'token' => JWT::encode($payload, env('JWT_SECRET'))
        ], 200);
    }
    public function testToken(Request $request) {
        return array(
            "Message" => "This Token Is Valid"
        );
    }
}