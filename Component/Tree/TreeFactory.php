<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 14:16
 */
namespace Umbrella\CoreBundle\Component\Tree;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Tree\Model\Tree;
use Umbrella\CoreBundle\Component\Tree\Model\TreeQuery;

/**
 * Class TreeFactory
 */
class TreeFactory
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * TreeFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $typeClass
     * @param array $options
     * @return Tree
     */
    public function create($typeClass, array $options = array())
    {
        $type = $this->createType($typeClass);

        $tree = new Tree($this->container);
        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);

        // create tree
        $tree->configureOptions($resolver);
        $options = $resolver->resolve($options);
        $tree->setOptions($options);

        return $tree;
    }

    /**
     * @param $typeClass
     *
     * @return TreeType
     */
    protected function createType($typeClass)
    {
        if ($typeClass !== TreeType::class && !is_subclass_of($typeClass, TreeType::class)) {
            throw new \InvalidArgumentException("Class '$typeClass' must extends TreeType class.");
        }

        $type = new $typeClass();
        if (is_a($type, ContainerAwareInterface::class)) {
            $type->setContainer($this->container);
        }

        return $type;
    }

}