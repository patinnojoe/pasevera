<?php

namespace App\Http\Controllers\API;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{

    /**
     * Register a newly created user in DB.
     */
    public function register(RegisterRequest $request)
    {
        //

        try {
            $user =  User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'email' => $request->email,


            ]);

            if ($user) {
                $token = $user->createToken('my API token')->plainTextToken;
                $authUser = [
                    'token' => $token,
                    'user' => $user,
                    'user_id' => $user->id
                ];

                return   ResponseHelper::success(message: 'User has been registered to pasevera sucessfully', data: $authUser, statusCode: 201,);
            }
            return   ResponseHelper::error(message: 'Something went wrong, please try again');
        } catch (Exception $e) {

            //throw $th;
            Log::error('Unable to register', [$e->getMessage() . 'Line no' . $e->getLine()]);
            return   ResponseHelper::error(message: 'Something went wrong, please try again' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     *Login the user .
     */
    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return   ResponseHelper::error(message: 'Something went wrong, check credentials and try again', statusCode: 500);
            }
            $user = Auth::user();
            $token = $user->createToken('my API token')->plainTextToken;
            $authUser = [
                'token' => $token,
                'user' => $user,
                'user_id' => $user->id
            ];
            return   ResponseHelper::success(message: 'User has been logged in to pasevera sucessfully', data: $authUser, statusCode: 200);
        } catch (Exception $e) {
            Log::error('Unable to login', [$e->getMessage() . 'Line no' . $e->getLine()]);
            return   ResponseHelper::error(message: 'Something went wrong, please try again' . $e->getMessage(), statusCode: 500);
        }
    }
}
