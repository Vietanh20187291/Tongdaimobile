<!-- start c_social.ctp -->
<?php echo $this->Html->script('social')?>
<ul class="social">
	<?php if(!empty($a_site_info['facebook']) && $a_site_info['facebook_like']){?>
	<li class="facebook"><div class="fb-like" data-send="false" data-layout="button_count" data-width="110" data-show-faces="false" data-font="arial"></div></li>
	<?php }if(!empty($a_site_info['google']) && $a_site_info['google_like']){?>
	<li class="google"><div class="g-plusone" data-size="medium"></div></li>
	<?php }if(!empty($a_site_info['twitter']) && $a_site_info['twitter_like']){?>
	<li class="twitter"><a href="https://twitter.com/share" class="twitter-share-button" >Tweet</a></li>
	<?php }if(!empty($a_site_info['linkedin']) && $a_site_info['linkedin_like']){?>
	<li class="linkedin"><script type="IN/Share" data-counter="right"></script></li>
	<?php }?>
	<li><a class="addthis_counter addthis_pill_style"></a></li>
</ul>
<!-- end c_social.ctp -->