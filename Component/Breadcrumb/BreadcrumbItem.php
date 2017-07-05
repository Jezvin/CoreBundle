<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 01:46.
 */

namespace Umbrella\CoreBundle\Component\Breadcrumb;

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
     * @var bool
     */
    public $translateLabel;

    /**
     * BreadcrumbItem constructor.
     *
     * @param string $label
     * @param string $url
     * @param bool $translateLabel
     */
    public function __construct($label = '', $url = '', $translateLabel = true)
    {
        $this->url = $url;
        $this->label = $label;
        $this->translateLabel = $translateLabel;
    }
}
