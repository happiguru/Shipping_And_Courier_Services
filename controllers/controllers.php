<?php

    function cleanInt($integer) {
        $string_sanitize = filter_var($integer, FILTER_VALIDATE_INT);
        return $string_sanitize;
    }

    function cleanString($string) {
        $string_sanitize = filter_var($string, FILTER_SANITIZE_STRING);
        return $string_sanitize;
    }
?>