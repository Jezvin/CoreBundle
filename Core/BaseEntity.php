<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/05/17
 * Time: 18:50
 */
namespace Umbrella\CoreBundle\Core;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class BaseEntity
 * @package Umbrella\CoreBundle\Core
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class BaseEntity
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    public $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    public $updatedAt;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $now = new \DateTime('NOW');
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $now = new \DateTime('NOW');
        $this->updatedAt = $now;
    }

}