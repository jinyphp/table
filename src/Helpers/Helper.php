<?php
use \Jiny\Html\CTag;
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
        return (new \Jiny\Html\CTag('a',true))
        ->setAttribute('href', "javascript: void(0);")
        ->setAttribute('wire:click', $link)
        ->addItem($title);

    }
}

/** ----- ----- ----- ----- -----
 *  Cell Functions
 */
// 키-값쌍에서 값을 출력합니다.
function xCellValue($string)
{
    $arr = explode(":",$string);
    if (isset($arr[1])) {
        return xSpan($arr[1]);
        //return $arr[1];
    }
}

// 키-값쌍에서 키를 출력합니다.
function xCellKey($string)
{
    $arr = explode(":",$string);
    if (isset($arr[0])) {
        return xSpan($arr[0]);
        //return $arr[0];
    }
}

// 입력을 반환합니다.
function xCellString($string)
{
    return xSpan($string);
    //return $string;
}

function xCellPopupEdit($title, $item)
{
    $link = xLink($title)->setHref("javascript: void(0);");
    $link->setAttribute("wire:click", "$"."emit('edit','".$item->id."')");

    if (isset($item->enable)) {
        if($item->enable) {
            return $link;
        } else {
            return xSpan($link)->style("text-decoration:line-through;");
        }
    }

    return $link;
}

function xCellAvatar($item, $key)
{
    if (isset($item->$key)) {
        $img = new CTag('img');
        $img->setAttribute('src','/images/'.$item->$key);
        $img->addClass("inline object-cover rounded-full");
        $img->addClass("w-8 h-8");

        return $img;
        // <img {{$attributes->merge(['class' => ''])}} />
        // <x-avata class="w-8 h-8" src="/images/{{$item->image1}}"/>
    }
}
