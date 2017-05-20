<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46
 */

namespace Umbrella\CoreBundle\DataTable\Model;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class DataTable
 * @package Umbrella\CoreBundle\Model\Table
 */
class DataTable implements OptionsAwareInterface, ContainerAwareInterface
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $template = 'UmbrellaCoreBundle:DataTable:datatable.html.twig' ;

    /**
     * @var string
     */
    public $ajaxUrl;

    /**
     * @var string
     */
    public $ajaxType = 'POST';

    /**
     * @var array
     */
    public $columns = array();

    /**
     * @var string
     */
    public $entityName;

    /**
     * @var DataTableQuery
     */
    public $query;

    /**
     * @var array
     */
    protected $results = null;

    /**
     * @var array
     */
    protected $fetchedResults = null;


    /* Handle request */
    protected $draw;
    
    use ContainerAwareTrait;

    /**
     * @param Request $request
     */
    public function handleRequest(Request $request)
    {
        $this->draw = $request->get('draw');
        $this->query->handleRequest($this, $request);
    }

    /**
     * @return array
     */
    public function getResults()
    {
        if ($this->results === null) {
            $this->query->init($this->entityName);
            $this->results = $this->query->getResults();
        }
        return $this->results;
    }

    /**
     * @return array
     */
    protected function fetchAll()
    {
        if ($this->fetchedResults === null) {
            $results = $this->getResults();

            foreach ($results as $result) {
                $row = array();

                foreach ($this->columns as $column) {
                    $row[] = $column->render($result);
                }
                $this->fetchedResults[] = $row;
            }
        }
    }

    /**
     * @return array
     */
    public function getApiResults()
    {
        $this->fetchAll();
        $count = $this->query->count();

        return array(
            'draw' => $this->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $this->fetchedResults
        );
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'entity'
        ));

        $resolver->setDefined(array(
            'ajax_url',
            'ajax_type',
            'class',
            'template'
        ));
    }

    /**
     * @inheritdoc
     */
    public function setOptions(array $options = array())
    {
        $this->class = ArrayUtils::get($options, 'class');
        $this->template = ArrayUtils::get($options, 'template', $this->template);

        $this->ajaxUrl = ArrayUtils::get($options, 'ajax_url');
        $this->ajaxType = ArrayUtils::get($options, 'ajax_type', $this->ajaxType);

        $this->entityName = ArrayUtils::get($options, 'entity');
    }
}