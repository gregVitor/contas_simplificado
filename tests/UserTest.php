<?php

class UserTest extends TestCase
{
    public function testRegisterUser()
    {
        $response = $this->post(
            'api/v1/register',
            [
                "name" => $this->faker->name,
                "email" => $this->faker->unique()->safeEmail,
                "fiscal_document" => $this->generateRandomNumber(11),
                "password" => "12345678",
                "password_confirm" => "12345678"

            ]
        );

        $response->assertResponseStatus(200);
        $this->assertJson(
            $this->response->getContent()
        );
    }

    public function testLogin()
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

        $response->assertResponseStatus(200);
        $this->assertJson(
            $this->response->getContent()
        );
    }
}
