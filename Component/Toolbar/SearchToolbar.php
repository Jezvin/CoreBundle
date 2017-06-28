<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 14:06.
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormBuilder;
use Umbrellac\CoreBundle\Form\AddonTextType;

/**
 * Class SearchToolbar.
 */
class SearchToolbar extends Toolbar
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('search', AddonTextType::class, array(
            'label' => false,
            'suffix' => '<i class="fa fa-search"></i>',
            'required' => false,
            'attr' => array(
                'placeholder' => 'form.placeholder.search',
            ),
        ));
    }

    /**
     * @inheritdoc
     */
    public function filter(QueryBuilder $qb, $data)
    {
        if ($data['search']) {
            $qb->andWhere('lower(e.search) LIKE :search')->setParameter('search', '%'.strtolower($data['search']).'%');
        }
    }
}
