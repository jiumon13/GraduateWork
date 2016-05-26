<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 31.10.15
 * Time: 13:30
 */

namespace MediaBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use MediaBundle\Entity\Image;

class DoctrineSubscriber implements EventSubscriber
{
    /**
     * @var
     */
    private $rootDir;

    /**
     * @param $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
        $this->uploadDir = 'uploads/images';
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
            'postPersist',
            'postUpdate',
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Image) {
            return;
        }
        $this->prepare($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Image) {
            return;
        }
        $this->prepare($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Image) {
            return;
        }
        $this->upload($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Image) {
            return;
        }
        $this->upload($entity);
    }

    /**
     * @param Image $image
     */
    private function prepare(Image $image)
    {
        if ($image->getFile() == null) {
            return;
        }
        $hash = md5(uniqid(mt_rand(), true));
        $full = sprintf('%s/%s/%s/%s.%s',
            $this->uploadDir,
            substr($hash, 0, 2),
            substr($hash, 2, 2),
            $hash,
            $image->getFile()->guessExtension()
        );
        $image->setFilename($full);
    }

    /**
     * @param Image $image
     */
    private function resize(Image $image)
    {
        list($width, $height) = getimagesize($image->getFile()->getPathname());
        $targetFormat = Image::$formats[$image->getFormat()];
        $targetHeight = $targetFormat['height'];
        $targetWidth = $targetFormat['width'];
        if ($width > $targetWidth || $height > $targetHeight) {
            if ($width > $height) {
                $scale = ($targetWidth / $width);
            } else {
                $scale = ($targetHeight / $height);
            }
            $oldWidth = $width;
            $oldHeight = $height;
            $width = round($width * $scale);
            $height = round($height * $scale);
            $source = imagecreatefromjpeg($image->getFile()->getPathname());
            $resized = imagecreatetruecolor($width, $height);
            imagecopyresampled($resized, $source, 0, 0, 0, 0, $width, $height, $oldWidth, $oldHeight);
            imagejpeg($resized, $image->getFile()->getPathname(), 90);
            imagedestroy($source);
            imagedestroy($resized);
        }
    }

    private function resizeForce(Image $image)
    {
        list($width, $height) = getimagesize($image->getFile()->getPathname());
        $targetFormat = Image::$formats[$image->getFormat()];
        $targetHeight = $targetFormat['height'];
        $targetWidth = $targetFormat['width'];

        $scale = min([$targetWidth / $width, $targetHeight / $height]);

        $oldWidth = $width;
        $oldHeight = $height;

        $width = round($width * $scale);
        $height = round($height * $scale);

        $source = imagecreatefromjpeg($image->getFile()->getPathname());

        // Prepare canvas
        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);

        $dx = (int) ($targetWidth - $width) / 2;
        $dy = (int) ($targetHeight - $height) / 2;

        imagecopyresampled($canvas, $source, $dx, $dy, 0, 0, $width, $height, $oldWidth, $oldHeight);
        imagejpeg($canvas, $image->getFile()->getPathname(), 90);
        imagedestroy($source);
        imagedestroy($canvas);
    }

    /**
     * @param Image $image
     */
    private function upload(Image $image)
    {
        if ($image->getFile() == null) {
            return;
        }
        $filename = pathinfo($image->getFilename(), PATHINFO_BASENAME);
        $dir = sprintf('%s/%s/%s/%s', $this->rootDir, $this->uploadDir, substr($filename, 0, 2), substr($filename, 2, 2));
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->resizeForce($image);
        $image->getFile()->move($dir, $filename);
    }
}