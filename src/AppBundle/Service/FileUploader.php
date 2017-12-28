<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 10:37
 */

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\File;

class FileUploader
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function uploadFile($file)
    {   
        #upload
        $pathName = $this->upload($file);
        #save
        $uplFile = new File();
        $uplFile->setExtension($file->getClientOriginalExtension());
        $uplFile->setPath($this->getUploadDir());
        $uplFile->setFile($pathName);
        $uplFile->setName($file->getClientOriginalName());

        return $uplFile;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->getClientOriginalExtension();

        $file->move($this->getUploadDir(), $fileName);

        return $fileName;
    }

    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    public function remove($file)
    {
        $fullPath = $file->getPath()."/".$file->getFile();
        if(file_exists($fullPath)) unlink($fullPath);
    }
}