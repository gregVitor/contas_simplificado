<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\BankAccountRepository;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly BankAccountRepository $bankAccountRepository

    ) {
    }

    public function create(
        object $data
    ): User {
        $user = $this->userRepository->create($data);
        $this->bankAccountRepository->create(['user_id' => $user->id]);

        return $user;
    }
}
