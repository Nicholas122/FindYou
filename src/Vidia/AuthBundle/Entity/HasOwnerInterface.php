<?php

namespace Vidia\AuthBundle\Entity;

interface HasOwnerInterface
{
    /**
     * @return User[]
     */
    public function getOwners();
}
