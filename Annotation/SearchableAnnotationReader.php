<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 01/06/17
 * Time: 23:40
 */

namespace Umbrella\CoreBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SearchableAnnotationReader
 */
class SearchableAnnotationReader
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * SearchableAnnotationReader constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->reader = $container->get('annotation_reader');
    }

    /**
     * @param $entityClass
     * @return null|Searchable
     */
    public function getSearchable($entityClass)
    {
        return $this->getInheritAnnotation($entityClass, Searchable::class);
    }

    /**
     * @param $entityClass
     * @return SearchableField[]
     */
    public function getSearchableFields($entityClass)
    {
        $reflection = new \ReflectionClass($entityClass);

        $fields = array();
        foreach ($reflection->getProperties() as $property) {
            $annotation = $this->reader->getPropertyAnnotation($property, SearchableField::class);
            if ($annotation !== null) {
                $fields[$property->getName()] = $annotation;
            }
        }
        return $fields;
    }

    /* Helper */

    /**
     * @param $class
     * @param $annotationName
     * @return null|mixed
     */
    public function getInheritAnnotation($class, $annotationName)
    {
        $reflection = new \ReflectionClass($class);
        $annotation = $this->reader->getClassAnnotation($reflection, $annotationName);

        if ($annotation !== null) {
            return $annotation;
        }

        $parentClass = get_parent_class($class);
        return $parentClass === false
            ? null
            : $this->getInheritAnnotation($parentClass, $annotationName);
    }

}