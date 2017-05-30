<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 01:46.
 */

namespace Umbrella\CoreBundle\Breadcrumb;

/**
 * Class BreadcrumbItem.
 */
class BreadcrumbItem
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $label;

    /**
     * BreadcrumbItem constructor.
     *
     * @param string $label
     * @param string $url
     */
    public function __construct($label = '', $url = '')
    {
        $this->url = $url;
        $this->label = $label;
    }
}
