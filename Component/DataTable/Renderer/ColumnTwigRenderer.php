<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/05/17
 * Time: 09:11.
 */

namespace Umbrellac\CoreBundle\Component\DataTable\Renderer;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Umbrellac\CoreBundle\Component\DataTable\Model\Column\Column;

/**
 * Class ColumnTwigRenderer.
 */
class ColumnTwigRenderer implements ColumnRendererInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $template;

    /**
     * ColumnTwigRenderer constructor.
     *
     * @param $template
     */
    public function __construct($template)
    {
        $this->template = $template;
    }

    /**
     * @param Column $column
     * @param $entity
     *
     * @return string
     */
    public function render(Column $column, $entity)
    {
        return $this->container->get('twig')->render($this->template, array(
            'column' => $column,
            'entity' => $entity,
        ));
    }
}
