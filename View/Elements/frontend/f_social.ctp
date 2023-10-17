<div class="social">
	<ul class="link">
		<?php if(!empty($a_site_info['facebook'])){?>
		<li><?php echo $this->Html->link('<span class="icon fb"><span>',$a_site_info['facebook'],array('title'=>'Facebook','rel'=>'nofollow','target'=>'_blank','escape'=>false))?></li>
		<?php }if(!empty($a_site_info['google'])){?>
		<li><?php echo $this->Html->link('<span class="icon gg"><span>',$a_site_info['google'],array('title'=>'Google Plus','rel'=>'nofollow','target'=>'_blank','escape'=>false))?></li>
		<?php }if(!empty($a_site_info['twitter'])){?>
		<li><?php echo $this->Html->link('<span class="icon tw"><span>',$a_site_info['twitter'],array('title'=>'Twitter','rel'=>'nofollow','target'=>'_blank','escape'=>false))?></li>
		<?php }if(!empty($a_site_info['linkedin'])){?>
		<li><?php echo $this->Html->link('<span class="icon lnk"><span>',$a_site_info['linkedin'],array('title'=>'Linked In','rel'=>'nofollow','target'=>'_blank','escape'=>false))?></li>
		<?php }if(!empty($a_site_info['blogspot'])){?>
		<li><?php echo $this->Html->link('<span class="icon blg"><span>',$a_site_info['blogspot'],array('title'=>'BlogSpot','rel'=>'nofollow','target'=>'_blank','escape'=>false))?></li>
		<?php }if(!empty($a_site_info['youtube'])){?>
		<li><?php echo $this->Html->link('<span class="icon youtube"><span>',$a_site_info['youtube'],array('title'=>'YouTube','rel'=>'nofollow','target'=>'_blank','escape'=>false))?></li>
		<?php }
		if(!empty($a_site_info['rss'])){?>
		<li><?php echo $this->Html->link('<span class="icon rss"><span>',$a_site_info['rss'],array('title'=>'RSS','rel'=>'nofollow','target'=>'_blank','escape'=>false))?></li>
		<?php }?>
	</ul> <!--  end .link -->
</div> <!--  end .social -->