<?php

namespace Vidia\AdminBundle\Controller;

use AppBundle\Entity\TranslationConstant;
use AppBundle\Entity\TranslationConstantValue;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vidia\AdminBundle\Form\TranslationConstantValueForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TranslationConstantValuesController extends BaseController
{
    /**
     * @Route("/translation-constant-values/new", name="new-translation-constant-values")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function newAction(Request $request)
    {
        $translationConstant = $this->findOneBy('AppBundle:TranslationConstant', ['id' => $request->get('translation-constant')]);

        $translationConstantValue = new TranslationConstantValue();
        $translationConstantValue->setTranslationConstant($translationConstant);

        $form = $this->createForm(TranslationConstantValueForm::class, $translationConstantValue);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && 'POST' == $request->getMethod()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($translationConstantValue);
            $em->flush();

            $response = $this->redirectToRoute('translation-constant', ['translationConstant' => $translationConstantValue->getTranslationConstant()->getId()]);
        } else {
            $response = $this->render('@VidiaAdmin/translationConstantValue/translation-constant-value.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $response;
    }

    /**
     * @Route("/translation-constant-values/{translationConstantValue}", name="edit-translation-constant-value")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function editAction(TranslationConstantValue $translationConstantValue, Request $request)
    {
        $form = $this->createForm(TranslationConstantValueForm::class, $translationConstantValue);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && 'POST' == $request->getMethod()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($translationConstantValue);
            $em->flush();

            $response = $this->redirectToRoute('translation-constant', ['translationConstant' => $translationConstantValue->getTranslationConstant()->getId()]);
        } else {
            $response = $this->render('@VidiaAdmin/translationConstantValue/translation-constant-value.html.twig', [
                'form' => $form->createView(),
            ]);
        }


        return $response;
    }

    /**
     * @Route("/translation-constant-values/{translationConstantValue}", name="delete-translation-constant-value")
     * @Method({"DELETE"})
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(TranslationConstantValue $translationConstantValue, Request $request)
    {
        $translationConstantId = $translationConstantValue->getTranslationConstant()->getId();

        $em = $this->getDoctrine()->getManager();

        $em->remove($translationConstantValue);
        $em->flush();

        $response = new Response();

        return $response;
    }
}
