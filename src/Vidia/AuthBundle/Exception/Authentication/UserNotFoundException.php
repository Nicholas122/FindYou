<?php

namespace Vidia\AuthBundle\Exception\Authentication;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserNotFoundException extends UnauthorizedHttpException
{
}
