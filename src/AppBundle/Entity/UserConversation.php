<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Vidia\AuthBundle\Entity\HasOwnerInterface;
use Vidia\AuthBundle\Entity\User;
use Swagger\Annotations as SWG;

/**
 * UserConversation.
 * @SWG\Definition()
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\EntityListeners({
 *     "AppBundle\EventListener\DoctrineEntityListener\SetOwnerListener",
 *     })
 */
class UserConversation implements HasOwnerInterface
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @JMS\Expose
     * @JMS\Groups({"default"})
     * @ORM\ManyToOne(targetEntity="Conversation")
     * @ORM\JoinColumn(name="parent_conversation_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parentConversation;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserConversation
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set parentConversation
     *
     * @param \AppBundle\Entity\Conversation $parentConversation
     *
     * @return UserConversation
     */
    public function setParentConversation(\AppBundle\Entity\Conversation $parentConversation = null)
    {
        $this->parentConversation = $parentConversation;

        return $this;
    }

    /**
     * Get parentConversation
     *
     * @return \AppBundle\Entity\Conversation
     */
    public function getParentConversation()
    {
        return $this->parentConversation;
    }

    /**
     * @return User[]
     */
    public function getOwners()
    {
        return [$this->getUser()];
    }
}
