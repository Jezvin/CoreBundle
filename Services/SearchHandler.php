<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 01/06/17
 * Time: 23:52
 */
namespace Umbrella\CoreBundle\Services;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Umbrellac\CoreBundle\Annotation\SearchableAnnotationReader;

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
     * @var SearchableAnnotationReader
     */
    private $reader;

    /**
     * SearchHandler constructor.
     * @param SearchableAnnotationReader $reader
     */
    public function __construct(SearchableAnnotationReader $reader)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->reader = $reader;
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