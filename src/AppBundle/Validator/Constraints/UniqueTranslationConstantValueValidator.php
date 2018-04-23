<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Language;
use AppBundle\Entity\TranslationConstant;
use AppBundle\Entity\TranslationConstantValue;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueTranslationConstantValueValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($entity, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueTranslationConstantValue) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\UniqueTranslationConstantValue');
        }

        if ($entity->getTranslationConstant() instanceof TranslationConstant && $entity->getLanguage() instanceof Language && $this->isExist($entity)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

    private function isExist(TranslationConstantValue $entity)
    {
        $repository = $this->em->getRepository('AppBundle:TranslationConstantValue');

        if (empty($entity->getId())) {
            $translationConstantValue = $repository->findBy(array(
                'language' => $entity->getLanguage()->getId(),
                'translationConstant' => $entity->getTranslationConstant()->getId(), ));
        } else {
            $translationConstantValue = $repository->findBy(array(
                'value' => $entity->getValue(),
                'language' => $entity->getLanguage()->getId(),
                'translationConstant' => $entity->getTranslationConstant()->getId(), ));
        }

        $response = empty($translationConstantValue) ? false : true;

        return $response;
    }
}
