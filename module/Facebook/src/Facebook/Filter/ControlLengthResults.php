<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 08.07.2018
 * Time: 08:30
 */

namespace Facebook\Facebook\Filter;


class ControlLengthResults
{
    const STRING_LENGTH = 120;

    public function showFetchResults($data)
    {
        if (is_string($data)) {
            if (strlen($data) > self::STRING_LENGTH) {
                return substr($data, 0, self::STRING_LENGTH) . "&nbsp...";
            } else {
                return $data;
            }
        }
    }
}