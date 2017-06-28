<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/06/17
 * Time: 21:03
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model\Column;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrellac\CoreBundle\Entity\UmbrellaFile;
use Umbrellac\CoreBundle\Utils\ArrayUtils;
use Umbrellac\CoreBundle\Utils\HtmlUtils;

/**
 * Class ImageColumn
 */
class ImageColumn extends JoinColumn
{

    /**
     * @var array
     */
    public $imageAttr;

    /**
     * @var string
     */
    public $imagineFilter;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * ImageColumn constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->cacheManager = $container->get('liip_imagine.cache.manager');
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function defaultRender($entity)
    {
        $joinEntity = $this->getJoinEntity($entity);

        if (!$joinEntity instanceof UmbrellaFile) {
            return null;
        }

        $attr = array_merge(['title' => $joinEntity->name], $this->imageAttr);

        $url = $joinEntity->getWebPath();
        if (!empty($this->imagineFilter)) {
            $url = $this->cacheManager->getBrowserPath($url, $this->imagineFilter);
        }
        return '<img src="' . $url . '" ' . HtmlUtils::array_to_html_attribute($attr) . '>';
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        parent::setOptions($options);
        $this->imageAttr = $options['image_attr'];
        $this->imagineFilter = ArrayUtils::get($options, 'imagine_filter');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'image_attr',
            'imagine_filter'
        ));

        $resolver->setAllowedTypes('image_attr', 'array');
        $resolver->setDefault('image_attr',  array(
            'width' => 80,
            'height' => 80
        ));
        $resolver->setDefault('class', 'text-center');
    }
}