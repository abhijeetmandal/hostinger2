<?php
namespace CHT\admin;

use CHT\includes\CHT_Widget;
/**
 * Class CHT_Admin_Base
 * @since 1.0
 */

require 'class-social-icons.php';

class CHT_Admin_Base
{
    public $page;
    public $socials;
    public $colors;
    protected $token;
    protected static $response = null;
    protected static $checked_token = false;
    protected $upgrade_slug;


    public function __construct()
    {
        $plugin              = CHT_Widget::get_instance();
        $this->plugin_slug   = $plugin->get_plugin_slug();
        $this->friendly_name = $plugin->get_name();
        $this->socials       = CHT_Social_Icons::get_instance()->get_icons_list();
        $this->colors        = CHT_Social_Icons::get_instance()->get_colors();
        $this->token         = $this->get_token();
        $this->upgrade_slug  = $this->plugin_slug.'-upgrade';

        if ( is_admin() ) { // admin actions
            add_action('admin_menu', array($this, 'cht_admin_setting_page'));
            // Adds all of the options for the administrative settings
            add_action('admin_init', array($this, 'cht_register_inputs'));
            add_action('admin_head', array($this, 'cht_inline_css_admin'));
            add_action('init', array($this, 'check_page_available'));
        }
        add_action('updated_option', array($this, 'cht_clear_all_caches'));

        /* Send message to owner */
        add_action( 'wp_ajax_wcp_admin_send_message_to_owner', array( $this, 'wcp_admin_send_message_to_owner' ) );

        /* ADD Upgrade link to plugin */
        add_filter( 'plugin_action_links_' . CHT_PLUGIN_BASE, [ $this, 'plugin_action_links' ] );

        add_action( 'admin_footer', array( $this, 'add_deactivate_modal' ) );
        add_action( 'wp_ajax_chaty_plugin_deactivate', array( $this, 'chaty_plugin_deactivate' ) );
    }

    public function chaty_plugin_deactivate() {
        global $current_user;
        $postData = $_POST;
        $errorCounter = 0;
        $response = array();
        $response['status'] = 0;
        $response['message'] = "";
        $response['valid'] = 1;
        if(!isset($postData['reason']) || empty($postData['reason'])) {
            $errorCounter++;
            $response['message'] = "Please provide reason";
        } else if (!isset($postData['nonce']) || empty($postData['nonce'])) {
            $response['message'] = __("Your request is not valid", CHT_OPT);
            $errorCounter++;
            $response['valid'] = 0;
        } else {
            $nonce = self::chaty_sanitize_options($postData['nonce']);
            if(!wp_verify_nonce($nonce, 'chaty_deactivate_nonce')) {
                $response['message'] = __("Your request is not valid", CHT_OPT);
                $errorCounter++;
                $response['valid'] = 0;
            }
        }
        if($errorCounter == 0) {
            global $current_user;
            $postData = $_POST;
            $email = get_option('admin_email');
            $domain = site_url();
            $user_name = $current_user->first_name . " " . $current_user->last_name;
            $subject = "Chaty was removed from {$domain}";
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= 'From: ' . $user_name . ' <' . $email . '>' . PHP_EOL;
            $headers .= 'Reply-To: ' . $user_name . ' <' . $email . '>' . PHP_EOL;
            $headers .= 'X-Mailer: PHP/' . phpversion();
            ob_start();
            ?>
            <table border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <th>Plugin</th>
                    <td>Chaty</td>
                </tr>
                <tr>
                    <th>Plugin Version</th>
                    <td><?php echo CHT_CURRENT_VERSION ?></td>
                </tr>
                <tr>
                    <th>Domain</th>
                    <td><?php echo $domain ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $email ?></td>
                </tr>
                <tr>
                    <th>Reason</th>
                    <td><?php echo nl2br($postData['reason']) ?></td>
                </tr>
                <tr>
                    <th>WordPress Version</th>
                    <td><?php echo get_bloginfo('version') ?></td>
                </tr>
                <tr>
                    <th>PHP Version</th>
                    <td><?php echo PHP_VERSION ?></td>
                </tr>
            </table>
            <?php
            $content = ob_get_clean();
            $to = "contact+removed@premio.io";
            wp_mail($to, $subject, $content, $headers);
            $response['status'] = 1;
        }
        echo json_encode($response);
        die;
    }

