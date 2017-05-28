<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 14:06
 */

namespace Umbrella\CoreBundle\Toolbar\Model;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Umbrella\CoreBundle\Form\AddonTextType;

/**
 * Class SearchToolbar
 * @package Umbrella\CoreBundle\Toolbar\Model
 */
class SearchToolbar extends AbstractToolbar
{

    /**
     * @param FormFactory $factory
     * @return FormInterface
     */
    public function createForm(FormFactory $factory)
    {
        $builder = $this->createFormBuilder($factory);
        $builder->add('search', AddonTextType::class, array(
            'label' => false,
            'suffix' => '<i class="fa fa-search"></i>',
            'required' => false,
            'attr' => array(
                'placeholder' => 'Rechercher ...'
            )
        ));

        return $builder->getForm();
    }

    /**
     *
     * @param QueryBuilder $qb
     * @param array $data
     * @return QueryBuilder
     */
    public function buildQuery(QueryBuilder $qb, array $data)
    {
        if ($data['search']) {
            $qb->andWhere('lower(e.searchable) LIKE :search')->setParameter('search', '%' . strtolower($data['search']) . '%');
        }
    }
}