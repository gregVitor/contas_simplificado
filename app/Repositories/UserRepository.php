<?php

namespace App\Repositories;

use App\Enums\UserTypeEnum;
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
    public function create(object $data)
    {
        $password = app('hash')->make($data->password);

        $user = $this->user->create([
            'name' => $data->name,
            'email' => $data->email,
            'fiscal_document' => $data->fiscal_document,
            'type' => strlen($data->fiscal_document) > 11 ? UserTypeEnum::SHOPKEEPER : UserTypeEnum::COMMON,
            'password' => $password
        ]);

        return $user;
    }
}
