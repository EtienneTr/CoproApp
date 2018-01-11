<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 10:55
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Charge;
use AppBundle\Entity\Contract;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Service\FileUploader;


class RemoveFileListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $file = null;

        if ($entity instanceof Contract) {
            $file = $entity->getAttachment();
        }

        if ($entity instanceof Charge) {
            $file = $entity->getFile();
        }
        
        if($file) {
            $this->uploader->remove($file);
        }

        return;
    }

}