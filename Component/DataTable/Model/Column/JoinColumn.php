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
        $joinEntity = $this->getJoinEntity($entity);

        if ($joinEntity === null) {
            return null;
        }

        if ($joinEntity instanceof \Traversable) {
            $html = '';
            foreach ($joinEntity as $e) {
                $html .= '<span class="label label-primary">' . $this->getJoinEntityValue($e) . '</span>&nbsp;';
            }
            return $html;
        }

        return $this->getJoinEntityValue($joinEntity);
    }

    /**
     * @param $entity
     *
     * @return Collection
     */
    public function getJoinEntity($entity)
    {
        return $this->accessor->getValue($entity, $this->join);
    }

    /**
     * @param $joinEntity
     *
     * @return string
     */
    public function getJoinEntityValue($joinEntity)
    {
        return empty($this->joinPropertyPath)
            ? (string) $joinEntity
            : $this->accessor->getValue($joinEntity, $this->joinPropertyPath);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        parent::setOptions($options);
        $this->join = ArrayUtils::get($options, 'join', $options['id']);
        $this->queryJoin = $options['query_join'];
        $this->joinPropertyPath = ArrayUtils::get($options, 'property_path');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'join',
            'query_join',
            'property_path'
        ));

        $resolver->setDefault('orderable', false);
        $resolver->setDefault('query_join', Join::LEFT_JOIN);
    }
}
