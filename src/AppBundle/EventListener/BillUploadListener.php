<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 10:55
 */

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Charge;
use AppBundle\Service\FileUploader;


class BillUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Charge) {
            $fileName = $entity->getBill();
            $this->uploader->remove($fileName);
        }

        return;
    }

}