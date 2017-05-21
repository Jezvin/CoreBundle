<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 21/05/17
 * Time: 10:44
 */

namespace Umbrella\CoreBundle\DataTable\Model\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class DateColumn
 * @package Umbrella\CoreBundle\DataTable\Model\Column
 */
class DateColumn extends PropertyColumn
{
    /**
     * @var string
     */
    public $format = 'd/m/Y H:i';

    /**
     * @param $entity
     * @return mixed
     */
    public function getPropertyValue($entity)
    {
        $value = $this->accessor->getValue($entity, $this->propertyPath);
        if ($value instanceof \DateTime) {
            return $value->format($this->format);
        } else {
            return $value;
        }
    }
    
    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        parent::setOptions($options);
        $this->format = ArrayUtils::get($options, 'format', $this->format);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefined(array(
            'format',
        ));
    }
}