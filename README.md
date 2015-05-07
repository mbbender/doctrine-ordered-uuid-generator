This library is under development and has not been tested with anything other than the most basic creating of an entity. 
Relationships have not been tested. Hydration has not been tested. Performance has not been tested.

The purpose of this Doctrine2 Id generator is to increase performance when using a UUID for entities. 
It creates an ordered UUID based on the methods described in Karthik Appigatla's article (http://www.percona.com/blog/2014/12/19/store-uuid-optimized-way/).

#Installation and Usage

Add to composer require
```JSON
"mbbender/doctrine-ordered-uuid-generator":"dev-master"
```

##Register Type with Entity Manager
```PHP
Doctrine\DBAL\Types\Type::addType('ordered_guid','Mbbender\Doctrine\DBAL\Types\OrderedGuidType');
```

###In Laravel
Install https://github.com/atrauzzi/laravel-doctrine

In config/doctrine.php add
```
'custom_type' => [
  `'ordered_guid' => 'Mbbender\Doctrine\DBAL\Types\OrderedGuidType'`
]
```

##Implement In Entity
```PHP
 /**
 * @ORM\Id
 * @ORM\GeneratedValue(strategy="CUSTOM")
 * @ORM\Column(type="ordered_guid")
 * @ORM\CustomIdGenerator(class="Mbbender\Doctrine\ORM\Id\OrderedGuidGenerator")
 */
private $id;
```

#Notes
Basic testing done with MySQL and Sqlite.

#Contributions
If you'd like to add a database system you just have to add a strategy for generating the
UUID in the form stated in the linked article above for your database system in the OrderedGuidTYpe file.