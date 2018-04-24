<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vidia\AuthBundle\Entity\User as BaseUser;

/**
 * User.
 *
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("email", groups={"registration", "default"})
 * @UniqueEntity("username", groups={"registration", "default"})
 * @UniqueEntity("phone", groups={"registration", "default"})
 * @ORM\EntityListeners({
 *     "Vidia\AuthBundle\EventListener\UserEntityListener",
 *     "AppBundle\EventListener\DoctrineEntityListener\UserEntityListener"}
 * )
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @JMS\Expose
     * @JMS\Groups({"default", "Default", "auth", "user"})
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default", "auth"})
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     */
    protected $username;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default", "auth"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="255")
     */
    protected $fullName;

    /**
     * @JMS\Expose
     * @JMS\Groups({"user", "auth"})
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Email()
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    protected $email;

    /**
     * @JMS\Expose
     * @JMS\Groups({"user", "auth"})
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     * @Assert\NotBlank()
     */
    protected $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $password;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(min = 5)
     */
    protected $plainPassword = null;

    /**
     * @JMS\Expose
     * @JMS\Groups({"user", "auth"})
     * @ORM\Column(type="string", nullable=true, options={"default" : "ROLE_USER"})
     */
    protected $role;

    /**
     * @JMS\Expose
     * @JMS\Groups({"auth"})
     */
    protected $accessToken;

    /**
     * @JMS\Expose
     * @JMS\Groups({"auth"})
     */
    protected $refreshToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $salt;

    /**
     * @JMS\Expose
     * @JMS\Groups({"auth"})
     */
    protected $expirationDate;

    /**
     * @JMS\Expose
     * @JMS\Groups({"user", "auth"})
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $isVerified = 0;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default", "user", "auth"})
     * @ORM\OneToOne(targetEntity="Photo")
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $photo;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default","user", "auth"})
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex("/^\+380[69]\d{8}/")
     * @Assert\Length(min="13" ,max="14")
     */
    protected $phone;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default", "auth"})
     * @ORM\Column(type="enum_gender_type", nullable=false)
     * @Assert\NotBlank()
     */
    protected $gender;

    /**
     * @JMS\Expose
     * @JMS\Groups({"user", "auth"})
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

    /**
     * @JMS\Expose
     * @JMS\Groups({"user", "auth"})
     * @ORM\Column(type="datetime", nullable=true)
     *
     */
    protected $registrationDate;

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return array($this->role ? $this->role : 'ROLE_USER');
    }

    /**
     * @return User[]
     */
    public function getOwners()
    {
        return array(
            $this,
        );
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set fullName.
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set dateOfBirth.
     *
     * @param \DateTime $dateOfBirth
     *
     * @return User
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth.
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set role.
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Set isVerified.
     *
     * @param int $isVerified
     *
     * @return User
     */
    public function setIsVerified($isVerified)
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Get isVerified.
     *
     * @return int
     */
    public function getIsVerified()
    {
        return $this->isVerified;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set gender.
     *
     * @param enum_gender_type $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender.
     *
     * @return enum_gender_type
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set language.
     *
     * @param \AppBundle\Entity\Language $language
     *
     * @return User
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
     * Set photo.
     *
     * @param \AppBundle\Entity\Photo $photo
     *
     * @return User
     */
    public function setPhoto(\AppBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo.
     *
     * @return \AppBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set registrationDate
     *
     * @ORM\PrePersist()
     *
     * @return User
     */
    public function setRegistrationDate($registrationDate = null)
    {
        if ($registrationDate) {
            $this->registrationDate = $registrationDate;
        }
        else{
            $this->registrationDate = new \DateTime('now');
        }
        

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }
}
