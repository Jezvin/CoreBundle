<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 21/05/17
 * Time: 10:44.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class DateColumn.
 */
class DateColumn extends PropertyColumn
{
    /**
     * @var string
     */
    public $format;

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function defaultRender($entity)
    {
        $value = $this->getPropertyValue($entity);
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
        $this->format = ArrayUtils::get($options, 'format');
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

        $resolver->setDefault('format', 'd/m/Y H:i');
    }
}
