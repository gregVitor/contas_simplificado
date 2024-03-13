<?php

use App\Enums\UserTypeEnum;
use App\Models\BankAccount;
use App\Models\User;
use App\Repositories\BankAccountRepository;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Faker\Factory as Faker;

abstract class TestCase extends BaseTestCase
{
    protected $faker;

    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create('pt_BR');
    }

    protected function generateRandomNumber($length)
    {
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;
        return mt_rand($min, $max);
    }

    protected function generateToken()
    {
        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        $password = "12345678";

        $response = $this->post(
            'api/v1/register',
            [
                "name" => $name,
                "email" => $email,
                "fiscal_document" => $this->generateRandomNumber(11),
                "password" => $password,
                "password_confirm" => $password

            ]
        );

        $response = $this->post(
            'api/v1/login',
            [
                "email" => $email,
                "password" => $password

            ]
        );
        $content = json_decode($this->response->getContent());
        
        return (object)[
            'token' => $content->data->access_token,
            'email' => $email
        ];
    }

    protected function generateTokenShopkeeper()
    {
        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        $password = "12345678";

        $response = $this->post(
            'api/v1/register',
            [
                "name" => $name,
                "email" => $email,
                "fiscal_document" => $this->generateRandomNumber(14),
                "password" => $password,
                "password_confirm" => $password

            ]
        );

        $response = $this->post(
            'api/v1/login',
            [
                "email" => $email,
                "password" => $password

            ]
        );
        $content = json_decode($this->response->getContent());
        
        return (object)[
            'token' => $content->data->access_token,
            'email' => $email
        ];
    }

    protected function createUser($numberDigitsDocument = 11): User
    {
        $user = new User();
        $user->name = $this->faker->name;
        $user->email = $this->faker->unique()->safeEmail;
        $user->fiscal_document = $this->generateRandomNumber($numberDigitsDocument);
        $user->type = $numberDigitsDocument > 11 ? UserTypeEnum::SHOPKEEPER : UserTypeEnum::COMMON;
        $user->password = "12345678";
        $user->save();

        BankAccount::query()->create(['user_id' => $user->id]);

        return $user;
    }
}
