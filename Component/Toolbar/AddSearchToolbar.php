<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 14:06.
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Toolbar\Action\ActionsBuilder;
use Umbrella\CoreBundle\Component\Toolbar\Action\AddAction;

/**
 * Class AddSearchToolbar
 */
class AddSearchToolbar extends SearchToolbar
{
    /**
     * @inheritdoc
     */
    public function buildActions(ActionsBuilder $builder, array $options)
    {
        $builder->add('add', AddAction::class, array(
            'title' => $options['add_title'],
            'action' => $options['add_action']
        ));
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setRequired(array(
            'add_title',
            'add_action'
        ));
    }
}
