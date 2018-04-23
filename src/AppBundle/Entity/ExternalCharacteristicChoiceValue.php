<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * ExternalCharacteristicChoiceValue.
 *
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity()
 * @UniqueEntity(fields={"value"})
 */
class ExternalCharacteristicChoiceValue
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
     * @ORM\ManyToOne(targetEntity="ExternalCharacteristic", inversedBy="choiceValues")
     * @ORM\JoinColumn(name="external_characteristic_id", referencedColumnName="id", onDelete="CASCADE")
     * @AppAssert\IsValidExternalCharacteristic()
     */
    protected $externalCharacteristic;

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
     * @return ExternalCharacteristicChoiceValue
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
     * Set externalCharacteristic.
     *
     * @param \AppBundle\Entity\ExternalCharacteristic $externalCharacteristic
     *
     * @return ExternalCharacteristicChoiceValue
     */
    public function setExternalCharacteristic(\AppBundle\Entity\ExternalCharacteristic $externalCharacteristic = null)
    {
        $this->externalCharacteristic = $externalCharacteristic;

        return $this;
    }

    /**
     * Get externalCharacteristic.
     *
     * @return \AppBundle\Entity\ExternalCharacteristic
     */
    public function getExternalCharacteristic()
    {
        return $this->externalCharacteristic;
    }
}
