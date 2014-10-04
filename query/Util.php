<?php

namespace QuakeQuery;

class Util
{
    public static function stripColors($str)
    {
        $result = '';
        $len = strlen($str);
        for ($i = 0; $i < $len;) {
            if ($str[$i] === '^'
                && ($i+1) < $len
                && ord($str[$i+1]) <= ord('9')
                && ord($str[$i+1]) >= ord('0')) {
                $i += 2;
            } else {
                $result .= $str[$i];
                $i += 1;
            }
        }
        return $result;
    }

    public static function colorMap($str)
    {
        $map = array('black', 'red', 'green', 'yellow', 'blue', 'aqua',
                     'pink', 'white', 'orange', 'grey');
        $result = array();
        $colorState = 'white';
        $text = '';
        $len = strlen($str);
        for ($i = 0; $i < $len;) {
            if ($str[$i] === '^'
                && ($i+1) < $len
                && ord($str[$i+1]) <= ord('9')
                && ord($str[$i+1]) >= ord('0')) {
                $result[] = array($colorState, $text);
                $colorState = $map[$str[$i+1]];
                $text = '';
                $i += 2;
            } else {
                $text .= $str[$i];
                $i += 1;
            }
        }
        $result[] = array($colorState, $text);
        return $result;
    }

    public static function mapToHtml($map)
    {
        $html = '';
        foreach ($map as $pair) {
            $color = $pair[0];
            $text = $pair[1];
            $html .= '<span class="color-' . $color . '">' . $text . '</span>';
        }
        return $html;
    }

    public static function toHtml($str)
    {
        return self::mapToHtml(self::colorMap($str));
    }
}
