<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 05/01/2018
 * Time: 16:12
 */

namespace AppBundle\Controller;

use AppBundle\Service\FileManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Class FileController
 * @package AppBundle\Controller
 */
class FileController extends Controller
{
    /**
     * @Route("/download/{id}", name="download_file")
     * @Method({"GET"})
     */
    public function fileDownloadAction(FileManager $fileManager, $id)
    {
        $file = $fileManager->findOne($id);

        if(!$file){
            throw new NotFoundResourceException("Requested file doesn't exist");
        }

        $filePath = $file->getPath()."/".$file->getFile();
        $filename = $file->getName();

        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($filePath));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
        $response->headers->set('Content-length', filesize($filePath));

        // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent(file_get_contents($filePath));

        return$response;
    }
}