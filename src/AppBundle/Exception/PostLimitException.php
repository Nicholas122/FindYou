<?php


namespace AppBundle\Exception;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PostLimitException extends AccessDeniedHttpException
{

}