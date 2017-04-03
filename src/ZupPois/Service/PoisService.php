<?php
namespace ZupPois\Service;

use ZupPois\Service\Interfaces\EntityService;

class PoisService extends BaseService implements EntityService
{
    public function getAll()
    {
        return $this->em->getRepository('ZupPois\Entities\Pois')->getResults();
    }

    public function getRecord($id)
    {
        return $this->em->getRepository('ZupPois\Entities\Pois')->find($id);
    }
}