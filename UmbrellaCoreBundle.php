<?php

namespace Umbrella\CoreBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Umbrellac\CoreBundle\DependencyInjection\Compiler\MenuPass;
use Umbrellac\CoreBundle\DependencyInjection\Compiler\MenuRendererPass;

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
        $container->addCompilerPass(new MenuPass());
        $container->addCompilerPass(new MenuRendererPass());
    }
}
