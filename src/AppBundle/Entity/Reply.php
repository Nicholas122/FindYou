<?php


namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Reply
{
    /**
     * @Assert\NotBLank()
     */
    protected $messageBody;

    /**
     * @Assert\NotBLank()
     */
    protected $post;

    public function setMessageBody($messageBody)
    {
        $this->messageBody = $messageBody;

        return $this;
    }

    public function getMessageBody()
    {
        return $this->messageBody;
    }

    public function setPost(Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }
}