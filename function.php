<?php

use Inilim\JSON\JSON;

if (!\function_exists('_json')) {
    function _json(): JSON
    {
        static $o = null;
        return $o ??= new JSON;
    }
}
