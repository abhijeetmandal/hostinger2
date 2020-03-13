<section class="section">
    <h1 class="section-title">
        <strong><?php _e('Step', CHT_OPT);?> 3:</strong> <?php _e('Launch it!', CHT_OPT);?>
    </h1>

    <div class="form-horizontal">
        <div class="form-horizontal__item flex-center">
            <label class="form-horizontal__item-label"><?php _e('Active', CHT_OPT);?>:</label>
            <div>
                <label class="switch">
                    <span class="switch__label"><?php _e('Off', CHT_OPT);?></span>
                    <?php $cht_active = get_option('cht_active'); ?>
                    <input data-gramm_editor="false" type="checkbox" name="cht_active" value="1" <?php echo (!empty($cht_active)) ? 'checked' : ''; ?>>
                    <span class="switch__styled"></span>
                    <span class="switch__label"><?php _e('On', CHT_OPT);?></span>
                </label>
            </div>
        </div>
    </div>
    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce("chaty_plugin_nonce") ?>">
    <div class="text-center">
        <button class="btn-save">
            <?php _e('Save Changes', CHT_OPT); ?>
        </button>
    </div>
</section>
