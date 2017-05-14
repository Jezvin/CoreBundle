<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 01:46
 */

namespace Umbrella\CoreBundle\Breadcrumb;

/**
 * Class BreadcrumbItem
 * @package Umbrella\CoreBundle\Breadcrumb
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
    public $text;

    /**
     * BreadcrumbItem constructor.
     * @param string $text
     * @param string $url
     */
    public function __construct($text = "", $url = "")
    {
        $this->url = $url;
        $this->text = $text;
    }
}