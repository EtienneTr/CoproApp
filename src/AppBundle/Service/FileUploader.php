<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 10:37
 */

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getUploadDir(), $fileName);

        return $fileName;
    }

    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    public function remove($fileName)
    {
        $fullPath = $this->getUploadDir()."/".$fileName;
        if(file_exists($fullPath)) unlink($fullPath);
    }
}