<?php
/**
 * Helpers
 */

function _getValue($string)
{
    $arr = explode(":",$string);
    if (isset($arr[1])) {
        return $arr[1];
    }
}

function _getKey($string)
{
    $arr = explode(":",$string);
    if (isset($arr[0])) {
        return $arr[0];
    }
}


