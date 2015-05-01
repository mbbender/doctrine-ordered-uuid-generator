<?php  namespace Mbbender\Doctrine\DBAL\Types;

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
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
            //TODO: Throw exception? Never should have a null value here I don't think
        }

        // TODO: Throw exception if $value is not a resource. Not sure how you can not have a resource.
        $value = (is_resource($value)) ? stream_get_contents($value) : $value;

        return bin2hex($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::ORDEREDGUID;
    }
}