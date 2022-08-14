<?php

/**
 * Plugin Name:		Automatic WP Posts Summarizer
 * Author:		    Shycoder
 * Author URI:		https://shycoder.com/
 * Description:		AI Powered automatic posts summarization plugin for WordPress
 * Version:		    1.0
 * Plugin URI:		https://wordpress.org/plugins/automatic-wp-posts-summarizer
 * Requires PHP:	7.4
 * Requires at least:	5.1
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

defined('AWPS_DIR') || define('AWPS_DIR', plugin_dir_path(__FILE__));
defined('AWPS_ENCRYPTION_METHOD')  || define('AWPS_ENCRYPTION_METHOD', 'AES-256-CBC');
defined('AWPS_SUMMARIZER_TABLE')   || define('AWPS_SUMMARIZER_TABLE', 'awps_summarizer');

require_once 'autoload.php';

if (empty($GLOBALS['awps'])) {

    $GLOBALS['awps'] = Awps\Awps::get_instance();

    register_activation_hook(__FILE__, [$GLOBALS['awps'], 'activate']);
    register_deactivation_hook(__FILE__, [$GLOBALS['awps'], 'deactivate']);
}
