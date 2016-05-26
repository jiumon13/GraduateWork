<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 21.05.15
 * Time: 23:04
 */

namespace MediaBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadableEntityTrait
{
    /**
     * @var string
     */
    private $temporary = '';

    /**
     * @var UploadedFile
     */
    private $file = null;

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getTemporary()
    {
        return $this->temporary;
    }

    /**
     * @param string $temporary
     */
    public function setTemporary($temporary)
    {
        $this->temporary = $temporary;
    }
}