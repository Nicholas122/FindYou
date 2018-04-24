<?php

namespace Vidia\AdminBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Vidia\AdminBundle\Form\UserForm;

class UsersController extends BaseController
{
    /**
     * @Route("/users", name="users")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            $response = $this->redirectToRoute('sign-in');
        } else {
            $response = $this->render('@VidiaAdmin/users/index.html.twig', [
                'users' => $this->findBy('AppBundle:User', []),
            ]);
        }

        return $response;
    }

    /**
     * @Route("/users/{user}", name="user")
     * @Method({"GET", "POST"})
     */
    public function editAction(User $user, Request $request)
    {
        if (!$this->getUser() instanceof User) {
            $response = $this->redirectToRoute('sign-in');
        } else {
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
        }

        return $response;
    }

    /**
     * @Route("/users/{user}", name="delete-user")
     * @Method({"DELETE"})
     */
    public function deleteAction(User $user, Request $request)
    {
        if (!$this->getUser() instanceof User) {
            $response = $this->redirectToRoute('sign-in');
        } else {
            $em = $this->getDoctrine()->getManager();

            $em->remove($user);
            $em->flush();

            $response = $this->redirectToRoute('users');
        }

        return $response;
    }
}
