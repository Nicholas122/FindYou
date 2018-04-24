<?php

namespace Vidia\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Vidia\AuthBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="home")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        $response = $this->render('@VidiaAdmin/home/index.html.twig', [
            'usersQuantity' => $this->getUsersQuantity(),
            'usersOnlineCount' => $this->getUsersOnlineCount() ?: 0,
            'userRegistryStat' => json_encode($this->getUserRegistryStat()),
            'memoryUsage' => $this->getMemoryUsage(),
            'spaceUsage' => $this->getSpaceUsage(),
            'serverUptime' => $this->getServerUptime()

        ]);

        return $response;
    }
}
