<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Passport\TokenRepository;

class AuthController extends Controller
{

    public function reqister_user(Request $request){
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|numeric',
            'first_name'     => 'required',
            'last_name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return Response::errorResponse($validator->errors()->first());
        }

        $user = User::create([
            'lead_id' => $request->lead_id,
            'first_name'     => $request->first_name,
            'last_name'     => $request->last_name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'type' => 'customer',
        ]);

        return Response::successResponse($user);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::errorResponse($validator->errors()->first());
        }

        $input = $request->only(['email', 'password']);
        if (auth()->attempt($input)) {
            $token = auth()->user()->createToken('passport_token')->accessToken;
            $user  = auth()->user();

            $data = ['user' => $user, 'token' => $token];
            return Response::successResponse($data);
        }
        else {
            return Response::errorResponse('email or password incorrect');
        }

    }

    public function logout()
    {
        $token = auth()->user()->token();


        $tokenReposetory = app(TokenRepository::class);
        $tokenReposetory->revokeAccessToken($token->id);

        // use this method to logout from all devices
        // $refreshTokenRepository = app(RefreshTokenRepository::class);
        // $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($$access_token->id);


        return Response::successResponse([], "logout success");

    }

    public function forgot_password(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status == Password::RESET_LINK_SENT){
            return Response::successResponse([],$status);
        }

        return Response::errorResponse($status);
    }

    public function callback_reset(Request $request){

        $data =[];
        $data['token'] = $request->token;
        $data['email'] = $request->email;

        return Response::successResponse($data);
    }

    public function reset_password(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user) use ($request){
                $user->forceFill([
                   'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60)
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET){
            return Response::successResponse([],"password reset successfully");
        }

        return Response::errorResponse($status,[],500);
    }

}
