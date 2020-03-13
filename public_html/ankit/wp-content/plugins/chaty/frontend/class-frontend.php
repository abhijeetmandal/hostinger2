<?php

namespace CHT\frontend;

use CHT\admin\CHT_Admin_Base;
use CHT\admin\CHT_Social_Icons;

require_once CHT_ADMIN_INC . '/class-admin-base.php';
require_once CHT_ADMIN_INC . '/class-social-icons.php';

class CHT_Frontend extends CHT_Admin_Base
{
    /**
     * CHT_Frontend constructor.
     */
    public function __construct()
    {
        $this->socials = CHT_Social_Icons::get_instance()->get_icons_list();
        if (wp_doing_ajax()) {
            add_action('wp_ajax_choose_social', array($this, 'choose_social_handler'));
            add_action('wp_ajax_remove_social', array($this, 'remove_social_handler'));
            add_action('wp_ajax_add_token', array($this, 'add_token'));
            add_action('wp_ajax_del_token', array($this, 'del_token'));
        }

        add_action('wp_enqueue_scripts', array($this, 'front_styles'));
        add_action('wp_enqueue_scripts', array($this, 'front_scripts'));
        add_action('wp_footer', array($this, 'insert_widget'));
    }

    /**
     * Function add token
     */
    public function add_token()
    {
        check_ajax_referer('cht_nonce_ajax', 'nonce_code');
        $token = (!empty($_POST['token'])) ? sanitize_text_field($_POST['token']) : null;

        if ($token !== null) {
            if ($this->is_pro($token)) {
                update_option('cht_license_key', $token, 'no');
                echo 'true';
                wp_die();
            }
        }
        echo '';
        wp_die();

    }

    /**
     * Function add token
     */
    public function del_token()
    {
        check_ajax_referer('cht_nonce_ajax', 'nonce_code');
        if (!$this->is_pro()) {
            update_option('cht_license_key', '', '');
        }
        echo '';
        wp_die();

    }

