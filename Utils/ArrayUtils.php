<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 21:17
 */
namespace Umbrella\CoreBundle\Utils;

/**
 * Class ArrayUtils
 * @package Umbrella\CoreBundle\Utils
 */
class ArrayUtils
{
    /**
     * @param array $array
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public static function get(array $array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }

    /**
     * convert [0 => 'a', 1 => 'b', ...] to ['a'=>'a', 'b'=>'b', ...]
     *
     * @param array $array
     * @return array
     */
    public static function values_as_keys(array  &$array)
    {
        $result = array();
        foreach ($array as $value) {
            $result[$value] = $value;
        }
        return $result;
    }

    /**
     * @param $value
     * @return array
     */
    public static function to_array($value)
    {
        return is_array($value) ? $value : array($value);
    }

}