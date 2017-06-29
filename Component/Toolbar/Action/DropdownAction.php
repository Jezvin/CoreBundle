<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 29/06/17
 * Time: 19:38
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Action;


use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Routing\UmbrellaRoute;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class DropdownAction
 */
class DropdownAction extends Action
{
    const URL_TYPE = 'URL';
    const SEPARATOR_TYPE = 'SEPARATOR';

    /**
     * @var array
     */
    public $actions;

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        parent::setOptions($options);

        $this->actions = array();
        foreach ($options['actions'] as $action) {
            $resolvedAction = array();

            if (isset($action['action'])) {
                $resolvedAction['url'] = UmbrellaRoute::createFromOptions($action['action'])->generateUrl($this->router);
            }

            $resolvedAction['xhr'] = ArrayUtils::get($action, 'xhr', true);
            $resolvedAction['label'] = ArrayUtils::get($action, 'label');
            $resolvedAction['type'] = ArrayUtils::get($action, 'type', self::URL_TYPE);
            $this->actions[] = $resolvedAction;
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'actions'
        ));
        $resolver->setAllowedTypes('actions', 'array');

        $resolver->setDefault('class', 'btn btn-default btn-flat');
        $resolver->setDefault('actions', array());
        $resolver->setDefault('template', 'UmbrellaCoreBundle:Toolbar:action_dropdown.html.twig');
    }
}