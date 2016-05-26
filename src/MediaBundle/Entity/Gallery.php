<?php

namespace MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Gallery
 *
 * @ORM\Table(name="galleries")
 * @ORM\Entity()
 */
class Gallery
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Image[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="MediaBundle\Entity\Image", cascade={"persist"})
     */
    private $images;

    /**
     * Init
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
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
     * Set images
     *
     * @param Image[]|ArrayCollection $images
     *
     * @return Gallery
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Get images
     *
     * @return Image[]|ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param $format
     *
     * @return ArrayCollection
     */
    public function getImagesByFormat($format)
    {
        $images = new ArrayCollection();
        foreach ($this->images as $image) {
            if ($image->getFormat() == $format) {
                $images->add($image);
            }
        }

        return $images;
    }

    /**
     * @param Image $image
     *
     * @return $this
     */
    public function addImage(Image $image)
    {
        $this->images->add($image);

        return $this;
    }

    /**
     * Remove image
     *
     * @param \MediaBundle\Entity\Image $image
     */
    public function removeImage(\MediaBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }
}
