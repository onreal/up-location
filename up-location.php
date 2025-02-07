<?php
/**
 * Plugin Name: UpLocation
 * Description: A WordPress plugin to get user location permission.
 * Version: 1.0
 * Author: Margarit Koka
 * Author URI: https://upio.gr
 */

if (!defined('ABSPATH')) {
    exit;
}

spl_autoload_register(function ($class) {
    $prefix = 'Up\\UpLocation\\';
    $base_dir = dirname(__FILE__) . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

if (!defined('UP_LOCATION_PLUGIN_FILE')) {
    define('UP_LOCATION_PLUGIN_FILE', __FILE__);
}

if (!class_exists('UpLocation')) {
    include_once dirname(__FILE__) . '/src/UpLocation.php';
}

function fetch_user_location()
{
    if (isset($_COOKIE['up_location_latitude']) && isset($_COOKIE['up_location_longitude'])) {
        $latitude = $_COOKIE['up_location_latitude'];
        $longitude = $_COOKIE['up_location_longitude'];

        return array(
            'latitude' => $latitude,
            'longitude' => $longitude
        );
    }

    $user_id = get_current_user_id();

    if (!$user_id) {
        return null;
    }

    $latitude = get_user_meta($user_id, 'up_location_latitude', true);
    $longitude = get_user_meta($user_id, 'up_location_longitude', true);

    if (!$latitude || !$longitude) {
        return null;
    }

    return array(
        'latitude' => $latitude,
        'longitude' => $longitude
    );
}