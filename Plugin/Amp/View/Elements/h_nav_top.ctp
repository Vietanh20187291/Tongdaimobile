<!-- start h_nav_top.ctp -->
<div id="header_top" <?php if(!empty($a_banners_pos8)) echo 'class="has-adv-bottom"'; ?>>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<ul class="left-menu">
					<!-- Giới thiệu -->
					<li>
						<?php if(!empty($a_information_nav)) echo $this->HtmlAmp->linkInformationTop($a_information_nav,array(1),$sub=true,$span=true)?>
					</li>

					<!-- Facebook -->
					<?php
						if ( ! empty($a_site_info['facebook']))
						{
					?>
					<li>
						<?php
								echo $this->Html->link(__('Facebook'), $a_site_info['facebook'],array('title'=>'Facebook','rel'=>'nofollow','class'=>'','target'=>'_blank','escape'=>false));
						?>
					</li>
					<?php
						}
					?>

					<!-- Liên hệ -->
					<?php
						$show_lienhe = false;
						if ($show_lienhe && ! empty($oneweb_contact['enable']))
						{
					?>
					<li>
						<?php
								echo $this->Html->link($this->Html->tag('span',__('Liên hệ',true)),array('plugin'=>false, 'controller'=>'contacts','action'=>'index','lang'=>$lang,'ext'=>'html'),array('title'=>__('Liên hệ',true),'class'=>'','rel'=>'nofollow','escape'=>false));
						?>
					</li>
					<?php
						}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
