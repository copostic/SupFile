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

/**
 * @param string $type
 * @param int $length
 * @return string
 */
function random_text($type = 'alnum', $length = 32) {
    switch ($type) {
        case 'alnum':
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
        case 'alpha':
            $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
        case 'hexdec':
            $pool = '0123456789abcdef';
            break;
        case 'numeric':
            $pool = '0123456789';
            break;
        case 'nozero':
            $pool = '123456789';
            break;
        case 'distinct':
            $pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
            break;
        default:
            $pool = (string)$type;
            break;
    }


    $crypto_rand_secure = function ($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min;
        $log = log($range, 2);
        $bytes = (int)($log / 8) + 1;
        $bits = (int)$log + 1;
        $filter = (int)(1 << $bits) - 1;
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter;
        } while ($rnd >= $range);
        return $min + $rnd;
    };

    $token = "";
    $max = strlen($pool);
    for ($i = 0; $i < $length; $i++) {
        $token .= $pool[$crypto_rand_secure(0, $max)];
    }
    return $token;
}