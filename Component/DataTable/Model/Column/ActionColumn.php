<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 19:10.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model\Column;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Routing\EntityRouteCollection;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class ActionColumn.
 */
class ActionColumn extends Column
{
    protected static $TEMPLATE = array(
        'show' => '<a data-xhr-href="__url__" title="See"><i class="fa fa-eye"></i></a>',
        'edit' => '<a data-xhr-href="__url__" title="Edit"><i class="fa fa-pencil"></i></a>',
        'delete' => '<a data-xhr-href="__url__" title="Delete" data-confirm="Confirm delete ?"<i class="fa fa-times text-red"></i></a>',
        '__default' => '<a data-xhr-href="__url__">__action__</a>',
    );

    /**
     * @var array
     */
    private $routeCollection;

    /**
     * ActionColumn constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->routeCollection = new EntityRouteCollection($container->get('router'));
    }

    /**
     * @param $id
     * @param $entity
     *
     * @return string
     */
    public function generateUrl($id, $entity)
    {
        return $this->routeCollection->generateUrl($id, $entity);
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function defaultRender($entity)
    {
        $html = '';
        foreach ($this->routeCollection as $id => $data) {
            $template = ArrayUtils::get(self::$TEMPLATE, $id, self::$TEMPLATE['__default']);
            $html .= str_replace(
                array(
                    '__url__',
                    '__action__',
                ),
                array(
                    $this->routeCollection->generateUrl($id, $entity),
                    $id,
                ),
                $template).'&nbsp;';
        }
        return $html;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        parent::setOptions($options);
        $this->routeCollection->set($options['actions']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'actions',
        ));
        $resolver->setAllowedTypes('actions', 'array');

        $resolver->setDefault('actions', array());
        $resolver->setDefault('orderable', false);
    }
}
