<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ExternalCharacteristic.
 *
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity()
 * @UniqueEntity(fields={"name"})
 */
class ExternalCharacteristic
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
     * @ORM\OneToMany(targetEntity="ExternalCharacteristicChoiceValue", mappedBy="externalCharacteristic")
     */
    protected $choiceValues;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\Column(type="enum_external_characteristic_type", nullable=false)
     * @Assert\NotBlank()
     */
    protected $type;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->choiceValues = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ExternalCharacteristic
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
     * Set type.
     *
     * @param enum_external_characteristic_type $type
     *
     * @return ExternalCharacteristic
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return enum_external_characteristic_type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add choiceValue.
     *
     * @param \AppBundle\Entity\ExternalCharacteristicChoiceValue $choiceValue
     *
     * @return ExternalCharacteristic
     */
    public function addChoiceValue(\AppBundle\Entity\ExternalCharacteristicChoiceValue $choiceValue)
    {
        $this->choiceValues[] = $choiceValue;

        return $this;
    }

    /**
     * Remove choiceValue.
     *
     * @param \AppBundle\Entity\ExternalCharacteristicChoiceValue $choiceValue
     */
    public function removeChoiceValue(\AppBundle\Entity\ExternalCharacteristicChoiceValue $choiceValue)
    {
        $this->choiceValues->removeElement($choiceValue);
    }

    /**
     * Get choiceValues.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChoiceValues()
    {
        return $this->choiceValues;
    }
}
