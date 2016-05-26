<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * Class TimestampableTrait
 *
 * Don't forget add "@ORM\HasLifecycleCallbacks" to entity class
 *
 */
trait TimestampableEntityTrait
{
    /**
     * @var \DateTime
     *
     * @Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @PrePersist
     */
    public function timestampCreatedAt()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @PreUpdate
     */
    public function timestampUpdatedAt()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}