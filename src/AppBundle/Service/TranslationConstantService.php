<?php

namespace AppBundle\Service;

use AppBundle\Entity\TranslationConstant;
use AppBundle\Entity\TranslationConstantValue;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TranslationConstantService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform(&$translationConstants, Request $request)
    {
        $locale = $request->getLocale();

        $newTranslationConstants['locale'] = $locale;

        /**
         * @var TranslationConstant
         */
        foreach ($translationConstants as $translationConstant) {
            /**
             * @var TranslationConstantValue
             */
            foreach ($translationConstant->getValues() as $value) {
                if ($value->getLanguage()->getLocale() === $locale) {
                    $newTranslationConstants['data'][$translationConstant->getName()] = $value->getValue();
                }
            }
        }

        return $newTranslationConstants;
    }
}