    public function add_deactivate_modal() {
        global $pagenow;

        if ( 'plugins.php' !== $pagenow ) {
            return;
        }

        include CHT_DIR . '/views/modals/chaty-deactivate-form.php';
    }

    public function plugin_action_links( $links ) {
        $links['go_pro'] = '<a href="'.CHT_PRO_URL.'" class="chaty-plugins-gopro">'.__( 'Upgrade', 'stars-testimonials' ).'</a>';

        wp_register_style( 'chaty-admin-custom-style', CHT_PLUGIN_URL.'/assets/css/admin-cht-style.css');
        wp_enqueue_style( 'chaty-admin-custom-style' );

        return $links;
    }

    public function wcp_admin_send_message_to_owner() {
        $response = array();
        $response['status'] = 0;
        $response['error'] = 0;
        $response['errors'] = array();
        $response['message'] = "";
        $errorArray = [];
        $errorMessage = __("%s is required", CHT_OPT);
        $postData = $_POST;
        if(!isset($postData['textarea_text']) || trim($postData['textarea_text']) == "") {
            $error = array(
                "key"   => "textarea_text",
                "message" => __("Please enter your message","wcp")
            );
            $errorArray[] = $error;
        }
        if(!isset($postData['user_email']) || trim($postData['user_email']) == "") {
            $error = array(
                "key"   => "user_email",
                "message" => sprintf($errorMessage,__("Email","wcp"))
            );
            $errorArray[] = $error;
        } else if(!filter_var($postData['user_email'], FILTER_VALIDATE_EMAIL)) {
            $error = array(
                'key' => "user_email",
                "message" => "Email is not valid"
            );
            $errorArray[] = $error;
        }
        if(empty($errorArray)) {
            if(!isset($_REQUEST['nonce']) || empty($_REQUEST['nonce'])) {
                $error = array(
                    'key' => "nonce",
                    "message" => "Your request is not valid"
                );
                $errorArray[] = $error;
            } else if(!wp_verify_nonce($_REQUEST['nonce'], "chaty_send_message_to_owner")) {
                $error = array(
                    'key' => "nonce",
                    "message" => "Your request is not valid"
                );
                $errorArray[] = $error;
            }
        }
        if(empty($errorArray)) {
            global $current_user;
            $text_message = $postData['textarea_text'];
            $email = $postData['user_email'];
            $domain = site_url();
            $user_name = $current_user->first_name." ".$current_user->last_name;
            $subject = "Chaty request: ".$domain;
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= 'From: '.$user_name.' <'.$email.'>'.PHP_EOL ;
            $headers .= 'Reply-To: '.$user_name.' <'.$email.'>'.PHP_EOL ;
            $headers .= 'X-Mailer: PHP/' . phpversion();
            ob_start();
            ?>
            <table border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <th>Domain</th>
                    <td><?php echo $domain ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $email ?></td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td><?php echo nl2br($text_message) ?></td>
                </tr>
            </table>
            <?php
            $message = ob_get_clean();
            $to = "contact+fromwp@premio.io";
            $status = wp_mail($to, $subject, $message, $headers);
            if($status) {
                $response['status'] = 1;
            } else {
                $response['status'] = 0;
                $response['message'] = "Not able to send mail";
            }
        } else {
            $response['error'] = 1;
            $response['errors'] = $errorArray;
        }
        echo json_encode($response);
    }

    public function del_space($text){
        return str_replace('_',' ',$text);
    }

