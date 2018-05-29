<?php
/**
 * @param $value
 * @param null $title
 */
function err($value, $title = null) {
    error_log('====================================================');
    if (!is_null($title)) {
        error_log('****************' . $title);
    }
    if (is_array($value) || is_object($value)) {
        error_log(print_r($value, true));
    } elseif (is_bool($value)) {
        error_log($value ? 'true' : 'false');
    } else {
        error_log($value);
    }
}

/**
 * @param $value
 * @param null $title
 */
function pr($value, $title = null) {
    if (!is_null($title)) {
        echo '<h1>' . $title . '</h1>';
    }
    echo '<pre>';
    print_r($value);
    echo '</pre>';
    echo '<hr />';
}
