<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 19:09
 */

namespace Umbrella\CoreBundle\DataTable\Model\Column;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class EntityColumn
 * @package Umbrella\CoreBundle\DataTable\Model\Column
 */
class EntityColumn extends Column
{
    /**
     * @var string
     */
    public $propertyPath;

    /**
     * @var string
     */
    public $dqlPart;

    /**
     * @var PropertyAccess
     */
    protected $accessor;

    /**
     * EntityColumn constructor.
     * @param $id
     */
    public function __construct($id)
    {
        parent::__construct($id);
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param array $options
     */
    public function resolveOptions(array $options = array())
    {
        parent::resolveOptions($options);
        $this->propertyPath = ArrayUtils::get($options, 'property_path', $this->id);
    }

    /**
     * @param $result
     * @return string
     */
    public function render($result)
    {
        return $this->getValue($result);
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