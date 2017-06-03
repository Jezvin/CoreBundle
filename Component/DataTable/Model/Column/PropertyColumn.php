<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 19:09.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class PropertyColumn.
 */
class PropertyColumn extends Column
{
    /**
     * @var string
     */
    public $propertyPath;

    /**
     * @var PropertyAccess
     */
    protected $accessor;

    /**
     * EntityColumn constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        parent::__construct($id);
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function defaultRender($entity)
    {
        return (string) $this->getPropertyValue($entity);
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function getPropertyValue($entity)
    {
        return $this->accessor->getValue($entity, $this->propertyPath);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        parent::setOptions($options);
        $this->propertyPath = ArrayUtils::get($options, 'property_path');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'property_path',
        ));

        $resolver->setDefault('property_path', $this->id);
    }
}
