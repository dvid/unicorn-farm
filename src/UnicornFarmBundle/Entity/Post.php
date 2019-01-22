<?php

namespace UnicornFarmBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use UnicornFarmBundle\Entity\User;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="UnicornFarmBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="Unicorn", inversedBy="posts")
     * @ORM\JoinColumn(name="unicorn_id", referencedColumnName="id")
     */
    private $unicorn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modifiedAt", type="datetime")
     */
    private $modifiedAt;



    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->modifiedAt = null;
        $this->markUpdated();
    }

    /**
     * @return $this
     */
    protected function markUpdated()
    {
        $this->modifiedAt = new \DateTime();

        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Post
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get unicorn
     *
     * @return int
     */
    public function getUnicorn()
    {
        return $this->unicorn;
    }

    /**
     * Set unicorn
     *
     * @param integer $unicorn
     *
     * @return Post
     */
    public function setUnicorn($unicorn)
    {
        $this->unicorn = $unicorn;

        return $this;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Post
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }
}

