<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 19:09
 */

namespace Umbrella\CoreBundle\DataTable;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class EntityColumn
 * @package Umbrella\CoreBundle\Model\Table
 */
class EntityColumn extends Column
{
    /**
     * @var string
     */
    protected $propertyPath;

    /**
     * @var string
     */
    protected $dqlPart;

    /**
     * @var PropertyAccess
     */
    protected $accessor;

    /**
     * EntityColumn constructor.
     * @param $id
     * @param array $options
     */
    public function __construct($id, array $options = array())
    {
        parent::__construct($id, $options);
        $this->propertyPath = ArrayUtils::get($options, 'property_path', $id);
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function getValue($entity)
    {
        if ($this->propertyPath === null) {
            return $entity;
        } else {
            return $this->accessor->getValue($entity, $this->propertyPath);
        }
    }
}