<?php
/**
 * Helpers
 */
if (!function_exists("_getValue")) {
    function _getValue($string)
    {
        $arr = explode(":",$string);
        if (isset($arr[1])) {
            return $arr[1];
        }
    }

    function _value($string)
    {
        return _getValue($string);
    }
}

if (!function_exists("_getKey")) {
    function _getKey($string)
    {
        $arr = explode(":",$string);
        if (isset($arr[0])) {
            return $arr[0];
        }
    }
    function _key($string)
    {
        return _getKey($string);
    }
}

if (!function_exists("xWireLink")) {
    function xWireLink($title, $link) {
        return '<a href="javascript: void(0);" wire:click="'.$link.'">'.$title.'</a>';
    }
}

