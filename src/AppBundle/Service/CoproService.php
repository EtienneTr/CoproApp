<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 08/12/2017
 * Time: 15:19
 */

namespace AppBundle\Service;


use Doctrine\ORM\EntityManager;

class CoproService
{
    private $repo;
    private $em;

    public function __construct(EntityManager $entityManager, $entityName)
    {
        $this->em = $entityManager;
        $this->repo = $this->em->getRepository($entityName);
    }

    public function findAll()
    {
        return $this->repo->findAll();
    }

    public function findOne($id)
    {
        return $this->repo->find($id);
    }

    public function create($elem)
    {
        $this->em->persist($elem);
        $this->em->flush();
    }

    public function update($elem)
    {
        $this->em->merge($elem);
        $this->em->flush();
    }

    public function remove($elem)
    {
        $this->em->remove($elem);
        $this->em->flush();
    }

}