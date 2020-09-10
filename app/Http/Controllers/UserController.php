<?php

namespace App\Http\Controllers;

use App\Owner;
use App\User;
use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Http\Resources\User as UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'could_not_create_token'], 500);
        }
        $user = JWTAuth::user();
        return response()->json(compact('token', 'user'));
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'home_number' => 'required|numeric|min:1|max:60',
            'store_name' => 'required|string|max:255',
            'role' => 'required',
            ]);
        //Solo se crean nuevos clientes, no nuevos propietarios
        if ($validator ->role==User::ROLE_CLIENT){
            $userable = Client::create([
                'home_number' => $request->get('home_number'),
            ]);
        }else{
            $userable = Owner::create([
                'home_number' => $request->get('store_name'),
            ]);
        }

        $user = $userable->user()->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),]);

        $token = JWTAuth::fromUser($user);
        return response()->json( new UserResource($user, $token), 201);
    }
    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'token_absent'], $e->getStatusCode());
        }

        return response()->json(new UserResource($user), 200);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                "status" => "success",
                "message" => "User successfully logged out."
            ], 200);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(["message" => "No se pudo cerrar la sesión."], 500);
        }
    }
}
