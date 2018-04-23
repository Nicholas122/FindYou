<?php

namespace Vidia\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Vidia\AdminBundle\Form\SignInForm;

class SecurityController extends Controller
{
    /**
     * @Route("/sign-in", name="sign-in")
     */
    public function indexAction()
    {
        $form = $this->createForm(SignInForm::class);

        return $this->render('VidiaAdminBundle:Security:sign-in.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/logout", name="logout")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logoutActiot(Request $request)
    {
        return $this->redirectToRoute('sign-in');
    }
}
