<?php

namespace Vidia\AdminBundle\Controller;

use AppBundle\Entity\TranslationConstant;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Vidia\AdminBundle\Form\TranslationConstantForm;

class TranslationConstantController extends BaseController
{
    /**
     * @Route("/translation-constants", name="translation-constants")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            $response = $this->redirectToRoute('sign-in');
        } else {
            $response = $this->render('@VidiaAdmin/translationConstant/index.html.twig', [
                'translationConstants' => $this->findBy('AppBundle:TranslationConstant', [], ['id' => 'DESC']),
            ]);
        }

        return $response;
    }

    /**
     * @Route("/translation-constants/new", name="new-translation-constants")
     */
    public function newAction(Request $request)
    {
        if (!$this->getUser() instanceof User) {
            $response = $this->redirectToRoute('sign-in');
        } else {
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
        }

        return $response;
    }

    /**
     * @Route("/translation-constants/{translationConstant}", name="translation-constant")
     * @Method({"GET", "POST"})
     */
    public function editAction(TranslationConstant $translationConstant, Request $request)
    {
        if (!$this->getUser() instanceof User) {
            $response = $this->redirectToRoute('sign-in');
        } else {
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
        }

        return $response;
    }

    /**
     * @Route("/translation-constants/{translationConstant}", name="delete-translation-constant")
     * @Method({"DELETE"})
     */
    public function deleteAction(TranslationConstant $translationConstant, Request $request)
    {
        if (!$this->getUser() instanceof User) {
            $response = $this->redirectToRoute('sign-in');
        } else {
            $em = $this->getDoctrine()->getManager();

            $em->remove($translationConstant);
            $em->flush();

            $response = $this->redirectToRoute('translation-constants');
        }

        return $response;
    }
}
