<?php
if (!$this->is_pro()) {
    if (get_option('cht_position') == 'custom') {
        update_option('cht_position', 'right');
    }
    $social = get_option('cht_numb_slug');
    $social = explode(",", $social);
    $social = array_splice($social, 0, 3);
    $social = implode(',', $social);
    update_option('cht_numb_slug', $social);
    if (get_option('cht_custom_color') != '') {
        update_option('cht_custom_color', '');
        update_option('cht_color', '#A886CD');
    }
}
?>

<div class="container <?php echo (!$this->is_pro() && get_option('cht_license_key') != '') ? 'none_pro' : '' ?>"
     dir="ltr">
    <?php require_once 'parts/custom-styles.php'; ?>
    <header class="header">
        <img src="<?php echo esc_url(plugins_url('../assets/images/logo.svg', __FILE__)); ?>" alt="Chaty" class="logo">
        <?php settings_errors(); ?>
        <div class="ml-auto">
            <?php if (!$this->is_pro() && get_option('cht_license_key') == ''): ?>
                <button class="btn-white easy-modal-open" href="#token-modal">
                    <?php _e('Enter Token', CHT_OPT); ?>
                </button>
            <?php endif ?>

            <?php require_once 'modals/token.php'; ?>

            <?php if ($this->data_check() && $this->is_pro()): ?>

                <a class="btn-red" href="<?php echo $this->getUpgradeMenuItemUrl(); ?>">
                    <?php _e('Renew now', CHT_OPT); ?>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3.0746 9.2C2.5746 7 4.2746 5 6.4746 5H6.9746V6.5C6.9746 6.7 7.0746 6.9 7.2746 7C7.4746 7 7.6746 7 7.7746 6.9L10.7746 3.9C10.9746 3.7 10.9746 3.4 10.7746 3.2L7.7746 0.2C7.6746 0 7.4746 0 7.2746 0C7.0746 0.1 6.9746 0.3 6.9746 0.5V2H6.4746C2.5746 2 -0.525402 5.4 0.0745975 9.4C0.274598 10.9 1.0746 12.3 2.1746 13.3C2.3746 13.5 2.6746 13.5 2.8746 13.3L4.2746 11.9C4.4746 11.7 4.4746 11.4 4.2746 11.2C3.5746 10.6 3.1746 10 3.0746 9.2Z"
                            fill="white"/>
                        <path
                            d="M8.95 0.15C8.75 -0.0500001 8.45 -0.0500001 8.25 0.15L6.85 1.55C6.65 1.75 6.65 2.05 6.85 2.25C7.35 2.75 7.75 3.35 7.95 4.15C8.45 6.35 6.75 8.35 4.55 8.35H4.05V6.85C4.05 6.65 3.95 6.45 3.75 6.35C3.55 6.25 3.35 6.35 3.15 6.55L0.15 9.55C-0.0500001 9.75 -0.0500001 10.05 0.15 10.25L3.15 13.25C3.35 13.35 3.55 13.35 3.75 13.35C3.95 13.25 4.05 13.05 4.05 12.85V11.35H4.55C8.45 11.35 11.55 7.95 10.95 3.95C10.75 2.45 10.05 1.15 8.95 0.15Z"
                            transform="translate(4.92456 2.64999)" fill="white"/>
                    </svg>
                </a>
                <?php if ($this->is_pro()): ?>
                    <p class="text_update"
                       style="color:#fff; left: 0px;"><?php _e('Your Pro plan expires on', CHT_OPT); ?> <?php echo date('F jS, Y', strtotime($this->data_activ())); ?></p>
                <?php endif ?>

            <?php elseif (!$this->data_check() && !$this->is_pro()): ?>

                <a class="btn-red" href="<?php echo $this->getUpgradeMenuItemUrl(); ?>">
                    <?php _e('Upgrade Now', CHT_OPT); ?>
                    <svg width="17" height="19" viewBox="0 0 17 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17.4674 7.42523L11.8646 0.128021C11.7548 0.128021 11.6449 0 11.4252 0C11.3154 0 11.0956 0 10.9858 0.128021L9.44777 1.92032C9.22806 2.17636 9.22806 2.56042 9.33791 2.81647L11.7548 6.017H0.549289C0.219716 6.017 0 6.27304 0 6.6571V9.21753C0 9.60159 0.219716 9.85763 0.549289 9.85763H11.8646L9.44777 13.0582C9.22806 13.3142 9.22806 13.6983 9.44777 13.9543L11.0956 15.6186C11.2055 15.7466 11.3154 15.7466 11.4252 15.7466C11.5351 15.7466 11.7548 15.6186 11.8646 15.4906L17.4674 8.19336C17.5772 8.06534 17.5772 7.68127 17.4674 7.42523Z"
                            transform="translate(0.701416 18.3653) rotate(-90)" fill="white"/>
                    </svg>
                </a>

            <?php else: ?>
                <p class="plan_date">Your pro plan is valid
                    until <?php echo date('F jS, Y', strtotime($this->data_activ())); ?></p>
            <?php endif ?>
        </div>
    </header>

    <main class="main">
        <form id="cht-form" action="options.php" method="POST" enctype="multipart/form-data" data-auto="false">
            <?php settings_fields($this->plugin_slug); ?>
            <?php require_once 'parts/section1.php'; ?>
            <?php require_once 'parts/section2.php'; ?>
            <?php require_once 'parts/section3.php'; ?>
            <input type="text"
                   name="cht_license_key"
                   value="<?php echo esc_attr($this->token) ?>"
                   hidden>
            <?php submit_button(null, null, null, false); ?>
        </form>
    </main>

    <?php require_once 'parts/help.php'; ?>
