<?php  namespace Bender\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class OrderedGuidType extends GuidType
{
    const ORDEREDGUID = 'ordered_guid';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $field['length'] = 36;
        $field['fixed']  = true;

        $fieldDeclaration = array_merge($field, $fieldDeclaration);

        return $platform->getBinaryTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::ORDEREDGUID;
    }
}