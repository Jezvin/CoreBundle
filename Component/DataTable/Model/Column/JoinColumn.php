<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 21/05/17
 * Time: 11:32.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model\Column;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class JoinColumn.
 */
class JoinColumn extends Column
{
    /**
     * @var string
     */
    public $join;

    /**
     * @var string
     */
    public $joinPropertyPath;

    /**
     * @var string
     */
    public $queryJoin;

    /**
     * @var PropertyAccess
     */
    protected $accessor;

    /**
     * JoinColumn constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function defaultRender($entity)
    {
        $html = '';
        foreach ($this->getJoinEntitiesValues($entity) as $value) {
            $html .= '<span class="label label-primary">'.$value.'</span>&nbsp;';
        }

        return $html;
    }

    /**
     * @param $entity
     *
     * @return Collection
     */
    public function getJoinEntities($entity)
    {
        return $this->accessor->getValue($entity, $this->join);
    }

    /**
     * @param $joinEntity
     *
     * @return mixed
     */
    public function getJoinEntityValue($joinEntity)
    {
        return $this->accessor->getValue($joinEntity, $this->joinPropertyPath);
    }

    /**
     * @param $entity
     *
     * @return array
     */
    public function getJoinEntitiesValues($entity)
    {
        $entities = $this->getJoinEntities($entity);

        return array_map(array($this, 'getJoinEntityValue'), $entities->toArray());
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        parent::setOptions($options);
        $this->join = ArrayUtils::get($options, 'join', $options['id']);
        $this->queryJoin = $options['query_join'];
        $this->joinPropertyPath = $options['property_path'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(array(
            'property_path',
        ));

        $resolver->setDefined(array(
            'join',
            'query_join',
        ));

        $resolver->setDefault('orderable', false);
        $resolver->setDefault('query_join', Join::LEFT_JOIN);
    }
}
