<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * TranslationConstantValue.
 *
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity()
 * @AppAssert\UniqueTranslationConstantValue()
 */
class TranslationConstantValue
{
    /**
     * @var int
     *
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     */
    protected $value;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    protected $language;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\ManyToOne(targetEntity="TranslationConstant", inversedBy="values")
     * @ORM\JoinColumn(name="translation_constant_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    protected $translationConstant;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return TranslationConstantValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set language.
     *
     * @param \AppBundle\Entity\Language $language
     *
     * @return TranslationConstantValue
     */
    public function setLanguage(\AppBundle\Entity\Language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language.
     *
     * @return \AppBundle\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set translationConstant.
     *
     * @param \AppBundle\Entity\TranslationConstant $translationConstant
     *
     * @return TranslationConstantValue
     */
    public function setTranslationConstant(\AppBundle\Entity\TranslationConstant $translationConstant = null)
    {
        $this->translationConstant = $translationConstant;

        return $this;
    }

    /**
     * Get translationConstant.
     *
     * @return \AppBundle\Entity\TranslationConstant
     */
    public function getTranslationConstant()
    {
        return $this->translationConstant;
    }
}