    public function cht_inline_css_admin(){
        echo '<style>
            #toplevel_page_chaty-app img:hover, #toplevel_page_chaty-app img {                
                opacity: 0 !important;
              } 
            #toplevel_page_chaty-app:hover .dashicons-before{
                background-color: #00b9eb; 
                } 
            #toplevel_page_chaty-app .dashicons-before{
                background-color: #A0A3A8;              
                -webkit-mask: url('.plugins_url( '../icon/chaty.svg' , __FILE__).') no-repeat center;
                mask: url('.plugins_url( '../icon/chaty.svg' , __FILE__).') no-repeat center;
            }
            .current#toplevel_page_chaty-app .dashicons-before{
                background-color: #fff; 
            }
          
             </style> ';
    }

    public function enqueue_styles()
    {
        $query_args = array(
            'family' => 'Rubik:400,700|Oswald:400,600',
            'subset' => 'latin,latin-ext'
        );
        wp_enqueue_style( 'google_fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );
        wp_enqueue_style( $this->plugin_slug, plugins_url('../assets/css/cht-style.css', __FILE__), array(), CHT_VERSION);
    }

    public function enqueue_scripts()
    {

        wp_enqueue_script( $this->plugin_slug . '-jq-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js');
        wp_enqueue_script( $this->plugin_slug .'fileinput', plugins_url('../assets/js/fileinput.min.js', __FILE__), array( 'jquery' ), CHT_VERSION);
        wp_enqueue_script( $this->plugin_slug .'pop', plugins_url('../assets/js/popper.min.js', __FILE__), array( 'jquery' ), CHT_VERSION);
        wp_enqueue_script( $this->plugin_slug, plugins_url('../assets/js/cht-scripts.min.js', __FILE__), array('jquery', 'wp-color-picker', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable'), CHT_VERSION);
//        wp_enqueue_script( $this->plugin_slug, plugins_url('../assets/js/cht-scripts.js', __FILE__), array( 'jquery' ), CHT_VERSION);
        wp_enqueue_script( $this->plugin_slug.'stick', plugins_url('../assets/js/jquery.sticky.js', __FILE__), array( 'jquery' ), CHT_VERSION);
        wp_localize_script($this->plugin_slug.'stick', 'cht_nonce_ajax',
            array(
                'cht_nonce' => wp_create_nonce('cht_nonce_ajax')
            )
        );
    }

    public function cht_admin_setting_page() {
    if (current_user_can('manage_options')){
        $this->page = add_menu_page(
            __('Chaty', CHT_OPT),
            __('Chaty', CHT_OPT),
            'manage_options',
            $this->plugin_slug,
            array($this, 'display_cht_admin_page'),
            plugins_url( 'chaty/icon/chaty.svg' )
        );

        add_submenu_page(
            $this->plugin_slug,
            __('Settings', CHT_OPT),
            __('Settings', CHT_OPT),
            'manage_options',
            $this->plugin_slug,
            array($this, 'display_cht_admin_page')
        );

        $upgrade_page = add_submenu_page(
            $this->plugin_slug,
            __('Upgrade to Pro', CHT_OPT),
            __('Upgrade to Pro', CHT_OPT),
            'manage_options',
            $this->upgrade_slug,
            array($this, 'display_cht_admin_upgrade_page')
        );
        add_action( 'admin_print_styles-' . $upgrade_page, array( $this, 'enqueue_styles' ) );
    }
        // Load public-facing style sheet and JavaScript.
        add_action( 'admin_print_styles-' . $this->page, array( $this, 'enqueue_styles' ) );
        add_action( 'admin_print_scripts-' . $this->page, array( $this, 'enqueue_scripts' ) );
    }

    public function check_page_available() {

    }

    public function getUpgradeMenuItemUrl() {
        return CHT_PRO_URL;
    }


    public function display_cht_admin_page() {
        include_once CHT_DIR . '/views/admin.php';
    }

    public function display_cht_admin_upgrade_page() {
        include_once CHT_DIR . '/views/upgrade.php';
    }

    protected function get_token()
    {
        return get_option('cht_license_key');
    }
    // this site domain
    public function get_site(){
       $permalink = get_home_url();
        return $permalink;
    }
      public function is_pro($token = '')
    {
        if ($token === '') {
            $token = $this->get_token();
        }
        return false;
    }

    public function data_has_expired()
    {
        return strtotime(date('Y-m-d')) > strtotime(date('Y-m-d', strtotime($this->data_activ())));
    }

    public function data_activ(){
        if (!$this->is_pro()) return;
        $body = null;
        if(empty(self::$response->errors)){
            $body = json_decode(self::$response['body']);
        }
        if (isset($body->expired_date)) return $body->expired_date->date;
    }
    public function data_check(){
        if ($this->is_pro()) {
            return false;
        };
    }
    public function get_current_color()
    {
        $def_color = get_option('cht_color');
        $custom_color = get_option('cht_custom_color');
        if (!$def_color) {
            $color = $custom_color;
        } else {
            $color = $def_color;
        }

        $color = strtoupper($color);
        return $color;
    }

    public function get_position_style()
    {
        $position = get_option('cht_position');

        if ($position === 'custom') {
            $pos_side = get_option('positionSide');
            $bot = (get_option('cht_bottom_spacing')) ? get_option('cht_bottom_spacing') : '25';
            $side = (get_option('cht_side_spacing')) ? get_option('cht_side_spacing') : '25';
            if ($pos_side === 'right') {
                $pos_style = 'left: auto; bottom: ' . $bot . 'px; right: ' . $side . 'px';
            } else {
                $pos_style = 'left: ' . $side . 'px; bottom: ' . $bot . 'px; right: auto';
            }
        } elseif ($position === 'right') {
            $pos_style = 'left: auto; bottom: 25px; right: 25px';
        } else {
            $pos_style = 'left: 25px; bottom: 25px; right: auto';
        }

        return $pos_style ;
    }

    public function cht_clear_all_caches()
    {
        global $wp_fastest_cache;
        // if W3 Total Cache is being used, clear the cache
        if (function_exists('w3tc_flush_all')) {
            w3tc_flush_all();
            /* if WP Super Cache is being used, clear the cache */
        } else if (function_exists('wp_cache_clean_cache')) {
            global $file_prefix, $supercachedir;
            if (empty($supercachedir) && function_exists('get_supercache_dir')) {
                $supercachedir = get_supercache_dir();
            }
            wp_cache_clean_cache($file_prefix);
        } else if (class_exists('WpeCommon')) {
            //be extra careful, just in case 3rd party changes things on us
            if (method_exists('WpeCommon', 'purge_memcached')) {
                WpeCommon::purge_memcached();
            }
            if (method_exists('WpeCommon', 'clear_maxcdn_cache')) {
                WpeCommon::clear_maxcdn_cache();
            }
            if (method_exists('WpeCommon', 'purge_varnish_cache')) {
                WpeCommon::purge_varnish_cache();
            }
        } else if (method_exists('WpFastestCache', 'deleteCache') && !empty($wp_fastest_cache)) {
            $wp_fastest_cache->deleteCache();
        } else if (function_exists( 'rocket_clean_domain' )) {
            rocket_clean_domain();
            // Preload cache.
            if ( function_exists( 'run_rocket_sitemap_preload' ) ) {
                run_rocket_sitemap_preload();
            }
        }
    }

    public static function chaty_sanitize_options($value) {
        $value = stripslashes($value);
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        return $value;
    }

    public function cht_register_inputs()
    {
        /**
         * Adding settings fields
         */
        // Section One
        foreach ($this->socials as $social) {
            add_settings_field(
                'cht_social_' . $social['slug'],
                ucfirst($social['slug']),
                '',
                $this->plugin_slug
            );
        }

        // Section Two
        add_settings_field('cht_devices', 'Devices', '', $this->plugin_slug);
        add_settings_field('cht_color', 'Color', '', $this->plugin_slug);
        add_settings_field('cht_custom_color', 'Color', '', $this->plugin_slug);
        add_settings_field('cht_position', 'Position', '', $this->plugin_slug);
        add_settings_field('positionSide', 'PositionSide', '', $this->plugin_slug);
        add_settings_field('cht_bottom_spacing', 'Bottom spacing', '', $this->plugin_slug);
        add_settings_field('cht_side_spacing', 'Side spacing', '', $this->plugin_slug);
        add_settings_field('cht_cta', 'CTA', '', $this->plugin_slug);
        add_settings_field('cht_credit', 'Credit', '', $this->plugin_slug);
        add_settings_field('cht_cta_switcher', 'CTA switcher', '', $this->plugin_slug);

        // section three
        add_settings_field('cht_active', 'Active', '', $this->plugin_slug);

        // token
        add_settings_field('cht_license_key', 'Token', '', $this->plugin_slug);

        // slug
        add_settings_field('cht_numb_slug', 'Numb', '', $this->plugin_slug);

        /**
         * Registering settings fields
         */

        /*check for nonce*/
        if(isset($_REQUEST['nonce']) && !empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], "chaty_plugin_nonce")) {

            // register field section one
            foreach ($this->socials as $social) {
                register_setting($this->plugin_slug, 'cht_social_' . $social['slug']);
            }

            // register field section two
            register_setting($this->plugin_slug, 'cht_devices', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'cht_color', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'cht_custom_color', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'cht_position', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'positionSide', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'cht_bottom_spacing', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'cht_side_spacing', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'cht_cta', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'cht_credit', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'cht_cta_switcher', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'cht_widget_size', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'cht_widget_img', 'chaty_sanitize_options');
            register_setting($this->plugin_slug, 'widget_icon', 'chaty_sanitize_options');
            add_action('pre_update_option_cht_widget_img', array($this, 'uploadCustomWidget'), 10, 3);

            // register field section three
            register_setting($this->plugin_slug, 'cht_active', 'chaty_sanitize_options');

            // register field section token
            register_setting($this->plugin_slug, 'cht_license_key', 'chaty_sanitize_options');

            // register field section slug
            register_setting($this->plugin_slug, 'cht_numb_slug', 'chaty_sanitize_options');
        }
    }

    public function device(){
        return'desktop_active mobile_active';
        if( isset(get_option('cht_devices')['desktop']) && isset(get_option('cht_devices')['mobile'])){
           return'desktop_active mobile_active';
        }else if( isset(get_option('cht_devices')['mobile'])){
            return 'mobile_active';
        }else if(isset(get_option('cht_devices')['desktop'])){
             return 'desktop_active';
        }else{
            return 'none';
        }
    }

    public function getCustomWidgetImg()
    {
        $value = get_option('cht_widget_img');
        return isset($value['url']) ? $value['url'] : '';
    }

    public function uploadCustomWidget($value, $old_value, $option)
    {
        $option = !empty($option) ? $option : 'cht_widget_img';
        $allowed_ext = ['jpeg', 'png', 'jpg' ,'svg'];
        if ( ! function_exists( 'wp_handle_upload' ) )
            require_once( ABSPATH . 'wp-admin/includes/file.php' );

        $file = &$_FILES[$option];
        $type = wp_check_filetype($file['name']);

        if (!in_array($type['ext'], $allowed_ext)) {
            return $old_value;
        }

        if (isset($old_value['file'])) {
            wp_delete_file($old_value['file']);
        }

        $overrides = array( 'test_form' => false );
        $movefile = wp_handle_upload( $file, $overrides );
        if ( $movefile && empty($movefile['error']) ) {
            return array(
                'file' => $movefile['file'],
                'url' => $movefile['url']
            );
        }
        return '';
    }

    public function getCallToAction()
    {
        if(get_option('cht_cta')){
            $res = nl2br(get_option('cht_cta'));
            $res = str_replace(array("\n", "\r"), "", $res);
            return $res;
        }
        return '';
    }
}

new CHT_Admin_Base();
