<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\ControllerTestCase;

class ExternalCharacteristicControllerValueTest extends ControllerTestCase
{
    public function testPostUnprocessable()
    {
        $data = [
            'value' => 'blue',
            'externalCharacteristic' => $this->getLastId('ExternalCharacteristic') - 1,
        ];

        $response = $this->sendPost('api/external-characteristic-values', $data, null);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testPost()
    {
        $data = [
            'value' => 'blue',
            'externalCharacteristic' => $this->getLastId('ExternalCharacteristic') - 1,
        ];

        $response = $this->sendPost('api/external-characteristic-values', $data, $this->apiToken);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        foreach ($responseData as $key => $datum) {
            if (array_key_exists($key, $data) && 'externalCharacteristic' != $key) {
                $this->assertTrue($datum === $data[$key]);
            }
        }
    }

    public function testPostAlreadyUsedValue()
    {
        $data = [
            'value' => 'blue',
            'externalCharacteristic' => $this->getLastId('ExternalCharacteristic') - 1,
        ];

        $response = $this->sendPost('api/external-characteristic-values', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value is already used.', $errors['value']['message']);
        }
    }

    public function testPostUnprocessableType()
    {
        $data = [
            'value' => 'grey',
            'externalCharacteristic' => $this->getLastId('ExternalCharacteristic'),
        ];

        $response = $this->sendPost('api/external-characteristic-values', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This external characteristic is not of type choice.', $errors['externalCharacteristic']['message']);
        }
    }

    public function testPutUnprocessable()
    {
        $data = [
            'value' => 'green',
            'externalCharacteristic' => $this->getLastId('ExternalCharacteristic') - 1,
        ];

        $response = $this->sendPut('api/external-characteristic-values/'.$this->getLastId('ExternalCharacteristicChoiceValue'), $data, null);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testPutNotFound()
    {
        $data = [
            'value' => 'green',
            'externalCharacteristic' => $this->getLastId('ExternalCharacteristic') - 1,
        ];

        $response = $this->sendPut('api/external-characteristic-values/0', $data, $this->apiToken);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testPut()
    {
        $data = [
            'value' => 'green',
        ];

        $response = $this->sendPut('api/external-characteristic-values/'.$this->getLastId('ExternalCharacteristicChoiceValue'), $data, $this->apiToken);
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
        $response = $this->sendGet('api/external-characteristic-values', $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testGet()
    {
        $response = $this->sendGet('api/external-characteristic-values/'.$this->getLastId('ExternalCharacteristicChoiceValue'), $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testNotFoundGet()
    {
        $response = $this->sendGet('api/external-characteristic-values/0', $this->apiToken);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testDeleteUnprocessable()
    {
        $response = $this->sendDelete('api/external-characteristic-values/'.$this->getLastId('ExternalCharacteristicChoiceValue'), '');
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testDelete()
    {
        $numberBefore = $this->getNumRecords('ExternalCharacteristicChoiceValue');
        $response = $this->sendDelete('api/external-characteristic-values/'.$this->getLastId('ExternalCharacteristicChoiceValue'), $this->apiToken);
        $numberAfter = $this->getNumRecords('ExternalCharacteristicChoiceValue');
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals($numberBefore - 1, $numberAfter);
    }

    public function testDeleteNotFound()
    {
        $this->sendDelete('/api/external-characteristic-values/0', $this->apiToken);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }
}
