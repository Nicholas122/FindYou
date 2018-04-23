<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\ControllerTestCase;

class ExternalCharacteristicControllerTest extends ControllerTestCase
{
    public function testPost()
    {
        $data = [
            'name' => 'eyes',
            'type' => 'choice',
        ];

        $response = $this->sendPost('api/external-characteristics', $data, $this->apiToken);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        foreach ($responseData as $key => $datum) {
            if (array_key_exists($key, $data)) {
                $this->assertTrue($datum === $data[$key]);
            }
        }
    }

    public function testPostAlreadyUsedName()
    {
        $data = [
            'name' => 'eyes',
            'type' => 'choise',
        ];

        $response = $this->sendPost('api/external-characteristics', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value is already used.', $errors['name']['message']);
        }
    }

    public function testPostUnprocessableType()
    {
        $data = [
            'name' => 'hair',
            'type' => 'test',
        ];

        $response = $this->sendPost('api/external-characteristics', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value already used.', $errors['type']['message']);
        }
    }

    public function testGetCollection()
    {
        $response = $this->sendGet('api/external-characteristics', $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testGet()
    {
        $response = $this->sendGet('api/external-characteristics/'.$this->getLastId('ExternalCharacteristic'), $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testNotFoundGet()
    {
        $response = $this->sendGet('api/external-characteristics/0', $this->apiToken);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testDeleteUnprocessable()
    {
        $response = $this->sendDelete('api/external-characteristics/'.$this->getLastId('ExternalCharacteristic'), '');
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testDelete()
    {
        $numberBefore = $this->getNumRecords('ExternalCharacteristic');
        $response = $this->sendDelete('api/external-characteristics/'.$this->getLastId('ExternalCharacteristic'), $this->apiToken);
        $numberAfter = $this->getNumRecords('ExternalCharacteristic');
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals($numberBefore - 1, $numberAfter);
    }

    public function testDeleteNotFound()
    {
        $this->sendDelete('/api/external-characteristics/0', $this->apiToken);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }
}
