<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 20:57.
 */

namespace Umbrella\CoreBundle\Utils;

/**
 * Class StringUtils.
 */
class StringUtils
{
    /**
     * @see http://stackoverflow.com/questions/1993721/how-to-convert-camelcase-to-camel-case
     *
     * @param $input
     *
     * @return string
     */
    public static function to_underscore($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }
}
