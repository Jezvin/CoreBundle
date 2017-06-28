<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 01:46.
 */

namespace Umbrella\CoreBundle\Component\AppProxy;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AppMessageBuilder.
 */
class AppMessageBuilder
{
    /**
     * @var array
     */
    protected $messages = array();

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AppProxy constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Clear all messages.
     */
    public function clear()
    {
        $this->messages = array();
    }

    // Misc actions

    public function toast($id, array $parameters = array(), $level = 'success')
    {
        return $this->add(AppMessage::TOAST, array(
            'value' => $this->container->get('translator')->trans($id, $parameters),
            'level' => $level,
        ));
    }

    public function redirectToRoute($route, array $params = array())
    {
        return $this->redirect($this->container->get('router')->generate($route, $params));
    }

    public function redirect($url)
    {
        return $this->add(AppMessage::REDIRECT, array(
            'value' => $url,
        ));
    }

    public function execute($js)
    {
        return $this->add(AppMessage::EXECUTE_JS, array(
            'value' => $js,
        ));
    }

    // Html actions

    public function replace($html, $css_selector)
    {
        return $this->addHtmlMessage(AppMessage::REPLACE_HTML, $html, $css_selector);
    }

    public function replaceView($template, array $context = array(), $css_selector)
    {
        return $this->replace($this->container->get('twig')->render($template, $context), $css_selector);
    }

    public function update($html, $css_selector)
    {
        return $this->addHtmlMessage(AppMessage::UPDATE_HTML, $html, $css_selector);
    }

    public function updateView($template, array $context = array(), $css_selector)
    {
        return $this->update($this->container->get('twig')->render($template, $context), $css_selector);
    }

    public function remove($css_selector)
    {
        return $this->addHtmlMessage(AppMessage::REMOVE_HTML, null, $css_selector);
    }

    public function prepend($html, $css_selector)
    {
        return $this->addHtmlMessage(AppMessage::PREPEND_HTML, $html, $css_selector);
    }

    public function append($html, $css_selector)
    {
        return $this->addHtmlMessage(AppMessage::REMOVE_HTML, $html, $css_selector);
    }

    // Modal actions

    public function openModal($html)
    {
        return $this->addHtmlMessage(AppMessage::OPEN_MODAL, $html);
    }

    public function renderModal($template, array $context = array())
    {
        return $this->openModal($this->container->get('twig')->render($template, $context));
    }

    public function closeModal()
    {
        return $this->addHtmlMessage(AppMessage::CLOSE_MODAL);
    }

    // DataTable actions

    public function reloadTable($id)
    {
        return $this->add(AppMessage::RELOAD_TABLE, array(
            'id' => $id,
        ));
    }

    // Utils

    /**
     * @param $action
     * @param array $params
     *
     * @return AppMessageBuilder
     */
    protected function add($action, $params = array())
    {
        $this->messages[] = new AppMessage($action, $params);

        return $this;
    }

    /**
     * @param $type
     * @param $html
     * @param $css_selector
     *
     * @return AppMessageBuilder
     */
    protected function addHtmlMessage($type, $html = null, $css_selector = null)
    {
        return $this->add($type, array(
            'value' => $html,
            'selector' => $css_selector,
        ));
    }

    /**
     * @return JsonResponse
     */
    public function getResponse()
    {
        return new JsonResponse($this->messages);
    }
}
