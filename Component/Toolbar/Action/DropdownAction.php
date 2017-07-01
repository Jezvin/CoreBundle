<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 29/06/17
 * Time: 19:38
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Action;


use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class DropdownAction
 */
class DropdownAction extends Action
{
    /**
     * @var array
     */
    public $children;

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        parent::setOptions($options);
        $this->children = ArrayUtils::get($options, 'children');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'children'
        ));
        $resolver->setAllowedTypes('children', 'array');

        $resolver->setDefault('class', 'btn btn-default btn-flat');
        $resolver->setDefault('children', array());
        $resolver->setDefault('template', 'UmbrellaCoreBundle:Toolbar\Action:action_dropdown.html.twig');

        $childBuilder = new ActionsBuilder($this->container);
        $resolver->setNormalizer('children', function($options, $value) use ($childBuilder) {
            foreach ($value as $id => $params) {
               $childBuilder->add($id, $params['type'], $params['options']);
           }
           return $childBuilder->getActions();
        });
    }
}