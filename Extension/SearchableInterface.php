<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 12:54
 */
namespace Umbrella\CoreBundle\Extension;

/**
 * Interface SearchableInterface
 * @package Umbrella\CoreBundle\Extension
 */
interface SearchableInterface
{

    /**
     * @param $searchable
     */
    public function setSearchable($searchable);

    /**
     * @return array
     */
    public function getSearchableFields();

}