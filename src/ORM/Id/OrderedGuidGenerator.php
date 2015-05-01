<?php namespace Bender\Doctrine\ORM\Id;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

class OrderedGuidGenerator extends AbstractIdGenerator{

    /**
     * Generates an ordered UUID identifier for an entity.
     *
     * Currently only supported by MySQL.
     *
     * @param EntityManager|EntityManager $em
     * @param \Doctrine\ORM\Mapping\Entity $entity
     * @return mixed
     */
    public function generate(EntityManager $em, $entity)
    {
        // Check for MySQL (only supported database)
        if($em->getConnection()->getDatabase()->getName() !== 'mysql')
            throw DBALException::notSupported($em->getConnection()->getDatabase()->getName());

        return 'SELECT '.$this->getOrderedGuidExpression()
            .' FROM (SELECT '.$em->getConnection()->getDatabasePlatform()->getGuidExpression()
            .' as uuid) as t1';
    }

    protected function getOrderedGuidExpression()
    {
        return 'UNHEX(CONCAT(SUBSTR(uuid, 15, 4),SUBSTR(uuid, 10, 4),SUBSTR(uuid, 1, 8),SUBSTR(uuid, 20, 4),SUBSTR(uuid, 25))) FROM (SELECT UUID() as uuid) as t1';
    }
}