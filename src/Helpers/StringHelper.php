<?php

namespace Helpers;

class StringHelper
{
    public static function splitAt($string, $index): array
    {
        return [(string)substr($string, 0, $index), (string)substr($string, $index, strlen($string))];
    }

    public static function removeLastNChars($string, $count): string
    {
        return substr($string, 0, -abs($count));
    }
}
