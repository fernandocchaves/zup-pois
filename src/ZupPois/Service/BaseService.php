<?php
namespace ZupPois\Service;

class BaseService
{
    protected $em;
    public function __construct($em)
    {
        $this->em = $em;
    }

    public function save($record)
    {
        $this->em->persist($record);
        $this->em->flush();
    }

    public function delete($record)
    {
        $this->em->remove($record);
        $this->em->flush();
    }
}