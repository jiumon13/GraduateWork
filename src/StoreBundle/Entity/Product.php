<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 18.07.15
 * Time: 20:15
 */

namespace StoreBundle\Entity;

use CommonBundle\Entity\MetaData;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\OneToMany;
use MediaBundle\Entity\Gallery;
use CategoryBundle\Entity\Category;
use CategoryBundle\Entity\Tag;
use CommonBundle\Entity\EnablableEntityTrait;
use CommonBundle\Entity\TimestampableEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use MediaBundle\Entity\Image;
use StoreBundle\Entity\ProductService;
use Tools\AliasGenerator;

/**
 * @Entity(repositoryClass="StoreBundle\Entity\Repository\ProductRepository")
 * @Table(name="products")
 * @HasLifecycleCallbacks()
 */
class Product
{
    use EnablableEntityTrait,
        TimestampableEntityTrait;

    /**
     * @var int
     * @Column(type="integer")
     * @Id()
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Column(type="string", length=128)
     */
    private $name;

    /**
     * @var string
     * @Column(type="string", length=128)
     */
    private $alias;

    /**
     * @var int
     * @Column(type="integer")
     */
    private $price;

    /**
     * @var Currency
     * @ManyToOne(targetEntity="StoreBundle\Entity\Currency")
     */
    private $currency;

    /**
     * @var ArrayCollection|Tag[]
     *
     * @ManyToMany(targetEntity="CategoryBundle\Entity\Tag", cascade={"persist"})
     */
    private $tags;

    /**
     * @var Gallery
     *
     * @ManyToOne(targetEntity="MediaBundle\Entity\Gallery", cascade={"persist"})
     */
    private $gallery;

    /**
     * @var ArrayCollection|Category[]
     *
     * @ManyToMany(targetEntity="CategoryBundle\Entity\Category", mappedBy="products")
     */
    private $categories;

    /**
     * @var ArrayCollection|ProductHasAttribute[]
     *
     * @OneToMany(targetEntity="StoreBundle\Entity\ProductHasAttribute", mappedBy="product", cascade={"persist"}, orphanRemoval=true)
     */
    private $attributes;

    /**
     * @var ArrayCollection|ProductService[]
     *
     * @OneToMany(targetEntity="StoreBundle\Entity\ProductService", mappedBy="product", cascade={"persist"}, orphanRemoval=true, indexBy="id")
     */
    private $services;

    /**
     * @var string
     *
     * @Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @var MetaData
     *
     * @Embedded(class="CommonBundle\Entity\MetaData")
     */
    private $meta;

    /**
     * @var int
     * @Column(type="integer", nullable=true)
     */
    private $popularity;

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->services = new ArrayCollection();
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     *
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return round($this->price / 100, 2);
    }

    /**
     * @return int
     */
    public function getPriceISO()
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = round($price * 100);

        return $this;
    }

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     *
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Tag[]|ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag[]|ArrayCollection $tags
     *
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name ?: '';
    }

    /**
     * @PrePersist()
     * @PreUpdate()
     */
    public function generateAlias()
    {
        $this->alias = empty($this->alias)
            ? AliasGenerator::generate($this->name)
            : $this->alias;
    }

    /**
     * @return Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param Gallery $gallery
     *
     * @return $this
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * @return \CategoryBundle\Entity\Category[]|ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return Image
     */
    public function getPreview()
    {
        if (!$this->gallery) {
            return null;
        }

        $images = $this->gallery->getImagesByFormat(Image::PRODUCT_PREVIEW);

        return $images->count() > 0 ? $images->first() : null;
    }

    /**
     * @return Image
     */
    public function getView()
    {
        if (!$this->gallery) {
            return null;
        }

        $images = $this->gallery->getImagesByFormat(Image::PRODUCT_SHOW);

        return $images->count() > 0 ? $images->first() : null;
    }

    /**
     * @return ArrayCollection|Image[]
     */
    public function getViews()
    {
        if (!$this->gallery) {
            return null;
        }

        return $this->gallery->getImagesByFormat(Image::PRODUCT_SHOW);
    }

    /**
     * @return ArrayCollection|ProductHasAttribute[]
     */
    public function getAttributes()
    {
        return $this->attributes;
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
     * Add tag
     *
     * @param \CategoryBundle\Entity\Tag $tag
     *
     * @return Product
     */
    public function addTag(\CategoryBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \CategoryBundle\Entity\Tag $tag
     */
    public function removeTag(\CategoryBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Add category
     *
     * @param \CategoryBundle\Entity\Category $category
     *
     * @return Product
     */
    public function addCategory(\CategoryBundle\Entity\Category $category)
    {
        $category->addProduct($this);
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \CategoryBundle\Entity\Category $category
     */
    public function removeCategory(\CategoryBundle\Entity\Category $category)
    {
        $category->removeProduct($this);
        $this->categories->removeElement($category);
    }

    /**
     * Add attribute
     *
     * @param \StoreBundle\Entity\ProductHasAttribute $attribute
     *
     * @return Product
     */
    public function addAttribute(\StoreBundle\Entity\ProductHasAttribute $attribute)
    {
        $attribute->setProduct($this);
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * Remove attribute
     *
     * @param \StoreBundle\Entity\ProductHasAttribute $attribute
     */
    public function removeAttribute(\StoreBundle\Entity\ProductHasAttribute $attribute)
    {
        $this->attributes->removeElement($attribute);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Add service
     *
     * @param ProductService $service
     *
     * @return Product
     */
    public function addService(ProductService $service)
    {
        $service->setProduct($this);
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param ProductService $service
     */
    public function removeService(ProductService $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return Collection
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return string
     */
    public function getDescriptionFormat()
    {
        return 'richhtml';
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
     * @return $this
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @return int
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * @param int $popularity
     * @return Product
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;
        return $this;
    }
}
