<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 15.02.16
 * Time: 19:28
 */

namespace ContentBundle\Entity;

use CommonBundle\Entity\EnablableEntityTrait;
use CommonBundle\Entity\TimestampableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Tools\AliasGenerator;

/**
 * @ORM\Entity()
 * @ORM\Table(name="articles")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    use TimestampableEntityTrait,
        EnablableEntityTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }


    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return Page
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getContentFormat()
    {
        return 'richhtml';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function generateAlias()
    {
        $this->alias = empty($this->alias)
            ? AliasGenerator::generate($this->title)
            : $this->alias;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->title;
    }
}