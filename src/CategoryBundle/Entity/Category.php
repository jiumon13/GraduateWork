<?php

namespace CategoryBundle\Entity;

use CommonBundle\Entity\MetaData;
use ContentBundle\Entity\Article;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CommonBundle\Entity\EnablableEntityTrait;
use MediaBundle\Entity\Image;
use StoreBundle\Entity\Product;
use Tools\AliasGenerator;

/**
 * Item
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Category
{
    use EnablableEntityTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="CategoryBundle\Entity\Category", inversedBy="child")
     */
    private $parent;

    /**
     * @var Category[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CategoryBundle\Entity\Category", mappedBy="parent")
     */
    private $child;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255)
     */
    private $alias;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="MediaBundle\Entity\Image", cascade={"persist"})
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight = 0;

    /**
     * @var Product[]
     *
     * @ORM\ManyToMany(targetEntity="StoreBundle\Entity\Product", inversedBy="categories")
     */
    private $products;

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
     * Initialize
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->child = new ArrayCollection();
        $this->meta = new MetaData();
    }

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
     * Set parent
     *
     * @param Category $parent
     *
     * @return Category
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
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
     * Set alias
     *
     * @param string $alias
     *
     * @return Category
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
     * Set image
     *
     * @param Image $image
     *
     * @return Category
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return Category
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function generateAlias()
    {
        $this->alias = empty($this->alias)
            ? AliasGenerator::generate($this->name)
            : $this->alias;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name ?: '';
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
     * Add product
     *
     * @param Product $product
     *
     * @return Category
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * @return Category[]
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param Category[] $child
     */
    public function setChild($child)
    {
        $this->child = $child;
    }

    /**
     * Add child
     *
     * @param \CategoryBundle\Entity\Category $child
     *
     * @return Category
     */
    public function addChild(Category $child)
    {
        $this->child->add($child);

        return $this;
    }

    /**
     * Remove child
     *
     * @param Category $child
     */
    public function removeChild(Category $child)
    {
        $this->child->removeElement($child);
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
     * @return $this
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
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
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * @return $this
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;

        return $this;
    }
}
