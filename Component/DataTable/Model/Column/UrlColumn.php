<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/07/17
 * Time: 23:49
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model\Column;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class UrlColumn
 */
class UrlColumn extends PropertyColumn
{
    const TARGET_SELF = '_self';
    const TARGET_BLANK = '_blank';

    /**
     * @var string
     */
    public $target;

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function defaultRender($entity)
    {
        $value = htmlspecialchars($this->getPropertyValue($entity));
        return '<a href="' . $value . '" target="' . $this->target . '">' . $value . '</a>';
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        parent::setOptions($options);
        $this->target = ArrayUtils::get($options, 'target');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'target',
        ));

        $resolver->setDefault('target', self::TARGET_SELF);
    }
}