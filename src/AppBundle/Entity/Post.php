<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Vidia\AuthBundle\Entity\HasOwnerInterface;
use Vidia\AuthBundle\Entity\User;

/**
 * Post.
 *
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\EntityListeners({
 *     "AppBundle\EventListener\DoctrineEntityListener\SetOwnerListener",
 *     "AppBundle\EventListener\DoctrineEntityListener\PostEntityListener"})
 */
class Post implements HasOwnerInterface
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
     * @ORM\Column(type="text", length=1000, nullable=true)
     * @Assert\Length(max="1000")
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\Column(type="datetime")
     */
    protected $creationDate;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\Column(type="smallint")
     */
    protected $isAnonymously = 0;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="post", cascade={"remove"})
     */
    protected $photos;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $place;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    protected $placeId;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $isDeleted;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $vkId;

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
     * Set description.
     *
     * @param string $description
     *
     * @return Post
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set creationDate.
     *
     * @param \DateTime $creationDate
     *
     * @return Post
     * @ORM\PrePersist()
     */
    public function setCreationDate($creationDate = null)
    {
        if ($creationDate instanceof \DateTime) {
            $this->creationDate = $creationDate;
        }
        elseif(empty($this->creationDate)) {
            $this->creationDate = new \DateTime('now');
        }

        return $this;
    }

    /**
     * Get creationDate.
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set isAnonymously.
     *
     * @param int $isAnonymously
     *
     * @return Post
     */
    public function setIsAnonymously($isAnonymously)
    {
        $this->isAnonymously = $isAnonymously;

        return $this;
    }

    /**
     * Get isAnonymously.
     *
     * @return int
     */
    public function getIsAnonymously()
    {
        return $this->isAnonymously;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Post
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
     * @return User[]
     */
    public function getOwners()
    {
        return [$this->getUser()];
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add photo.
     *
     * @param \AppBundle\Entity\Photo $photo
     *
     * @return Post
     */
    public function addPhoto(\AppBundle\Entity\Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo.
     *
     * @param \AppBundle\Entity\Photo $photo
     */
    public function removePhoto(\AppBundle\Entity\Photo $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set place.
     *
     * @param array $place
     *
     * @return Post
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place.
     *
     * @return array
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set placeId
     *
     * @param string $placeId
     *
     * @return Post
     */
    public function setPlaceId($placeId)
    {
        $this->placeId = $placeId;

        return $this;
    }

    /**
     * Get placeId
     *
     * @return string
     */
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * Set isDeleted
     *
     * @param integer $isDeleted
     *
     * @return Post
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return integer
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set deletedDate
     *
     * @param \DateTime $deletedDate
     *
     * @return Post
     *
     * @ORM\PreUpdate()
     */
    public function setDeletedDate()
    {
        $this->deletedDate = null;

        if ($this->getIsDeleted()) {
            $this->deletedDate = new \DateTime();

        }

        return $this;
    }

    /**
     * Get deletedDate
     *
     * @return \DateTime
     */
    public function getDeletedDate()
    {
        return $this->deletedDate;
    }

    /**
     * Set vkId
     *
     * @param string $vkId
     *
     * @return Post
     */
    public function setVkId($vkId)
    {
        $this->vkId = $vkId;

        return $this;
    }

    /**
     * Get vkId
     *
     * @return string
     */
    public function getVkId()
    {
        return $this->vkId;
    }
}