</div>

<script>
    jQuery(document).ready(function(){
        jQuery("#cht-form").submit(function () {
            phoneNumberReg = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
            if (jQuery("#cht-form #Whatsapp").length && jQuery("#cht-form #Whatsapp").val() != "") {
                InputVal = jQuery.trim(jQuery("#cht-form #Whatsapp").val());
                if (check_for_number_chaty(InputVal, "Whatsapp")) {
                    alert("<?php echo __("For channel Whatsapp, please enter a valid phone number", CHT_OPT) ?>");
                    jQuery("#cht-form #Whatsapp").focus();
                    return false;
                }
            }
            if (jQuery("#cht-form #Phone").length && jQuery("#cht-form #Phone").val() != "") {
                InputVal = jQuery.trim(jQuery("#cht-form #Phone").val());
                if (check_for_number_chaty(InputVal, "Phone")) {
                    alert("<?php echo __("For channel Phone, please enter a valid phone number", CHT_OPT) ?>");
                    jQuery("#cht-form #Phone").focus();
                    return false;
                }
            }
            if (jQuery("#cht-form #Facebook_Messenger").length && jQuery("#cht-form #Facebook_Messenger").val() != "") {
                faceBookReg = /(?:http:\/\/)?(?:www\.)?m\.me\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-]*)/;
                InputVal = jQuery.trim(jQuery("#Facebook_Messenger").val());
                jQuery("#cht-form #Facebook_Messenger").val(InputVal);
                if (!faceBookReg.test(InputVal)) {
                    alert("Please make sure your Facebook page's URL looks like, \nhttps://m.me/YOURPAGE");
                    jQuery("#cht-form #Facebook_Messenger").focus();
                    return false;
                }
            }
            if (jQuery("#cht-form #SMS").length && jQuery("#cht-form #SMS").val() != "") {
                InputVal = jQuery.trim(jQuery("#cht-form #SMS").val());
                if (check_for_number_chaty(InputVal, "SMS")) {
                    alert("<?php echo __("For channel SMS, please enter a valid phone number", CHT_OPT) ?>");
                    jQuery("#cht-form #SMS").focus();
                    return false;
                }
            }
            if (jQuery("#cht-form #Viber").length && jQuery("#cht-form #Viber").val() != "") {
                InputVal = jQuery.trim(jQuery("#cht-form #Viber").val());
                if (check_for_number_chaty(InputVal, "Viber")) {
                    alert("<?php echo __("For channel Viber, please enter a valid phone number", CHT_OPT) ?>");
                    jQuery("#cht-form #Viber").focus();
                    return false;
                }
            }
        }); 
    });

    function check_for_number_chaty(phoneNumber, validationFor) {
        if(phoneNumber != "") {
            if(phoneNumber[0] == "+") {
                phoneNumber = phoneNumber.substr(1,phoneNumber.length)
            }
            if(validationFor == "Phone") {
                if (phoneNumber[0] == "*") {
                    phoneNumber = phoneNumber.substr(1, phoneNumber.length)
                }
            }
            if(isNaN(phoneNumber)) {
                return true;
            }
        }
        return false;
    }
</script>