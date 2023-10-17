<div id="widget_contact">
  <?php /* ?>
  <div class="collapse navbar-collapse" id="widgetSupport">
    <?php if(!empty($config->skype)) { ?>
      <a href="skype:<?= $config->skype ?>?chat" rel="nofollow" class="fa fa-skype text-white icon-skype mb-2" title="Skype chat with <?= $config->skype ?>"></a>
    <?php } ?>

    <?php if(!empty($config->whatsapp)) { ?>
      <a href="https://api.whatsapp.com/send?phone=<?= preg_replace('/\s|\-/', '', $config->whatsapp) ?>" title="<?= $config->whatsapp ?>" target="_blank" class="icon icon-whatsapp mb-2">.</a>
    <?php } ?>

    <?php if(!empty($config->wechat)) { ?>
      <a href="tel:<?= preg_replace('/\s|\-/', '', $config->wechat) ?>" title="<?= $config->wechat ?>" class="icon icon-wechat mb-2">.</a>
    <?php } ?>
  </div>
  <?php if(!empty($config->skype) || !empty($config->whatsapp) || !empty($config->wechat)) { ?>
  <a class="support collapsed mb-2" href="javascript:;" data-toggle="collapse" data-target="#widgetSupport" aria-controls="widgetSupport" aria-expanded="false">
    <i class="icon icon-support"></i>
  </a>
  <?php } */?>
  <?php if(!empty($a_configs_h['fb_chat'])) { ?>
    <a href="<?= $a_configs_h['fb_chat'] ?>" target='_blank' class="pull-left mb-2" target="_blank"><img src="https://cdn.autoads.asia/content/images/widget_icon_messenger.svg" alt=""></a>
  <?php } ?>

  <?php if(!empty($a_configs_h['zalo'])) { ?>
    <a href="https://zalo.me/<?= preg_replace('/\s|\-/', '', $a_configs_h['zalo']) ?>" title="<?= $a_configs_h['zalo'] ?>" class="icon icon-zalo mb-2" target="_blank">.</a>
  <?php } ?>

  <?php if(!empty($a_configs_h['hotline'])) { ?>
    <a href="tel:<?= preg_replace('/\s|\-/', '', $a_configs_h['hotline']) ?>" onclick="gtag_report_conversion('tel:<?= preg_replace('/\s|\-/', '', $a_configs_h['hotline']) ?>')" title="Hotline 24/7" class="icon icon-hotline mb-2">.</a>
  <?php } ?>

</div>