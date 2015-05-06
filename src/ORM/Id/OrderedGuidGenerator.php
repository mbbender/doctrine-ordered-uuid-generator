<?php namespace Mbbender\Doctrine\ORM\Id;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

/**
 * Class OrderedGuidGenerator
 * @package Mbbender\Doctrine\ORM\Id
 */
class OrderedGuidGenerator extends AbstractIdGenerator {

    /**
     * Generates an ordered UUID identifier for an entity.
     *
     * Currently only supports by MySQL and Sqlite.
     *
     * @param EntityManager|EntityManager $em
     * @param \Doctrine\ORM\Mapping\Entity $entity
     * @return mixed
     * @throws \Exception
     */
    public function generate(EntityManager $em, $entity)
    {
        $conn = $em->getConnection();
        $name = $conn->getDatabasePlatform()->getName();

        switch ($name)
        {
            case 'mysql':
                $sql = $this->getMySqlOrderedGuidExpression();
                break;
            case 'sqlite':
                $sql = $this->getSqliteOrderedGuidExpression();
                break;
            default:
                throw new \Exception("Database type [${$name}] not supported by OrderedGuidGenerator. Submit a pull request!");
        }

        $id = $conn->query($sql)->fetchColumn(0);

        switch ($name)
        {
            case 'sqlite':
                return hex2bin($id);
            default:
                return $id;
        }
    }

    /**
     * @param $uuid_expression
     * @return string
     */
    protected function getOrderedGuidExpression($uuid_expression)
    {
        return "SELECT UNHEX(CONCAT(SUBSTR(uuid, 15, 4),SUBSTR(uuid, 10, 4),SUBSTR(uuid, 1, 8),SUBSTR(uuid, 20, 4),SUBSTR(uuid, 25))) FROM ({$uuid_expression} as uuid) as t1";
    }

    /**
     * @return string
     */
    protected function getMySqlOrderedGuidExpression()
    {
        return $this->getOrderedGuidExpression("SELECT UUID()");
    }

    /**
     * @return string
     */
    protected function getSqliteOrderedGuidExpression()
    {
        return "select '1' || substr( hex( randomblob(2)), 2) || hex( randomblob(2)) || hex( randomblob(4)) || substr('AB89', 1 + (abs(random()) % 4) , 1) || substr(hex(randomblob(2)), 2) || hex(randomblob(6))";
    }
}