<?php

namespace Vidia\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Vidia\AuthBundle\Entity\User;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            $response = $this->redirectToRoute('sign-in');
        } else {
            $response = $this->render('@VidiaAdmin/home/index.html.twig', [
                'usersQuantity' => $this->getUsersQuantity(),
            ]);
        }

        return $response;
    }
}
