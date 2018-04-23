<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\ControllerTestCase;

class UserControllerTest extends ControllerTestCase
{
    public function testPostNotBlank()
    {
        $data = [
            'username' => null,
            'email' => null,
            'phone' => null,
            'gender' => null,
            'dateOfBirth' => null,
            'password' => null,
        ];
        $response = $this->sendPost('api/users', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            foreach ($data as $key => $datum) {
                $this->assertEquals('This value should not be blank.', $errors[$key]['message']);
            }
        }
    }

    public function testPost()
    {
        $data = [
            'username' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'phone' => '+380978309874',
            'gender' => 'male',
            'dateOfBirth' => '1992-12-18',
            'password' => '12345678',
        ];

        $response = $this->sendPost('api/users', $data, $this->apiToken);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        foreach ($responseData as $key => $datum) {
            if ('password' != $key && 'dateOfBirth' != $key && array_key_exists($key, $data)) {
                $this->assertTrue($datum === $data[$key]);
            }
        }
    }

    public function testPostUnprocessablePhone()
    {
        $data = [
            'username' => 'John Doe2',
            'email' => 'johndoe123@gmail.com',
            'phone' => '+38067859433',
            'gender' => 'male',
            'dateOfBirth' => '1992-12-18',
            'password' => '12345678',
        ];
        $response = $this->sendPost('api/users', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value is too short. It should have 13 characters or more.', $errors['phone']['message']);
        }
    }

    public function testPostUnprocessableGender()
    {
        $data = [
            'username' => 'John Doe2',
            'email' => 'johndoe123@gmail.com',
            'phone' => '+3806785943343',
            'gender' => 'malee',
            'dateOfBirth' => '1992-12-18',
            'password' => '12345678',
        ];
        $response = $this->sendPost('api/users', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testPostUnprocessableBirthDay()
    {
        $data = [
            'username' => 'John Doe2',
            'email' => 'johndoe123@gmail.com',
            'phone' => '+3806785943343',
            'gender' => 'male',
            'dateOfBirth' => '1992-18-18',
            'password' => '12345678',
        ];
        $response = $this->sendPost('api/users', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value is not valid.', $errors['dateOfBirth']['message']);
        }
    }

    public function testPostUnprocessableEmail()
    {
        $data = [
            'username' => 'John Doe2',
            'email' => 'johndoe123gmail',
            'phone' => '+3806785943343',
            'gender' => 'male',
            'dateOfBirth' => '1992-12-18',
            'password' => '12345678',
        ];
        $response = $this->sendPost('api/users', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value is not a valid email address.', $errors['email']['message']);
        }
    }

    public function testPutAlreadyUsed()
    {
        $data = [
            'username' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '+380678309563',
        ];

        $response = $this->sendPut('api/users/'.$this->getLastId('User'), $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            foreach ($data as $key => $datum) {
                $this->assertEquals('This value is already used.', $errors[$key]['message']);
            }
        }
    }

    public function testGetCollection()
    {
        $response = $this->sendGet('api/users', $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testGet()
    {
        $response = $this->sendGet('api/users/'.$this->getLastId('User'), $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testNotFoundGet()
    {
        $response = $this->sendGet('api/users/0', $this->apiToken);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testDeleteUnprocessable()
    {
        $response = $this->sendDelete('api/users/'.$this->getLastId('User'), '');
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testDelete()
    {
        $numberBefore = $this->getNumRecords('User');
        $response = $this->sendDelete('api/users/'.$this->getLastId('User'), $this->apiToken);
        $numberAfter = $this->getNumRecords('User');
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals($numberBefore - 1, $numberAfter);
    }

    public function testDeleteNotFound()
    {
        $this->sendDelete('/api/users/0', $this->apiToken);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }
}
