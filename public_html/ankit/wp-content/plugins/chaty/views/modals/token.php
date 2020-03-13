<div class="easy-modal" id="token-modal">
    <div class="easy-modal-inner">
        <button class="easy-modal-close" title="Close">
            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="15.6301" height="2.24494" rx="1.12247" transform="translate(1.5874) rotate(45)" fill="#4F4F4F"/>
                <rect width="15.6301" height="2.24494" rx="1.12247" transform="translate(12.6396 1.58742) rotate(135)" fill="#4F4F4F"/>
            </svg>
        </button>

        <h2><?php _e('Activate Pro Plan', CHT_OPT); ?></h2>
        <p class="text-center" style="font-size: 17px;">
            <?php _e('Enter your Pro token to activate all features', CHT_OPT); ?>
        </p>
           <?php settings_fields($this->plugin_slug); ?>
            <div class="form-horizontal__item">
                <label><?php _e('Token number', CHT_OPT); ?>:</label>               
                <input type="text"
                       name="cht_license_key"
                       value="<?php echo esc_attr($this->token); ?>"
                       placeholder="XXXXXXXXXXXXXXXXX">                               
            </div>

            <div class="text-center">
                <button class="btn-red update_token">
                    <?php _e('Upgrade Now', CHT_OPT); ?>
                    <svg width="17" height="19" viewBox="0 0 17 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.4674 7.42523L11.8646 0.128021C11.7548 0.128021 11.6449 0 11.4252 0C11.3154 0 11.0956 0 10.9858 0.128021L9.44777 1.92032C9.22806 2.17636 9.22806 2.56042 9.33791 2.81647L11.7548 6.017H0.549289C0.219716 6.017 0 6.27304 0 6.6571V9.21753C0 9.60159 0.219716 9.85763 0.549289 9.85763H11.8646L9.44777 13.0582C9.22806 13.3142 9.22806 13.6983 9.44777 13.9543L11.0956 15.6186C11.2055 15.7466 11.3154 15.7466 11.4252 15.7466C11.5351 15.7466 11.7548 15.6186 11.8646 15.4906L17.4674 8.19336C17.5772 8.06534 17.5772 7.68127 17.4674 7.42523Z" transform="translate(0.701416 18.3653) rotate(-90)" fill="white"/>
                    </svg>
                </button>
            </div>
        <div class="text-center">
            <a href="#" class="easy-modal-link show_up"><?php _e('How to get a token?', CHT_OPT); ?></a>
        </div>
    </div>
</div>