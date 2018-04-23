<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\ControllerTestCase;

class TranslationConstantControllerTest extends ControllerTestCase
{
    public function testPost()
    {
        $data = [
            'name' => 'password',
        ];

        $response = $this->sendPost('api/translation-constants', $data, $this->apiToken);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        foreach ($responseData as $key => $datum) {
            if (array_key_exists($key, $data)) {
                $this->assertTrue($datum === $data[$key]);
            }
        }
    }

    public function testPostAlreadyInUseName()
    {
        $data = [
            'name' => 'password',
        ];
        $response = $this->sendPost('api/translation-constants', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value is already used.', $errors['name']['message']);
        }
    }

    public function testPostUnprocessableName()
    {
        $data = [
            'name' => null,
        ];

        $response = $this->sendPost('api/translation-constants', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value should not be blank.', $errors['name']['message']);
        }
    }

    public function testPutUnprocessableName()
    {
        $data = [
            'name' => null,
        ];

        $response = $this->sendPut('api/translation-constants/'.$this->getLastId('TranslationConstant'), $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value should not be blank.', $errors['name']['message']);
        }
    }

    public function testPutUnprocessable()
    {
        $data = [
            'name' => 'home',
        ];

        $response = $this->sendPut('api/translation-constants/'.$this->getLastId('TranslationConstant'), $data, null);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testPutNotFound()
    {
        $data = [
            'name' => 'home',
        ];

        $response = $this->sendPut('api/translation-constants/0', $data, $this->apiToken);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testPut()
    {
        $data = [
            'name' => 'home',
        ];

        $response = $this->sendPut('api/translation-constants/'.$this->getLastId('TranslationConstant'), $data, $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        foreach ($responseData as $key => $datum) {
            if (array_key_exists($key, $data)) {
                $this->assertTrue($datum === $data[$key]);
            }
        }
    }

    public function testGetCollection()
    {
        $response = $this->sendGet('api/translation-constants', $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testGet()
    {
        $response = $this->sendGet('api/translation-constants/'.$this->getLastId('TranslationConstant'), $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testNotFoundGet()
    {
        $response = $this->sendGet('api/translation-constants/0', $this->apiToken);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testDeleteUnprocessable()
    {
        $response = $this->sendDelete('api/translation-constants/'.$this->getLastId('TranslationConstant'), '');
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testDelete()
    {
        $numberBefore = $this->getNumRecords('TranslationConstant');
        $response = $this->sendDelete('api/translation-constants/'.$this->getLastId('TranslationConstant'), $this->apiToken);
        $numberAfter = $this->getNumRecords('TranslationConstant');
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals($numberBefore - 1, $numberAfter);
    }

    public function testDeleteNotFound()
    {
        $this->sendDelete('/api/translation-constants/0', $this->apiToken);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }
}
