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
        $conn = $em->getConnection();

        // Check for MySQL (only supported database)
        if($conn->getDatabasePlatform()->getName() !== 'mysql')
            throw DBALException::notSupported($conn->getDatabase()->getName());

        $sql = 'SELECT '.$this->getOrderedGuidExpression();

        return $conn->query($sql)->fetchColumn(0);
    }

    protected function getOrderedGuidExpression()
    {
        return 'UNHEX(CONCAT(SUBSTR(uuid, 15, 4),SUBSTR(uuid, 10, 4),SUBSTR(uuid, 1, 8),SUBSTR(uuid, 20, 4),SUBSTR(uuid, 25))) FROM (SELECT UUID() as uuid) as t1';
    }
}