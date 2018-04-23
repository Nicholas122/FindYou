<?php

namespace Vidia\AuthBundle\Strategy\Auth;

use Vidia\AuthBundle\Entity\UserInterface;
use Symfony\Component\HttpFoundation\Request;

interface AuthStrategyInterface
{
    /**
     * Log user in.
     *
     * @param Request $request
     *
     * @return UserInterface|bool
     */
    public function authorize(Request $request);

    /**
     * Tells if strategy supports given request parameters.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request);
}
