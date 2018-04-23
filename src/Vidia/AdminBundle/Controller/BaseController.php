<?php

namespace Vidia\AdminBundle\Controller;

use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    public function getUsersQuantity()
    {
        /**
         * @var UserRepository
         */
        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

        return $userRepository->getUsersQuantity();
    }

    public function findBy($entity, $criteria, $orderBy = [])
    {
        $repository = $this->getDoctrine()->getRepository($entity);

        $result = $repository->findBy($criteria, $orderBy);

        return $result;
    }

    public function findOneBy($entity, $criteria)
    {
        $repository = $this->getDoctrine()->getRepository($entity);

        $result = $repository->findOneBy($criteria);

        return $result;
    }
}
