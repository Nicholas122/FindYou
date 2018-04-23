<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\ControllerTestCase;

class TranslationConstantValueValueControllerTest extends ControllerTestCase
{
    public function testPost()
    {
        $data = [
            'value' => 'Password',
            'language' => $this->getLastId('Language'),
            'translationConstant' => $this->getLastId('TranslationConstant'),
        ];

        $response = $this->sendPost('api/translation-constant-values', $data, $this->apiToken);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        foreach ($responseData as $key => $datum) {
            if (array_key_exists($key, $data) && !in_array($key, ['language', 'translationConstant'])) {
                $this->assertTrue($datum === $data[$key]);
            }
        }
    }

    public function testPostAlreadyUsed()
    {
        $data = [
            'value' => 'Password',
            'language' => $this->getLastId('Language'),
            'translationConstant' => $this->getLastId('TranslationConstant'),
        ];

        $response = $this->sendPost('api/translation-constant-values', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This constant already matters in the given language.', $errors[0]['message']);
        }
    }

    public function testPutAlreadyUsed()
    {
        $data = [
            'value' => 'Password',
            'language' => $this->getLastId('Language'),
            'translationConstant' => $this->getLastId('TranslationConstant'),
        ];

        $response = $this->sendPut('api/translation-constant-values/'.$this->getLastId('TranslationConstantValue'), $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This constant already matters in the given language.', $errors[0]['message']);
        }
    }

    public function testPut()
    {
        $data = [
            'value' => 'Пароль',
            'language' => $this->getLastId('Language'),
            'translationConstant' => $this->getLastId('TranslationConstant'),
        ];

        $response = $this->sendPut('api/translation-constant-values/'.$this->getLastId('TranslationConstantValue'), $data, $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        foreach ($responseData as $key => $datum) {
            if (array_key_exists($key, $data) && !in_array($key, ['language', 'translationConstant'])) {
                $this->assertTrue($datum === $data[$key]);
            }
        }
    }

    public function testPutUnprocessableValue()
    {
        $data = [
            'value' => 'Пароль',
            'language' => $this->getLastId('Language'),
            'translationConstant' => $this->getLastId('TranslationConstant'),
        ];

        $response = $this->sendPut('api/translation-constant-values/'.$this->getLastId('TranslationConstantValue'), $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This constant already matters in the given language.', $errors[0]['message']);
        }
    }

    public function testGetCollection()
    {
        $response = $this->sendGet('api/translation-constant-values', $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testGet()
    {
        $response = $this->sendGet('api/translation-constant-values/'.$this->getLastId('TranslationConstantValue'), $this->apiToken);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testNotFoundGet()
    {
        $response = $this->sendGet('api/translation-constant-values/0', $this->apiToken);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testDeleteUnprocessable()
    {
        $response = $this->sendDelete('api/translation-constant-values/'.$this->getLastId('TranslationConstantValue'), '');
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testDelete()
    {
        $numberBefore = $this->getNumRecords('TranslationConstantValue');
        $response = $this->sendDelete('api/translation-constant-values/'.$this->getLastId('TranslationConstantValue'), $this->apiToken);
        $numberAfter = $this->getNumRecords('TranslationConstantValue');
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals($numberBefore - 1, $numberAfter);
    }

    public function testDeleteNotFound()
    {
        $this->sendDelete('/api/translation-constant-values/0', $this->apiToken);
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testPostUnprocessableValue()
    {
        $data = [
            'value' => null,
            'language' => $this->getLastId('Language'),
            'translationConstant' => $this->getLastId('TranslationConstant'),
        ];

        $response = $this->sendPost('api/translation-constant-values', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value should not be blank.', $errors['value']['message']);
        }
    }

    public function testPostUnprocessableLanguage()
    {
        $data = [
            'value' => 'Password',
            'language' => null,
            'translationConstant' => $this->getLastId('TranslationConstant'),
        ];

        $response = $this->sendPost('api/translation-constant-values', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value should not be blank.', $errors['language']['message']);
        }
    }

    public function testPostUnprocessableTranslationConstant()
    {
        $data = [
            'value' => 'Password',
            'language' => $this->getLastId('Language'),
            'translationConstant' => null,
        ];

        $response = $this->sendPost('api/translation-constant-values', $data, $this->apiToken);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        if (array_key_exists('errors', $responseData)) {
            $errors = $responseData['errors'];

            $this->assertEquals('This value should not be blank.', $errors['translationConstant']['message']);
        }
    }
}
