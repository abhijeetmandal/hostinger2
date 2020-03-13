<?php
$social = $this->int_arr();
$cht_active = get_option("cht_active");
if(!empty($social) && $cht_active) {
//  $social = json_decode($social, true);
    if (array_key_exists("facebook_messenger", $social)) {
        ?>
        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://connect.facebook.net/locale/sdk.js#xfbml=1&version=v3.0';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
    <?php }
}?>

<?php $bg_color = $this->get_current_color();?>
<?php $len = count($this->int_arr());?>
<?php $cta = $this->getCallToAction();
?>

<?php
$positionSide = get_option('positionSide');
$cht_bottom_spacing = get_option('cht_bottom_spacing');
$cht_side_spacing = get_option('cht_side_spacing');
$cht_widget_size = get_option('cht_widget_size');
$cta = str_replace("'","&#39;",$cta);
$cta = str_replace('"',"&#34;",$cta);

$settings = array();
$settings['isPRO'] = 0;
$settings['position'] = get_option('cht_position');
$settings['social'] = $this->int_arr();
$settings['pos_side'] = empty($positionSide) ? 'right' : $positionSide;
$settings['bot'] = ($cht_bottom_spacing) ? $cht_bottom_spacing : '25';
$settings['side'] = ($cht_side_spacing) ? $cht_side_spacing : '25';
$settings['device'] = $this->device();
$settings['color'] = ($bg_color) ? $bg_color : '#A886CD';;
$settings['widget_size'] = ($cht_widget_size) ? $cht_widget_size: '54';
$settings['widget_type'] = get_option('widget_icon');
$settings['widget_img'] = $this->getCustomWidgetImg();
$settings['cta'] = addslashes($cta);
$settings['link_active'] = get_option('cht_credit');
$settings['active'] = ($cht_active && $len > 0 && $len < 3) ? "true" : "false";
$data = array();
$data['object_settings'] = $settings;
if($len > 0 && $len < 3) {
    ?>
    <script>
        var chaty_settings = '<?php echo json_encode($data) ?>';
    </script>
    <script src="<?php echo CHT_PLUGIN_URL ?>assets/js/cht-front-script.js?<?php echo rand(); ?>">
    </script>
<?php
}
?>
