<?php

namespace Vidia\AdminBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Vidia\AdminBundle\Form\UserForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UsersController extends BaseController
{
    /**
     * @Route("/users", name="users")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        $response = $this->render('@VidiaAdmin/users/index.html.twig', [
            'users' => $this->getUsers($request),
        ]);

        return $response;
    }

    /**
     * @Route("/users/{user}", name="user")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function editAction(User $user, Request $request)
    {
        $form = $this->createForm(UserForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && 'POST' == $request->getMethod()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();
        }

        $response = $this->render('@VidiaAdmin/users/user.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'posts' => $this->findBy('AppBundle:Post', ['user' => $user->getId()]),
        ]);

        return $response;
    }

    /**
     * @Route("/users/{user}", name="delete-user")
     * @Method({"DELETE"})
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(User $user, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($user);
        $em->flush();

        $response = $this->redirectToRoute('users');

        return $response;
    }
}
