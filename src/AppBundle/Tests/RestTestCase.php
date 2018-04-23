<?php

namespace AppBundle\Tests;

use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class RestTestCase extends WebTestCase
{
    /** @var Client */
    protected $client;

    /** @var ContainerInterface */
    protected $container;

    /**
     * Prepare each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient(array(
            'environment' => 'test',
            'debug' => true,
        ));
        $this->container = $this->client->getContainer();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    public function getLastId($entity)
    {
        $query = $this->getEntityManager()->createQuery('SELECT MAX(c.id) FROM AppBundle:'.$entity.' c');

        return $query->getSingleScalarResult();
    }

    public function getLastObject($entity)
    {
        $id = $this->getLastId($entity);
        $object = $this->getEntityManager()->getRepository('AppBundle:'.$entity)->find($id);

        return $object;
    }

    public function getNumRecords($entity)
    {
        $query = $this->getEntityManager()->createQuery('SELECT COUNT(c) FROM AppBundle:'.$entity.' c');

        return $query->getSingleScalarResult();
    }

    protected function logIn($username)
    {
        $session = $this->getEntityManager()->getRepository('VidiaAuthBundle:Session')->findOneBy(array('user' => $username));

        return $session;
//        $this->client->getContainer()->setParameter('apiToken', $user->getApiToken());
//        $token = new UsernamePasswordToken($user, null, $firewall, array('ROLE_SUPER_ADMIN'));
//        $session->set('_security_'.$firewall, serialize($token));
//        $session->save();

//        $cookie = new Cookie($session->getName(), $session->getId());
//        $this->client->getCookieJar()->set($cookie);
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    /**
     * @param $resource
     * @param $data
     * @param mixed $apiToken
     *
     * @return Response
     */
    protected function sendPost($resource, $data, $apiToken)
    {
        return $this->sendJSONRequest('POST', $resource, $data, $apiToken);
    }

    protected function sendPut($resource, $data, $apiToken)
    {
        return $this->sendJSONRequest('PUT', $resource, $data, $apiToken);
    }

    protected function sendGet($resource, $apiToken)
    {
        return $this->sendJSONRequest('GET', $resource, null, $apiToken);
    }

    protected function sendDelete($resource, $apiToken)
    {
        return $this->sendJSONRequest('DELETE', $resource, null, $apiToken);
    }

    protected function sendJSONRequest($method, $resource, $data, $apiToken)
    {
        $this->client->request(
            $method,
            $resource,
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => '' !== $apiToken ? 'Bearer '.$apiToken : '',
            ),
            $data ? json_encode($data) : null
        );

        return $this->client->getResponse();
    }

    protected function assertErrorsResponse($data)
    {
        $this->assertArrayHasKey('form', json_decode($data, true));
        $this->assertArrayHasKey('errors', json_decode($data, true));
    }

    protected function tearDown()
    {
        parent::tearDown();
        unset($this->client);
    }
}
