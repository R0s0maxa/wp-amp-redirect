<?php
/*
  Plugin Name: AMP Redirect
  Plugin URI:
  Description: Provides redirect to amp theme
  Author: Nikita Murzin
  Author URI: murzin@bluefountainmedia.com
  Version: 1.0
  License: GPL2
 */

// If this file is accessed directory, then abort.
if (!defined('WPINC')) {
    die;
}

define('AMP_REDIRECT_SETTINGS_GROUP', 'wp_amp_redirect_settings_group');
define('AMP_REDIRECT_OPTION', 'wp_amp_redirect_template');
define('AMP_REDIRECT_QUERY_VAR', 'amp');

include_once 'class-amp-redirect-options.php';
include_once 'class-amp-redirect-rewrite.php';
include_once 'сlass-amp-redirect.php';

add_action('plugins_loaded', 'wp_amp_redirect_init');
add_action('init', 'wp_amp_redirect_rewrites_init');

function wp_amp_redirect_init()
{
    $options_page = new AMP_Redirect_Options();
    $redirect = new AMP_Redirect();

    $options_page->init();
//    $redirect->init();
}

function wp_amp_redirect_rewrites_init()
{
    $rewrites = new AMP_Redirect_Rewrite();
    $rewrites->init();
}