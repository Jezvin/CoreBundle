<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/06/17
 * Time: 19:06
 */

namespace Umbrellac\CoreBundle\Component\Toolbar\Action;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ActionsBuilder
 */
class ActionsBuilder
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $actions = array();

    /**
     * @var array|null
     */
    private $resolvedActions = null;

    /**
     * DataTableBuilder constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $id
     * @param $actionClass
     * @param array $options
     *
     * @return $this
     */
    public function add($id, $actionClass, array $options = array())
    {
        $this->actions[$id] = array(
            'class' => $actionClass,
            'options' => $options,
        );

        return $this;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->actions[$id]);
    }

    /**
     * @return array
     */
    public function getActions()
    {
        if ($this->resolvedActions === null) {
            $this->resolvedActions = array();

            foreach ($this->actions as $id => $row) {
                $action = $this->createAction($row['class']);

                $resolver = new OptionsResolver();
                $action->configureOptions($resolver);
                $action->setOptions($resolver->resolve($row['options']));
                $this->resolvedActions[$id] = $action;
            }
        }
        return $this->resolvedActions;
    }

    /**
     * @param $class
     * @return Action
     */
    private function createAction($class)
    {
        if (!is_subclass_of($class, Action::class) && !$class == Action::class) {
            throw new \InvalidArgumentException("Class '$class' must extends Action class.");
        }
        return new $class($this->container);
    }
}