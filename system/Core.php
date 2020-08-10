<?php

/***
 *      __  __            _                     _          _   ______              _   __  ____      _______ 
 *     |  \/  |          | |         /\        (_)        | | |  ____|            (_) |  \/  \ \    / / ____|
 *     | \  / | ___   ___| |__      /  \   _ __ _ ______ _| | | |__ __ _ _   _ _____  | \  / |\ \  / / |     
 *     | |\/| |/ _ \ / __| '_ \    / /\ \ | '__| |_  / _` | | |  __/ _` | | | |_  / | | |\/| | \ \/ /| |     
 *     | |  | | (_) | (__| | | |  / ____ \| |  | |/ / (_| | | | | | (_| | |_| |/ /| | | |  | |  \  / | |____ 
 *     |_|  |_|\___/ \___|_| |_| /_/    \_\_|  |_/___\__,_|_| |_|  \__,_|\__,_/___|_| |_|  |_|   \/   \_____|
 *                                                                                                           
 *                                                                                                           
 */

defined('BASEPATH') or die('No direct script access allowed!');

require_once 'Init.php';
require_once 'Controller.php';
require_once 'Model.php';

/**
 * base_url
 * 
 * @param string $uri_string
 * @return string
 */
function base_url($uri_string = '')
{
    require 'app/config/config.php';

    return rtrim($config['base_url'], '/') . '/' . $uri_string;
}

/**
 * is_https
 * 
 * @return bool
 */
function is_https()
{
    if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
        return true;
    } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    }

    return false;
}

/**
 * database_config
 * 
 * @return array $config
 */
function database_config()
{
    require 'app/config/database.php';

    return $config;
}

/**
 * getCurrentDate
 * 
 * @param string $format
 * @return string
 */
function getCurrentDate($format = 'Y-m-d H:i:s')
{
    return date($format);
}

/**
 * DateFormat
 * 
 * @param string $date
 * @param string $format
 * @return string
 */
function DateFormat($date, $format = '')
{
    if ($format == '') {
        $format = 'd/m/Y';
    }

    $date = str_replace('/', '-', $date);

    return date($format, strtotime($date));
}

/**
 * show_404
 * 
 * @return mixed
 */
function show_404()
{
    http_response_code(404);
    return require_once('app/views/error/404.php');
}

/**
 * redirect
 * 
 * @param string $uri_string
 * @return redirect
 */
function redirect($uri_string)
{
    return header('Location: ' . base_url($uri_string));
}
