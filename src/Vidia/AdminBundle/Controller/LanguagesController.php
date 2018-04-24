<?php

namespace Vidia\AdminBundle\Controller;

use AppBundle\Entity\Language;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Vidia\AdminBundle\Form\LanguageForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class LanguagesController extends BaseController
{
    /**
     * @Route("/languages", name="languages")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        $response = $this->render('@VidiaAdmin/languages/index.html.twig', [
            'languages' => $this->findBy('AppBundle:Language', []),
        ]);

        return $response;
    }

    /**
     * @Route("/languages/new", name="new-language")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function newAction(Request $request)
    {
        $language = new Language();

        $form = $this->createForm(LanguageForm::class, $language);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && 'POST' == $request->getMethod()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($language);
            $em->flush();

            $response = $this->redirectToRoute('languages');
        } else {
            $response = $this->render('@VidiaAdmin/languages/language.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $response;
    }

    /**
     * @Route("/languages/{language}", name="language")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function editAction(Language $language, Request $request)
    {
        $form = $this->createForm(LanguageForm::class, $language);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && 'POST' == $request->getMethod()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($language);
            $em->flush();

            $response = $this->redirectToRoute('languages');
        } else {
            $response = $this->render('@VidiaAdmin/languages/language.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $response;
    }

    /**
     * @Route("/languages/{language}", name="delete-language")
     * @Method({"DELETE"})
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Language $language, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($language);
        $em->flush();

        $response = $this->redirectToRoute('languages');

        return $response;
    }
}
