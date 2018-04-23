<?php

namespace Vidia\AuthBundle\Service;

use Vidia\AuthBundle\Entity\UserInterface;
use Vidia\AuthBundle\Strategy\Auth\AuthStrategyInterface;
use Symfony\Component\HttpFoundation\Request;

class UserAuthService
{
    /** @var array $strategies */
    private $strategies;

    /**
     * UserAuthService constructor.
     *
     * @param array $strategies
     */
    public function __construct(array $strategies)
    {
        foreach ($strategies as $strategy) {
            $this->addAuthStrategy($strategy);
        }
    }

    /**
     * Log user in.
     *
     * @param Request $request
     *
     * @return UserInterface|bool
     */
    public function authorize(Request $request)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($request)) {
                return $strategy->authorize($request);
            }
        }

        return false;
    }

    /**
     * Add authorization strategy.
     *
     * @param AuthStrategyInterface $strategy
     */
    public function addAuthStrategy(AuthStrategyInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }
}
