<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;
use JMS\Serializer\Annotation as JMS;
use Vidia\AuthBundle\Entity\HasOwnerInterface;
use Vidia\AuthBundle\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * UserExternalCharacteristic.
 *
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity()
 * @ORM\EntityListeners({
 *     "AppBundle\EventListener\DoctrineEntityListener\SetOwnerListener"}
 * )
 * @UniqueEntity( fields={"user", "externalCharacteristic"}, errorPath="externalCharacteristic")
 */
class UserExternalCharacteristic implements HasOwnerInterface
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
     * @JMS\Groups({"detail"})
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\ManyToOne(targetEntity="ExternalCharacteristic")
     * @ORM\JoinColumn(name="external_characteristic_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $externalCharacteristic;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     * @AppAssert\ValidateGrowth(message="growth_validation_error")
     */
    protected $value;

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
     * @return UserExternalCharacteristic
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
     * Set user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserExternalCharacteristic
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set externalCharacteristic.
     *
     * @param \AppBundle\Entity\ExternalCharacteristic $externalCharacteristic
     *
     * @return UserExternalCharacteristic
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

    /**
     * @return User[]
     */
    public function getOwners()
    {
        return [$this->getUser()];
    }
}
