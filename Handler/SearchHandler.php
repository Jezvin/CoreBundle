<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 01/06/17
 * Time: 23:52
 */
namespace Umbrella\CoreBundle\Handler;

use Psr\Container\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Umbrella\CoreBundle\Annotation\SearchableAnnotationReader;

/**
 * Class SearchableHandler
 */
class SearchHandler
{

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var SearchableAnnotationReader
     */
    private $reader;

    /**
     * SearchHandler constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->container = $container;
        $this->reader = $container->get('umbrella.searchable_annotation_reader');
    }

    /**
     * @param $entityClass
     * @return bool
     */
    public function isSearchable($entityClass)
    {
        return $this->reader->getSearchable($entityClass) !== null;
    }

    /**
     * @param $entity
     * @return bool
     */
    public function indexEntity($entity)
    {
        $entityClass = get_class($entity);

        $searchable = $this->reader->getSearchable($entityClass);
        $this->container->get('logger')->info('Searchable ? ' . ($searchable ? 'TRUE' : 'FALSE'));

        // Entity doesn't have annotation Searchable
        if ($searchable === null) {
            return false;
        }

        $searches = array();
        foreach ($this->reader->getSearchableFields($entityClass) as $field => $annotation) {
            $searches[] = (string)$this->accessor->getValue($entity, $field);
        }

        $search = implode(' ', $searches);
        $this->accessor->setValue($entity, $searchable->getSearchField(), $search);
        return true;
    }


}