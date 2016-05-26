<?php

namespace ContentBundle\Entity;

use CommonBundle\Entity\EnablableEntityTrait;
use CommonBundle\Entity\MetaData;
use CommonBundle\Entity\TimestampableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Tools\AliasGenerator;

/**
 * Page
 *
 * @ORM\Table(name="pages")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Page
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
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="ContentBundle\Entity\Article")
     */
    private $article;

    /**
     * @var MetaData
     *
     * @ORM\Embedded(class="CommonBundle\Entity\MetaData")
     */
    private $meta;

    /**
     * Page constructor
     */
    public function __construct()
    {
        $this->meta = new MetaData();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Page
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Page
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     *
     * @return Page
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return MetaData
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param MetaData $meta
     *
     * @return Page
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;

        return $this;
    }
}
