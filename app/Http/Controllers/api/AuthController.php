<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Model\User;
use App\Rules\StrongPassword;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    public function signup(UserRequest $request)
    {
        $request = $request->validated();

        if (auth()->user()) {
            return Response([
                'status' => 'Failed',
                'message' => "What do you think you're doing? You already registered!",
            ], 403);
        }

        try {
            $user = new User;
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->role = 'user'; //you can promote user's status manually
            $user->password = bcrypt($request['password']);


            if ($user->save()) {
                return Response(['status' => 'Success',
                    'message' => $request['name'] . " is now a new user!",
                ]);
            } else {
                return Response(['status' => 'Failed',
                    'message' => "I am not sure why, but we massed up!"], 500);
            }
        } catch (QueryException $e) {
            return Response(['status' => 'Failed',
                'message' => "That's not the correct way of creating a user!",
                'debug' => $e]);
        }

    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => ['required', new StrongPassword]
        ]);

        if (Auth::attempt(['email' => $request->input('email'),
            'password' => $request->input('password')])) {
            // Authentication passed...
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);

            $token->save();
            return Response([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);
        }

        return Response([
            'error' => 'Unauthorized user',
            'code' => 401,
        ], 401);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     *
     */
    public function logout(Request $request)
    {

        $request->user()->token()->revoke();


        return Response([
            'status' => 'Success',
            'message' => 'See you soon!',
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user()
    {
        return response()->json(Auth::user());
    }
}
