<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TranslationConstant.
 *
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity()
 * @UniqueEntity(fields={"name"})
 */
class TranslationConstant
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
    protected $name;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\OneToMany(targetEntity="TranslationConstantValue", mappedBy="translationConstant")
     */
    protected $values;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name.
     *
     * @param string $name
     *
     * @return TranslationConstant
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add value.
     *
     * @param \AppBundle\Entity\TranslationConstantValue $value
     *
     * @return TranslationConstant
     */
    public function addValue(\AppBundle\Entity\TranslationConstantValue $value)
    {
        $this->values[] = $value;

        return $this;
    }

    /**
     * Remove value.
     *
     * @param \AppBundle\Entity\TranslationConstantValue $value
     */
    public function removeValue(\AppBundle\Entity\TranslationConstantValue $value)
    {
        $this->values->removeElement($value);
    }

    /**
     * Get values.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValues()
    {
        return $this->values;
    }
}
