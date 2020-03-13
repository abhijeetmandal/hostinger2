<?php
/*
  Plugin Name: Chaty
  Contributors: galdub, tomeraharon
  Description: Chat with your website visitors via their favorite channels. Show a chat icon on the bottom of your site and communicate with your customers.
  Author: Premio
  Author URI: https://premio.io/downloads/chaty/
  Version: 2.1.1
  License: GPL2
*/

if (!defined('ABSPATH')) {
    exit; // don't access directly
};

define('CHT_FILE', __FILE__); // this file
define('CHT_OPT', 'chaty');
define('CHT_DIR', dirname(CHT_FILE)); // our directory
define('CHT_ADMIN_INC', CHT_DIR . '/admin');
define('CHT_FRONT_INC', CHT_DIR . '/frontend');
define('CHT_INC', CHT_DIR . '/includes');
define('CHT_PRO_URL', admin_url("admin.php?page=chaty-app-upgrade"));
define('CHT_PLUGIN_URL', plugins_url() . "/chaty/");
define('CHT_PLUGIN_BASE', plugin_basename(CHT_FILE));
define('CHT_VERSION', "2.1.1");

if (!function_exists('wp_doing_ajax')) {
    function wp_doing_ajax()
    {
        /**
         * Filters whether the current request is a WordPress Ajax request.
         *
         * @since 4.7.0
         *
         * @param bool $wp_doing_ajax Whether the current request is a WordPress Ajax request.
         */
        return apply_filters('wp_doing_ajax', defined('DOING_AJAX') && DOING_AJAX);
    }
}

require_once CHT_INC . '/class-cht-icons.php';
require_once CHT_FRONT_INC . '/class-frontend.php';


add_action('activated_plugin', 'cht_activation_redirect');

register_activation_hook(CHT_FILE, 'cht_install', 10);

function cht_install()
{
    $widgetSize = get_option('cht_widget_size');

    if (empty($widgetSize)) {
        $cht_multidimensional_options = array(
            'mobile' => '1',
            'desktop' => '1',
        );

        update_option('cht_devices', $cht_multidimensional_options);
        update_option('cht_active', '1');
        update_option('cht_position', 'right');
        update_option('cht_cta', 'Contact us');
        update_option('cht_numb_slug', ',Phone,Whatsapp,Facebook Messenger');
        update_option('cht_social_whatsapp', '');
        update_option('cht_social_phone', '');
        update_option('cht_widget_size', '54');
        update_option('widget_icon', 'chat-base');
        update_option('cht_widget_img', '');
        update_option('cht_color', '#A886CD');
    }
}

function cht_activation_redirect($plugin)
{
    if ($plugin == plugin_basename(__FILE__)) {
        exit(wp_redirect(admin_url('admin.php?page=chaty-app')));
    }
}
   
