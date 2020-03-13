<style>
    #positionPro {
        <?php if (get_option('cht_position') === 'custom') : ?>
            display: block;
        <?php else : ?>
            display: none;
        <?php endif; ?>
    }

  .update-nag{
  	display: none;
  }
  #setting-error-settings_updated{
  	margin: 0 auto;
    background: #51F2B8;
    left: 37px;
    border-left-color: #51F2B8;
  }
  .none_pro #upgrade-modal{
    display: block !important;
    position: fixed !important;
    z-index: 1000013 !important;
    margin-left: -257.5px !important;
    margin-top: 0px !important;
  }
 .none_pro .easy-modal-close{
  display: none;
 }
</style>
