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
use App\Notifications\SignupActivate;
use Carbon\Carbon;
use App\PasswordReset;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Http\Controllers\Controller;

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
        $user = new UserResource(JWTAuth::user());
        return response()->json(compact('token', 'user'));
    }
    
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'home_number' => 'numeric|min:1|max:60',
            'store_name' => 'string|max:255',
            'role' => 'required',
            ]);
        //Solo se crean nuevos clientes, no nuevos propietarios
        if ($request->role=="ROLE_CLIENT"){
            $userable = Client::create([
                'home_number' => $request->get('home_number'),
            ]);
        }else{
            $userable = Owner::create([
                'store_name' => $request->get('store_name'),
            ]);
        }
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random=str_shuffle($permitted_chars);

        $user = $userable->user()->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'activation_code'  => $random,
            'role'=>$request->get('role'),   
            ]);
        
        $user->notify(new SignupActivate($user));

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

    // aqui la verificacion de correo
    public function verify($code)
    {
        $active = User::where('active', true)->first();
        $user = User::where('activation_code', $code)->first();

        if (!$user) {
            return response()->json(['message' => 'El token de activación es inválido'], 404);
        }

    // After verifying remove token (unnecessary)
        $user->activation_code = '';
        $user->active=true;
        $user->save();

        return view('confirm');
    }

    public function resend(User $user)
    {
        $active = User::where('active', true)->first();
        $user = User::where('activation_code', $code)->first();
        
        if(!$user)
        {   
            return response()->json(["message" => 'La cuenta ha sido verificada'], 409);
        }

        $user->notify(new SignupActivate($user));
        return response()->json(["message" => 'Se ha reenviado el correo electrónico de validación'], 200);
    }

    //De aqui hasta abajo el proceso de cambio de clave
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
                'message' => 'We can\'t find a user with that e-mail address.'
            ], 404);
        
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random=str_shuffle($permitted_chars);

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $random,
            ]
        );
        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ]);
    }

    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset)
        {    
            return response()->json(['message' => 'This password reset token is invalid.'], 404);
        }
        
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }
        return response()->json($passwordReset);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
                'message' => 'We can\'t find a user with that e-mail address.'
            ], 404);
        $user->password = Hash::make($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return response()->json($user);
    }
}
