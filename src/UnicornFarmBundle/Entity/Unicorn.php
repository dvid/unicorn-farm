<?php

namespace UnicornFarmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UnicornFarmBundle\Entity\User;

/**
 * Unicorn
 *
 * @ORM\Table(name="unicorn")
 * @ORM\Entity(repositoryClass="UnicornFarmBundle\Repository\UnicornRepository")
 */
class Unicorn
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="available", type="boolean", options={"default" : 1})
     */
    private $available;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * Set name
     *
     * @param string $name
     *
     * @return Unicorn
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Unicorn
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get available
     *
     * @return boolean
     */
    public function isAvailable()
    {
        return $this->available;
    }

    /**
     * Set available
     *
     * @param string $available
     *
     * @return Unicorn
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Unicorn
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}

