<?php

namespace Vidia\AdminBundle\Controller;

use AppBundle\Entity\TranslationConstant;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Vidia\AdminBundle\Form\TranslationConstantForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TranslationConstantController extends BaseController
{
    /**
     * @Route("/translation-constants", name="translation-constants")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        $response = $this->render('@VidiaAdmin/translationConstant/index.html.twig', [
            'translationConstants' => $this->findBy('AppBundle:TranslationConstant', [], ['id' => 'DESC']),
        ]);

        return $response;
    }

    /**
     * @Route("/translation-constants/new", name="new-translation-constants")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function newAction(Request $request)
    {
        $translationConstant = new TranslationConstant();

        $form = $this->createForm(TranslationConstantForm::class, $translationConstant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && 'POST' == $request->getMethod()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($translationConstant);
            $em->flush();

            $response = $this->redirectToRoute('translation-constants');
        } else {
            $response = $this->render('@VidiaAdmin/translationConstant/translation-constant.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $response;
    }

    /**
     * @Route("/translation-constants/{translationConstant}", name="translation-constant")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function editAction(TranslationConstant $translationConstant, Request $request)
    {
        $form = $this->createForm(TranslationConstantForm::class, $translationConstant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && 'POST' == $request->getMethod()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($translationConstant);
            $em->flush();

            $response = $this->redirectToRoute('translation-constants');
        } else {
            $response = $this->render('@VidiaAdmin/translationConstant/translation-constant.html.twig', [
                'form' => $form->createView(),
                'translationConstantValues' => $this->findBy('AppBundle:TranslationConstantValue', ['translationConstant' => $translationConstant->getId()]),
                'translationConstant' => $translationConstant,
            ]);
        }

        return $response;
    }

    /**
     * @Route("/translation-constants/{translationConstant}", name="delete-translation-constant")
     * @Method({"DELETE"})
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(TranslationConstant $translationConstant, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($translationConstant);
        $em->flush();

        $response = $this->redirectToRoute('translation-constants');

        return $response;
    }
}
