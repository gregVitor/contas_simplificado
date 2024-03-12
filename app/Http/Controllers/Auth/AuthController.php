<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Validators\AuthValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\EmailService;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct(
        private readonly Auth $auth,
        private readonly UserRepository $userRepository,
        private readonly UserService $userService,
        private readonly  AuthValidator  $authValidator,
        private readonly  EmailService $emailService
    ) {
    }

    public function registerUser(Request $request)
    {
        try {
            $this->authValidator->validateRegisterUser($request->all());

            $user = $this->userService->create($request);

            $this->emailService->sendEmailRegisterUser($user);

            $data = [
                'id'    => hashEncodeId($user->id),
                'email' => $user->email,
                'name'  => $user->name
            ];

            return apiResponse("Usuário cadastrado com sucesso", 200, $data);
        } catch (\Exception $e) {
            throw ($e);
        }
    }

    public function login(Request $request)
    {
        try {
            $this->authValidator->validateLogin($request->all());

            $credentials = $request->only(['email', 'password']);
            $token = Auth::attempt($credentials);

            if (!$token) {
                return apiResponse("Não autorizado", 401);
            }

            $data = [
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => Auth::factory()->getTTL() * 60
            ];

            return apiResponse("Token gerado com sucesso.", 200, $data);
        } catch (\Exception $e) {
            throw ($e);
        }
    }
}
