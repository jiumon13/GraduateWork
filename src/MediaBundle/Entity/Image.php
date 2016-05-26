<?php

namespace MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="images")
 * @ORM\Entity
 */
class Image
{
    use UploadableEntityTrait;

    const RESIZE_PROPORTIONALLY = 'proportionally';
    const FORCE = 'force';

    const CATALOG_PREVIEW = 'catalog_preview';
    const PRODUCT_PREVIEW = 'product_preview';
    const PRODUCT_SHOW = 'product_show';

    public static $formats = [
        self::CATALOG_PREVIEW => [
            'height' => 200,
            'width' => 300,
            'resize' => self::FORCE
        ],
        self::PRODUCT_PREVIEW => [
            'height' => 200,
            'width' => 300,
            'resize' => self::FORCE
        ],
        self::PRODUCT_SHOW => [
            'height' => 600,
            'width' => 500,
            'resize' => self::FORCE
        ]
    ];

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
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="format", type="string", length=255)
     */
    private $format;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=128, nullable=true)
     */
    private $alt;

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
     * Set filename
     *
     * @param string $filename
     *
     * @return Image
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->filename ?: '';
    }
}
