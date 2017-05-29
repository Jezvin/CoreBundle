<?php

namespace Umbrella\CoreBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Umbrella\CoreBundle\DependencyInjection\Compiler\MenuBuilderPass;
use Umbrella\CoreBundle\DependencyInjection\Compiler\MenuRendererPass;

/**
 * Class UmbrellaCoreBundle.
 */
class UmbrellaCoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new MenuBuilderPass());
        $container->addCompilerPass(new MenuRendererPass());
    }
}
