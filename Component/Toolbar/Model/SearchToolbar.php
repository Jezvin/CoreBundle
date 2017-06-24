<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 14:06.
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Model;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Umbrella\CoreBundle\Form\AddonTextType;

/**
 * Class SearchToolbar.
 */
class SearchToolbar extends Toolbar
{
    /**
     * @param FormFactory $factory
     *
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
                'placeholder' => 'form.placeholder.search',
            ),
        ));

        return $builder->getForm();
    }

    /**
     * @param QueryBuilder $qb
     * @param array        $data
     *
     * @return QueryBuilder
     */
    public function filter(QueryBuilder $qb, $data)
    {
        if ($data['search']) {
            $qb->andWhere('lower(e.search) LIKE :search')->setParameter('search', '%'.strtolower($data['search']).'%');
        }
    }
}
