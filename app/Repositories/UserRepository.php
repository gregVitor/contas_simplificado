<?php

namespace App\Repositories;

use App\Enums\TypeUserEnum;
use App\Models\User;

class UserRepository
{
    /**
     * @var User
     */
    private $user;

    /**
     * Class constructor method.
     *
     * @param User $user
     */
    public function __construct(
        User $user
    ) {
        $this->user = $user;
    }

    /**
     * Function create user
     *
     * @param object $data
     *
     * @return User
     */
    public function registerUser(object $data)
    {
        $password = app('hash')->make($data->password);

        $user = $this->user->create([
            'name' => $data->name,
            'email' => $data->email,
            'fiscal_document' => $data->fiscal_document,
            'type' => $data->fiscal_document > 11 ? TypeUserEnum::SHOPKEEPER : TypeUserEnum::COMMON,
            'password' => $password
        ]);

        return $user;
    }
}
