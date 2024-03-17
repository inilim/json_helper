<?php

use Inilim\JSON\JSON;

if (!function_exists('_json')) {
    function _json(): JSON
    {
        static $instance = null;
        if ($instance !== null) return $instance;
        return $instance = new JSON;
    }
}
