<section class="section section2" xmlns="http://www.w3.org/1999/html">
    <h1 class="section-title">
        <strong><?php _e('Step', CHT_OPT);?> 2:</strong> <?php _e('Customize your widget', CHT_OPT);?>
    </h1>

    <div class="form-horizontal">
        <?php
            // Color
            $color = $this->get_current_color();
        ?>
        <div class="form-horizontal__item">
            <label class="align-top form-horizontal__item-label"><?php _e('Color', 'chaty'); ?>:</label>
            <div>
                <div class="color-picker-dropdown">
                    <div class="color-picker">
                        <div class="color-picker-radio">
                            <label style="background-color: #A886CD">
                                <input type="checkbox" name="cht_color" value="#A886CD" title="Purple"
                                       <?php
                                       if (!$color) {
                                           echo 'checked';
                                       } elseif($color === '#A886CD') {
                                           echo 'checked';
                                       }
                                       ?>
                                >
                                <span></span>
                            </label>

                            <label style="background-color: #86CD91">
                                <input type="checkbox" name="cht_color" value="#86CD91" title="Green"
                                       <?php echo ($color === '#86CD91') ? 'checked' : ''; ?>
                                >
                                <span></span>
                            </label>

                            <label style="background-color: #4F6ACA">
                                <input type="checkbox" name="cht_color" value="#4F6ACA" title="Blue"
                                    <?php echo ($color === '#4F6ACA') ? 'checked' : ''; ?>
                                >
                                <span></span>
                            </label>

                            <label style="background-color: #FF6060">
                                <input type="checkbox" name="cht_color" value="#FF6060" title="Red"
                                    <?php echo ($color === '#FF6060') ? 'checked' : ''; ?>
                                >
                                <span></span>
                            </label>

                            <label style="background-color: #000">
                                <input type="checkbox" name="cht_color" value="#000" title="Black"
                                    <?php echo ($color === '#000') ? 'checked' : ''; ?>
                                >
                                <span></span>
                            </label>

                            <label style="background-color: #EEF075">
                                <input type="checkbox" name="cht_color" value="#EEF075" title="Yellow"
                                    <?php echo ($color === '#EEF075') ? 'checked' : ''; ?>
                                >
                                <span></span>
                            </label>

                            <label style="background-color: #FF95EE">
                                <input type="checkbox" name="cht_color" value="#FF95EE" title="Pink"
                                    <?php echo ($color === '#FF95EE') ? 'checked' : ''; ?>
                                >
                                <span></span>
                            </label>
                        </div>

                        <div class="color-picker-custom">
                            <?php if ($this->is_pro()) : ?>
                                <div>
                                    <?php
                                      $os = array("#86CD91", "#A886CD", "#4F6ACA", "#FF6060", "#000", "#EEF075", "#FF95EE");
                                        if (in_array($color, $os)) {
                                          $color = '';
                                        }
                                    ?>
                                    <?php if($color): ?>
                                        <div class="circle" style="background-color: <?php echo $color;?> ">

                                        </div><?php _e('Custom color', CHT_OPT);?>
                                    <?php else: ?>
                                       <div class="circle">?</div><?php _e('Custom color', CHT_OPT);?>
                                    <?php endif ?>
                                </div>

                                <div>
                                    <input type="input" name="cht_custom_color" placeholder="HEX code: #ffffff"
                                           value="<?php echo $color; ?>"
                                    />
                                    <button class="btn-red">Ok</button>
                                </div>
                            <?php else : ?>

                                <div>
                                    <div class="circle">?</div>
                                    <?php _e('Custom color', CHT_OPT);?>
                                    <a href="<?php echo $this->getUpgradeMenuItemUrl();?>">
                                        (<?php _e('upgrade to pro', CHT_OPT);?>)
                                    </a>
                                </div>

                                <div>
                                  <input type="input" name="cht_custom_colo" placeholder="HEX code: #ffffff"
                                           value="" readonly style="cursor:not-allowed;width: 172px;"
                                  />
                                    <button class="btn-red"><?php _e('Ok', CHT_OPT);?></button>
                                </div>
                            <?php endif; ?>
                        </div>
                          <?php $color = $this->get_current_color();?>
                        <button class="color-picker-btn-close">
                            <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 0L4.31505 3.77708L8.63008 0" transform="translate(1.37436 1.31006)" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M0 0L4.31505 3.77708L8.63008 0" transform="translate(1.37436 1.31006)" stroke="#4F4F4F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>

                    <button class="color-picker-btn">
                        <span class="circle" style="background-color: <?php echo (!empty($color)) ? $color : '#A886CD'; ?>"></span>
                        <span class="text">
                            <?php
                                if (!empty($color)) {
                                    $colors = $this->colors;
                                    if (isset($colors[$color])) {
                                        echo $colors[$color];
                                    } else {
                                        echo __('Custom', CHT_OPT);
                                    }
                                } else {
                                    _e('Purple', CHT_OPT);
                                }
                            ?>
                        </span>

                        <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0L4.31505 3.77708L8.63008 0" transform="translate(1.37436 1.31006)" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M0 0L4.31505 3.77708L8.63008 0" transform="translate(1.37436 1.31006)" stroke="#4F4F4F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <?php // Position ?>
        <div class="form-horizontal__item">
            <label class="align-top form-horizontal__item-label">Position:</label>
            <div>
                <?php $position = get_option('cht_position'); ?>
                <label class="custom-control custom-radio">
                    <input type="radio"
                           name="cht_position"
                           class="custom-control-input"
                           <?php echo ($position === "left") ? 'checked' : ''; ?>
                           value="left"
                    />
                    <span class="custom-control-label"><?php _e('Left', CHT_OPT);?></span>
                </label>

                <label class="custom-control custom-radio">
                    <input type="radio"
                           name="cht_position"
                           class="custom-control-input"
                           <?php echo ($position === "right") ? 'checked' : ''; ?>
                           value="right"
                    />
                    <span class="custom-control-label"><?php _e('Right', CHT_OPT);?></span>
                </label>

                <?php if ($this->is_pro()) : ?>
                    <label class="custom-control custom-radio">
                        <input type="radio"
                               id="positionCustom"
                               name="cht_position"
                               class="custom-control-input position-pro-radio"
                               <?php echo ($position === 'custom') ? 'checked' : ''; ?>
                               value="custom"
                        />
                        <span class="custom-control-label">
                            <?php _e('Custom', CHT_OPT);?>
                        </span>
                    </label>
                <?php else: ?>
                    <span class="custom-control custom-radio free-custom-radio">
                        <input type="radio" class="custom-control-input" disabled>
                        <span class="custom-control-label"><?php _e('Custom', CHT_OPT);?> </span>
                    </span>
                    <a href="<?php echo $this->getUpgradeMenuItemUrl();?>">(<?php _e('upgrade to pro', CHT_OPT);?>)</a>
                <?php endif; ?>

                <div id="positionPro">
                    <div class="position-pro">
                        <div>
                            <label>Side selection:</label>
                            <?php $positionSide = get_option('positionSide') ?>
                            <?php $pos_custom = empty($positionSide) ? 'right' : $positionSide; ?>
                            <div class="custom-control-group">
                                <label class="custom-control custom-radio custom-radio-btn">
                                    <input type="radio" value="left" name="positionSide" class="custom-control-input"
                                        <?php echo (!empty($pos_custom) && $pos_custom === 'left') ? 'checked' : ''; ?>
                                    >
                                    <span class="custom-control-label">
                                          <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.92473 5.70473L2.4086 0.20886C2.30824 0.00901022 2.00717 -0.0909147 1.70609 0.108935L0.200717 1.30803C0.100358 1.40796 0 1.50788 0 1.70773C0 1.90758 -9.72047e-08 1.90758 0.100358 2.00751L3.31183 6.00451L0.100358 10.0015C-9.72047e-08 10.1014 0 10.2014 0 10.3013C0 10.4012 0.100358 10.6011 0.200717 10.6011L1.70609 11.8002C1.80645 12 1.90681 12 2.00717 12C2.10753 12 2.30824 11.9001 2.4086 11.8002L6.92473 6.30428C7.02509 6.10443 7.02509 5.90458 6.92473 5.70473Z" transform="translate(7 12) rotate(180)" fill="#E0E0E0"/>
                                          </svg>
                                        <?php _e('Left', CHT_OPT); ?>
                                        </span>
                                </label>
                                <label class="custom-control custom-radio custom-radio-btn">
                                    <input type="radio" value="right" name="positionSide" class="custom-control-input"
                                        <?php echo (!empty($pos_custom) && $pos_custom === 'right') ? 'checked' : ''; ?>
                                    >
                                    <span class="custom-control-label">
                                          <?php _e('Right', CHT_OPT); ?>
                                        <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.92473 5.70473L2.4086 0.20886C2.30824 0.00901022 2.00717 -0.0909147 1.70609 0.108935L0.200717 1.30803C0.100358 1.40796 0 1.50788 0 1.70773C0 1.90758 -9.72047e-08 1.90758 0.100358 2.00751L3.31183 6.00451L0.100358 10.0015C-9.72047e-08 10.1014 0 10.2014 0 10.3013C0 10.4012 0.100358 10.6011 0.200717 10.6011L1.70609 11.8002C1.80645 12 1.90681 12 2.00717 12C2.10753 12 2.30824 11.9001 2.4086 11.8002L6.92473 6.30428C7.02509 6.10443 7.02509 5.90458 6.92473 5.70473Z" fill="#E0E0E0"/>
                                          </svg>
                                        </span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label><?php _e('Bottom spacing', CHT_OPT); ?>:</label>
                            <input type="number"
                                   name="cht_bottom_spacing"
                                   id="positionBottom"
                                   max="2000"
                                   value="<?php  echo esc_attr(get_option('cht_bottom_spacing')) ? esc_attr(get_option('cht_bottom_spacing')) : 25 ; ?>"
                                   placeholder="25">px
                        </div>

                        <div>
                            <label><?php _e('Side spacing', CHT_OPT); ?>:</label>
                            <input type="number"
                                   name="cht_side_spacing"
                                   id="positionSide"
                                   max="2000"
                                   value="<?php  echo esc_attr(get_option('cht_side_spacing')) ? esc_attr(get_option('cht_side_spacing')) : 25 ; ?>"
                                   placeholder="25">px
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <?php // Call to Action ?>
        <div class="form-horizontal__item">
            <label class="align-top form-horizontal__item-label"><?php _e('Call to action', CHT_OPT);?>:</label>
            <div> <script type="text/javascript">
               var keynum, lines = 1;

      function limitLines(obj, e) {
        // IE
        if(window.event) {
          keynum = e.keyCode;
        // Netscape/Firefox/Opera
        } else if(e.which) {
          keynum = e.which;
        }
        if(keynum == 13) {
            var text = jQuery(".test_textarea").val();
            var lines = text.split(/\r|\r\n|\n/);
            var count = lines.length;
            console.log(count); // Outputs 4
          if(count >= obj.rows) {
            return false;
          }else{
            lines++;
          }
        }
      }
            </script>
        <textarea type="text" data-gramm_editor="false"  class="test_textarea" cols="40" rows="2" name="cht_cta" value="<?php  $cta =nl2br(get_option('cht_cta'));
         echo str_replace(array("\r","\n"),"",$cta); ?>" placeholder="<?php _e('Message us!', CHT_OPT);?>" onkeydown="return limitLines(this, event)"><?php  echo get_option('cht_cta'); ?></textarea>
            </div>
        </div>

        <div class="form-horizontal__item widget-icon__block">
            <label class="form-horizontal__item-label">Widget icon:</label>
            <label class="widget-icon__wrap">
                <label class="custom-control custom-radio">
                    <input type="radio"
                           name="widget_icon"
                           class="custom-control-input js-widget-i "
                           value="chat-base"
                           data-type="chat-base"
                           data-gramm_editor="false"
                            <?php echo (esc_attr(get_option('widget_icon')) == 'chat-base') ? 'checked' : '' ?>
                    >
                    <i class="icon-chat" data-type="chat-base">
                        <svg version="1.1" id="ch" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496 507.7 54 54" style="enable-background:new -496 507.7 54 54;" xml:space="preserve">
                            <style type="text/css">.st1 {fill: #FFFFFF;}.st0{fill: #808080;}</style>
                            <g><circle cx="-469" cy="534.7" r="27" fill="#a886cd"/></g>
                            <path class="st1" d="M-459.9,523.7h-20.3c-1.9,0-3.4,1.5-3.4,3.4v15.3c0,1.9,1.5,3.4,3.4,3.4h11.4l5.9,4.9c0.2,0.2,0.3,0.2,0.5,0.2 h0.3c0.3-0.2,0.5-0.5,0.5-0.8v-4.2h1.7c1.9,0,3.4-1.5,3.4-3.4v-15.3C-456.5,525.2-458,523.7-459.9,523.7z"/>
                            <path class="st0" d="M-477.7,530.5h11.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-11.9c-0.5,0-0.8-0.4-0.8-0.8l0,0C-478.6,530.8-478.2,530.5-477.7,530.5z"/>
                            <path class="st0" d="M-477.7,533.5h7.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-7.9c-0.5,0-0.8-0.4-0.8-0.8l0,0C-478.6,533.9-478.2,533.5-477.7,533.5z"/>
                        </svg>
                    </i>
                    <span class="custom-control-label"></span>
                </label>

                <?php if(!$this->is_pro()): ?>
                    <a class="custom-control custom-radio " href="<?php echo $this->getUpgradeMenuItemUrl();?>" >
                <?php else:?>
                    <label class="custom-control custom-radio <?php if(!$this->is_pro()) echo 'easy-modal-open'; ?>" href="#upgrade-modal">
                <?php endif;?>

                    <input type="radio"
                           name="widget_icon"
                           class="custom-control-input js-widget-i"
                           value="chat-smile"
                           data-type="chat-smile"
                           data-gramm_editor="false"
                        <?php echo (esc_attr(get_option('widget_icon')) == 'chat-smile') ? 'checked' : '' ?>
                        <?php if(!$this->is_pro()) echo 'disabled'; ?>
                    >
                    <i class="icon-chat" data-type="chat-smile">
                        <svg version="1.1" id="smile" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="-496.8 507.1 54 54" style="enable-background:new -496.8 507.1 54 54;" xml:space="preserve">
                            <style type="text/css">.st1{fill:#FFFFFF;}  .st2{fill:none;stroke:#808080;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;}</style>
                            <g>
                                <circle cx="-469.8" cy="534.1" r="27" fill="#a886cd"/>
                            </g>
                            <path class="st1" d="M-459.5,523.5H-482c-2.1,0-3.7,1.7-3.7,3.7v13.1c0,2.1,1.7,3.7,3.7,3.7h19.3l5.4,5.4c0.2,0.2,0.4,0.2,0.7,0.2
	c0.2,0,0.2,0,0.4,0c0.4-0.2,0.6-0.6,0.6-0.9v-21.5C-455.8,525.2-457.5,523.5-459.5,523.5z"/>
                            <path class="st2" d="M-476.5,537.3c2.5,1.1,8.5,2.1,13-2.7"/>
                            <path class="st2" d="M-460.8,534.5c-0.1-1.2-0.8-3.4-3.3-2.8"/></svg>

                    </i>
                    <span class="custom-control-label"></span>
                <?php if(!$this->is_pro()): ?>
                    </a>
                <?php else:?>
                    </label>
                <?php endif;?>

                <?php if(!$this->is_pro()): ?>
                    <a class="custom-control custom-radio " href="<?php echo $this->getUpgradeMenuItemUrl();?>" >
                <?php else:?>
                    <label class="custom-control custom-radio">
                <?php endif;?>
                        <input type="radio"
                               name="widget_icon"
                               class="custom-control-input js-widget-i"
                               value="chat-bubble"
                               data-type="chat-bubble"
                               data-gramm_editor="false"
                            <?php echo (esc_attr(get_option('widget_icon')) == 'chat-bubble') ? 'checked' : '' ?>
                            <?php if(!$this->is_pro()) echo 'disabled'; ?>
                        >
                    <i class="icon-chat" data-type="chat-bubble">
                        <svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496.9 507.1 54 54" style="enable-background:new -496.9 507.1 54 54;" xml:space="preserve">
<style type="text/css">.st1{fill:#FFFFFF;}</style>
                            <g><circle  cx="-469.9" cy="534.1" r="27" fill="#a886cd"/></g>
                            <path class="st1" d="M-472.6,522.1h5.3c3,0,6,1.2,8.1,3.4c2.1,2.1,3.4,5.1,3.4,8.1c0,6-4.6,11-10.6,11.5v4.4c0,0.4-0.2,0.7-0.5,0.9
	c-0.2,0-0.2,0-0.4,0c-0.2,0-0.5-0.2-0.7-0.4l-4.6-5c-3,0-6-1.2-8.1-3.4s-3.4-5.1-3.4-8.1C-484.1,527.2-478.9,522.1-472.6,522.1z
	 M-462.9,535.3c1.1,0,1.8-0.7,1.8-1.8c0-1.1-0.7-1.8-1.8-1.8c-1.1,0-1.8,0.7-1.8,1.8C-464.6,534.6-463.9,535.3-462.9,535.3z
	 M-469.9,535.3c1.1,0,1.8-0.7,1.8-1.8c0-1.1-0.7-1.8-1.8-1.8c-1.1,0-1.8,0.7-1.8,1.8C-471.7,534.6-471,535.3-469.9,535.3z
	 M-477,535.3c1.1,0,1.8-0.7,1.8-1.8c0-1.1-0.7-1.8-1.8-1.8c-1.1,0-1.8,0.7-1.8,1.8C-478.8,534.6-478.1,535.3-477,535.3z"/>
</svg>
                    </i>
                    <span class="custom-control-label"></span>
                    <?php if(!$this->is_pro()): ?>
                        </a>
                    <?php else:?>
                        </label>
                    <?php endif;?>


                    <?php if(!$this->is_pro()): ?>
                        <a class="custom-control custom-radio " href="<?php echo $this->getUpgradeMenuItemUrl();?>" >
                    <?php else:?>
                        <label class="custom-control custom-radio">
                    <?php endif;?>
                    <input type="radio"
                           name="widget_icon"
                           class="custom-control-input js-widget-i"
                           value="chat-db"
                           data-type="chat-db"
                           data-gramm_editor="false"
                        <?php echo (esc_attr(get_option('widget_icon')) == 'chat-db') ? 'checked' : '' ?>
                        <?php if(!$this->is_pro()) echo 'disabled'; ?>
                    >
                    <i class="icon-chat" data-type="chat-db">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496 507.1 54 54" style="enable-background:new -496 507.1 54 54;" xml:space="preserve">
<style type="text/css">.st1{fill:#FFFFFF;}</style>
                            <g><circle  cx="-469" cy="534.1" r="27" fill="#a886cd"/></g>
                            <path class="st1" d="M-464.6,527.7h-15.6c-1.9,0-3.5,1.6-3.5,3.5v10.4c0,1.9,1.6,3.5,3.5,3.5h12.6l5,5c0.2,0.2,0.3,0.2,0.7,0.2
	c0.2,0,0.2,0,0.3,0c0.3-0.2,0.5-0.5,0.5-0.9v-18.2C-461.1,529.3-462.7,527.7-464.6,527.7z"/>
                            <path class="st1" d="M-459.4,522.5H-475c-1.9,0-3.5,1.6-3.5,3.5h13.9c2.9,0,5.2,2.3,5.2,5.2v11.6l1.9,1.9c0.2,0.2,0.3,0.2,0.7,0.2
	c0.2,0,0.2,0,0.3,0c0.3-0.2,0.5-0.5,0.5-0.9v-18C-455.9,524.1-457.5,522.5-459.4,522.5z"/>
</svg>

                    </i>
                    <span class="custom-control-label"></span>
                    <?php if(!$this->is_pro()): ?>
                        </a>
                    <?php else:?>
                        </label>
                    <?php endif;?>

                <?php if(!$this->is_pro()): ?>
                    <a class="custom-control custom-radio " href="<?php echo $this->getUpgradeMenuItemUrl();?>" >
                <?php else:?>
                    <label class="custom-control custom-radio">
                <?php endif;?>
                    <div class="form-group">
                        <div id="elPreviewImage"></div>
                        <div class="file-loading">
                            <input
                                    type="file"
                                    id="testUpload"
                                    name="cht_widget_img"
                                <?php if(!$this->is_pro()) echo 'disabled'; ?>
                            >
                        </div>
                        <div id="errorBlock" class="help-block"></div>
                    </div>
                    <?php if($this->is_pro()): ?>
                        <input type="radio"
                               name="widget_icon"
                               class="custom-control-input js-widget-i js-upload"
                               value="chat-image"
                               data-gramm_editor="false"
                            <?php if(!$this->is_pro()) echo 'disabled'; ?>
                               data-type="chat-image"
                               id="uploadInput"
                            <?php echo (esc_attr(get_option('widget_icon')) == 'chat-image') ? 'checked' : '' ?>
                        >
                        <span class="custom-control-label"></span>
                    <?php endif; ?>
                    <span class="custom-control-label"></span>
                <?php if(!$this->is_pro()): ?>
                    </a>
                <?php else:?>
                    </label>
                <?php endif;?>
            <?php if(!$this->is_pro()): ?>
                <a class="upgrade-link" href="<?php echo $this->getUpgradeMenuItemUrl();?>">(<?php _e('upgrade to pro', CHT_OPT);?>)</a>
            <?php endif;?>

                <script type="text/javascript">
                    (function ($) {
                        $(document).ready(function () {
                            $('#testUpload').fileinput({
                                showCaption: false,
                                showCancel: false,
                                showClose: false,
                                showRemove: false,
                                showUpload: false,
                                browseIcon: "<i class='icon-upload'></i>",
                                browseLabel: 'Upload',
                                browseClass: 'file-browse',
                                overwriteInitial: false,
                                initialPreviewCount: false,
                                allowedFileTypes: ['image'],
                                maxFileCount:1,
                                initialPreviewAsData: true,
                                elPreviewImage: '#elPreviewImage',
                                initialPreview: [
                                    "<?php echo $this->getCustomWidgetImg();?>",
                                ],
                                layoutTemplates:{
                                    progress: '',
                                    actionDelete: '',
                                    actionZoom:'',
                                    preview: ''
                                }
                            });
                        });
                    }(jQuery));
                </script>
            </div>
        </div>

        <div class="form-horizontal__item">
            <label class="form-horizontal__item-label">Widget size:</label>
            <div>
                <input type="number"
                       min="24"
                       max="100"
                       name="cht_widget_size"
                       class="widget-size__input"
                       data-gramm_editor="false"
                       value="<?php  echo esc_attr(get_option('cht_widget_size')) ? esc_attr(get_option('cht_widget_size')) : 54 ; ?>"
                       <?php if(!$this->is_pro()) echo 'disabled'; ?>
                />
                <span class="custom-control-label">
                    px
                    <?php if(!$this->is_pro()): ?>
                        <a href="<?php echo $this->getUpgradeMenuItemUrl();?>"> (<?php _e('upgrade to pro', CHT_OPT);?>)</a>
                    <?php endif ?>
                </span>
            </div>
        </div>

    <div class="form-horizontal__item flex-center">
        <label class="form-horizontal__item-label"><?php _e('Google Analytics', CHT_OPT);?>:</label>
        <div>
            <label class="switch">
                <?php
                $checked = get_option('cht_google_analytics');
                ?>
                <span class="switch__label"><?php _e('Off', CHT_OPT);?></span>
                <input disabled data-gramm_editor="false" type="checkbox" name="cht_google_analytics" value="1" <?php echo (!empty($checked)) ? 'checked' : ''; ?>>
                <span class="switch__styled"></span>
                <span class="switch__label"><?php _e('On', CHT_OPT);?></span>&nbsp;&nbsp;
                <a href="<?php echo $this->getUpgradeMenuItemUrl();?>">(<?php _e('upgrade to pro', CHT_OPT);?>)</a>
            </label>
        </div>
    </div>

    <div class="form-horizontal__item" id="custom-rules">
        <label class="form-horizontal__item-label">Show on:</label>
        <div class="chaty-option-box">
            <div class="page-options">
                <div class="chaty-page-option">
                    <div class="url-content">
                        <div class="url-select">
                            <select class="cht-required" id="url_shown_on___count___option">
                                <option value="show_on">Show on</option>
                                <option value="not_show_on">Don't show on</option>
                            </select>
                        </div>
                        <div class="url-option">
                            <select class="url-options cht-required" id="url_rules___count___option">
                                <option selected="selected" disabled value="">Select Rule</option>
                            </select>
                        </div>
                        <div class="url-box">
                            <span class='chaty-url'><?php echo site_url("/"); ?></span>
                        </div>
                        <div class="url-values">
                            <input type="text" class="cht-required" value="" id="url_rules___count___value" />
                        </div>
                        <div class="url-buttons">
                            <a class="remove-chaty" href="javascript:;">
                                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="15.6301" height="2.24494" rx="1.12247" transform="translate(2.26764 0.0615997) rotate(45)" fill="white"></rect>
                                    <rect width="15.6301" height="2.24494" rx="1.12247" transform="translate(13.3198 1.649) rotate(135)" fill="white"></rect>
                                </svg>
                            </a>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="chaty-pro-feature">
                    <a target="_blank" href="<?php echo $this->getUpgradeMenuItemUrl();?>">
                        <?php _e('Upgrade to Pro', CHT_OPT);?>
                    </a>
                </div>
            </div>
            <a href="javascript:;" class="create-rule" id="create-rule">Add Rule</a>
        </div>
    </div>

        <?php // Credit ?>
        <div class="form-horizontal__item credit">
            <?php if ($this->is_pro()) : ?>
                <label></label>
            <?php else: ?>
                <label class="form-horizontal__item-label">Credit:</label>
                <div>
                    <label class="custom-control custom-checkbox free-custom-checkbox">
                        <input type="checkbox"
                               class="custom-control-input"
                               disabled
                               data-gramm_editor="false"
                        >
                        <span class="custom-control-label"><?php _e('Remove "Get widget" link', CHT_OPT);?></span>
                    </label>
                    <a href="<?php echo $this->getUpgradeMenuItemUrl();?>">(<?php _e('upgrade to pro', CHT_OPT);?>)</a>
                </div>
            <?php endif; ?>
        </div>
</section>
