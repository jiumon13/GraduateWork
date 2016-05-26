<?php
/**
 * Created by PhpStorm.
 * User: G-SHARK
 * Date: 4/14/2016
 * Time: 6:42 PM
 */

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping\Column;

trait EnablableEntityTrait
{
    /**
     * @var bool
     *
     * @Column(type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }
}