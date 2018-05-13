<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Reply;
use AppBundle\Form\ReplyForm;
use AppBundle\Service\ReplyService;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Request\ParamFetcher;
use RedjanYm\FCMBundle\FCMClient;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("test")
 */
class TestController extends BaseRestController
{
    /**
     * Return conversations.
     *
     *
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $YOUR_API_KEY = 'AAAA4wynn7c:APA91bH7-FIVeQ5W7lDUEs37pgZwFm0w75iiV4AWLaohJW3zY76PHb8uqA0bDTRlkLoS5ThMhS62xIwY2tHmKCLG_3stL7c8suRfV9M0Hr0j9hPbGE5p30Ll9PTKf7qtlpScksbjk4Rd'; // Server key
        $YOUR_TOKEN_ID = 'fik6c0--WKA:APA91bF59gzqnz9N0rV0cTj23kNdzER3Maj6Y92gPkDPPEvfOVvvD0qfDwr_laDJED6k65eSayPFwC-zq7Oi4tzdpV2yM8Z3kj-9EjXGkSIHaYRe3euOJ8rcDHOXrVHr3Bc1bto1qJHu'; // Client token id



    }
}