<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Validators\AuthValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\EmailService;

class AuthController extends Controller
{
    private $userRepository;
    private $authValidator;
    private $emailService;

    public function __construct(
        UserRepository $userRepository,
        AuthValidator  $authValidator,
        EmailService $emailService
    ) {
        $this->userRepository = $userRepository;
        $this->authValidator  = $authValidator;
        $this->emailService = $emailService;
    }

    public function registerUser(Request $request)
    {
        try {
            $this->authValidator->validateRegisterUser($request->all());

            $user = $this->userRepository->registerUser($request);

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

            if (!$token = Auth::attempt($credentials)) {
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