    /**
     *
     */
    public function choose_social_handler()
    {
        check_ajax_referer('cht_nonce_ajax', 'nonce_code');
        $social = (!empty($_POST['social'])) ? sanitize_text_field($_POST['social']) : null;
        $version = (!empty($_POST['version'])) ? sanitize_text_field($_POST['version']) : '';

        if ($social !== null) {
            foreach ($this->socials as $item) {
                if ($item['slug'] == $social) {
                    break;
                }
            }

            if (!$item) {
                return;
            }

            // $token =  ($this->is_pro()) ? 'pro' : 'free';

            $social_opt = get_option('cht_social_' . $social);
            if (empty($social_opt)) {
                $social_opt = [
                    'value' => '',
                    'is_mobile' => 'checked',
                    'is_desktop' => 'checked'
                ];
            } else {
                $social_opt['is_desktop'] = isset($social_opt['is_desktop']) ? $social_opt['is_desktop'] : '';
                $social_opt['is_mobile'] = isset($social_opt['is_mobile']) ? $social_opt['is_mobile'] : '';
            }

            $social_opt['title'] = $item['title'];
            ob_start();
            $status = 0;
            ?>
            <li data-id="<?php echo $item['slug'] ?>" id="chaty-social-<?php echo $item['slug'] ?>">
                <div class="channels-selected__item <?php echo ($status)?"img-active":"" ?> <?php echo ($this->is_pro()) ? 'pro' : 'free'; ?> 1 available">
                    <div class="chaty-default-settings">
                        <div class="move-icon">
                            <img src="<?php echo plugin_dir_url("") ?>/chaty/assets/images/move-icon.png">
                        </div>
                        <div class="icon icon-md active" data-title="<?php echo $item['title']; ?>">
                                <span class="default-chaty-icon custom-icon-<?php echo $item['slug'] ?> default_image_<?php echo $item['slug'] ?>" >
                                    <svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <?php echo $item['svg']; ?>
                                    </svg>
                                </span>
                        </div>
                        <div class="channels__input-box">
                            <input type="text"
                                   class="channels__input"
                                   name="cht_social_<?php echo esc_attr($item['slug']); ?>[value]"
                                   value="<?php echo esc_attr($social_opt['value']); ?>"
                                   data-gramm_editor="false"
                                   id="<?php echo esc_attr($item['slug']); ?>"
                                >
                        </div>
                        <div>
                            <label class="channels__view"
                                   for="<?php echo str_replace(' ', '_', esc_attr($this->del_space($item['slug']))); ?>Desktop">
                                <input type="checkbox"
                                       id="<?php echo str_replace(' ', '_', esc_attr($this->del_space($item['slug']))); ?>Desktop"
                                       class="channels__view-check js-chanel-icon js-chanel-desktop"
                                       data-type="<?php echo str_replace(' ', '_', strtolower(esc_attr($this->del_space($item['slug'])))); ?>"
                                       name="cht_social_<?php echo esc_attr($item['slug']); ?>[is_desktop]"
                                       value="checked"
                                       data-gramm_editor="false"
                                    <?php echo isset($social_opt['is_desktop']) ? $social_opt['is_desktop'] : ''; ?>
                                    >
                                <span class="channels__view-txt">Desktop</label>
                            </label>
                            <label class="channels__view"
                                   for="<?php echo str_replace(' ', '_', esc_attr($this->del_space($item['slug']))); ?>Mobile">
                                <input type="checkbox"
                                       id="<?php echo str_replace(' ', '_', esc_attr($this->del_space($item['slug']))); ?>Mobile"
                                       class="channels__view-check js-chanel-icon js-chanel-mobile"
                                       data-type="<?php echo str_replace(' ', '_', strtolower(esc_attr($this->del_space($item['slug'])))); ?>"
                                       name="cht_social_<?php echo esc_attr($item['slug']); ?>[is_mobile]"
                                       value="checked"
                                       data-gramm_editor="false"
                                    <?php echo isset($social_opt['is_mobile']) ? $social_opt['is_mobile'] : ''; ?>
                                    >
                                <span class="channels__view-txt">Mobile</span>
                            </label>
                        </div>
                        <div class="chaty-settings" onclick="toggle_chaty_setting('<?php echo esc_attr($item['slug']); ?>')">
                            <a href="javascript:;"><span class="dashicons dashicons-admin-generic"></span></a>
                        </div>
                        <div class="input-example">
                            <?php _e('For example', CHT_OPT); ?>: <?php echo $item['example']; ?>
                        </div>
                        <?php if(isset($item['help']) && !empty($item['help'])) { ?>
                            <div class="viber-help">
                                <span><?php echo $item['help']; ?></span>
                                <?php echo isset($item['help_title'])?$item['help_title']:"Doesn't work?" ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="chaty-advance-settings">
                        <div class="chaty-setting-col">
                            <label>Icon Appearance</label>
                            <div>
                                <input readonly type="text" name="" class="chaty-color-field" value="" style="background-color: <?php echo $item['color'] ?>" />

                                <a href="javascript:;" class="upload-chaty-icon"><span class="dashicons dashicons-upload"></span> Custom Image</a>
                            </div>
                        </div>
                        <div class="clear clearfix"></div>
                        <div class="chaty-setting-col">
                            <label>On Hover Text</label>
                            <div>
                                <input readonly type="text" name="" value="<?php echo $social_opt['title'] ?>">
                            </div>
                        </div>
                        <div class="clear clearfix"></div>
                        <?php if($item['slug'] == "Whatsapp") { ?>
                            <div class="clear clearfix"></div>
                            <div class="chaty-setting-col">
                                <label>Pre Set Message</label>
                                <div>
                                    <input id="cht_social_message_<?php echo esc_attr($item['slug']); ?>" type="text" name="" value="" >
                                </div>
                            </div>
                        <?php } else if($item['slug'] == "Email") { ?>
                            <div class="clear clearfix"></div>
                            <div class="chaty-setting-col">
                                <label>Mail Subject</label>
                                <div>
                                    <input id="cht_social_message_<?php echo esc_attr($item['slug']); ?>" type="text" name="" value="" >
                                </div>
                            </div>
                        <?php } else if($item['slug'] == "WeChat") { ?>
                            <div class="clear clearfix"></div>
                            <div class="chaty-setting-col">
                                <label>Upload QR Code</label>
                                <div>
                                    <a class="cht-upload-image " id="upload_qr_code" href="javascript:;" >
                                        <span class="dashicons dashicons-upload"></span>
                                    </a>
                                </div>
                            </div>
                        <?php } else if($item['slug'] == "Link" || $item['slug'] == "Custom_Link") {
                            $is_checked = 1;
                            ?>
                            <div class="clear clearfix"></div>
                            <div class="chaty-setting-col">
                                <label >Open In a New Tab</label>
                                <div>
                                    <input type="hidden" name="cht_social_<?php echo esc_attr($item['slug']); ?>[new_window]" value="0" >
                                    <label class="channels__view" for="cht_social_window_<?php echo esc_attr($item['slug']); ?>">
                                        <input id="cht_social_window_<?php echo esc_attr($item['slug']); ?>" type="checkbox" class="channels__view-check" name="cht_social_<?php echo esc_attr($item['slug']); ?>[new_window]" value="1" <?php echo $is_checked?"checked":""; ?> >
                                        <span class="channels__view-txt">&nbsp;</span>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="chaty-pro-feature">
                            <a target="_blank" href="<?php echo $this->getUpgradeMenuItemUrl();?>">
                                <?php _e('Upgrade to Pro', CHT_OPT);?>
                            </a>
                        </div>
                    </div>
                    <button class="btn-cancel" data-social="<?php echo esc_attr($item['slug']); ?>">
                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <rect width="15.6301" height="2.24494" rx="1.12247"
                                  transform="translate(2.26764 0.0615997) rotate(45)" fill="white"/>
                            <rect width="15.6301" height="2.24494" rx="1.12247"
                                  transform="translate(13.3198 1.649) rotate(135)" fill="white"/>
                        </svg>
                    </button>
                </div>
            </li>
            <?php
            $html = ob_get_clean();

            echo json_encode($html);
        }


        wp_die();
    }

    public function remove_social_handler()
    {
        // $social = isset($_POST['social']) ? $_POST['social'] : '';
        // if ($social) {
        //     $res = delete_option('cht_social_' . $social);

        //     echo $res;
        // }
        wp_die();
    }

    public function front_styles()
    {
        // wp_enqueue_style( 'cht_widget_style', plugins_url('../assets/css/cht-widget.css', __FILE__));
    }

    public function front_scripts()
    {
        wp_enqueue_script('cht_widget_scripts', plugins_url('../assets/js/cht-scripts-front.min.js', __FILE__), array('jquery'));
//        wp_enqueue_script('cht_widget_server_scripts', plugins_url('../assets/js/server.js', __FILE__), array('jquery'));

    }

    public function int_arr()
    {

        $social = get_option('cht_numb_slug');
        $social = explode(",", $social);

        $arr = array();
        foreach ($social as $key_soc):
            foreach ($this->socials as $key => $social) :
                if ($social['slug'] != $key_soc) {
                    continue;
                }
                if ($value = get_option('cht_social_' . $social['slug'])) {
                    if (!empty($value['value']) && (wp_is_mobile() ? isset($value['is_mobile']) : isset($value['is_desktop']))) {
                        if($social['slug'] == "Viber") {
                            $val = $value['value'];
                            $fc = substr($val, 0, 1);
                            if ($fc == "+") {
                                $length = -1 * (strlen($val) - 1);
                                $val = substr($val, $length);
                            }
                            if (!wp_is_mobile()) {
                                $val = "+" . $val;
                            }
                            $arr[mb_strtolower($social['slug'])] = $val;
                        } else if($social['slug'] == "Whatsapp") {
                            $val = $value['value'];
                            $val = str_replace("+","", $val);
                            $arr[mb_strtolower($social['slug'])] = $val;
                        } else if($social['slug'] == "Facebook_Messenger") {
                            $val = $value['value'];
                            $val = str_replace("facebook.com","m.me", $val);
                            $val = str_replace("www.","", $val);
                            $val = esc_url($val);
                            $val = str_replace("http:", "https:", $val);
                            $value['value'] = $val;
                            $arr[mb_strtolower($social['slug'])] = $value['value'];
                        } else if($social['slug'] == "Link" || $social['slug'] == "Custom_Link") {
                            $value['value'] = esc_url($value['value']);
                            $arr[mb_strtolower($social['slug'])] = $value['value'];
                        } else {
                            $arr[mb_strtolower($social['slug'])] = $value['value'];
                        }
                    }
                };
            endforeach;
        endforeach;
        return $arr;
    }

    public function insert_widget()
    {
        if ($this->canInsertWidget()):
            include_once CHT_DIR . '/views/widget.php';
        endif;
    }

    private function canInsertWidget()
    {
        return get_option('cht_active') && $this->checkChannels();
    }

    private function checkChannels()
    {
        $social = explode(",", get_option('cht_numb_slug'));
        $res = false;
        foreach ($social as $name) {
            $value = get_option('cht_social_' . strtolower($name));
            $res = $res || !empty($value['value']) && (wp_is_mobile() ? isset($value['is_mobile']) : isset($value['is_desktop']));
        }
        return $res;
    }
}

return new CHT_Frontend();
