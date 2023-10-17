<aside class="box support">
	<span class="title"><?php echo __('Hỗ trợ trực tuyến',true)?></span>
	<ul>
		<?php if(!empty($oneweb_support['livechat'])){?>
		<li>
			<a href="<?php echo $this->Html->url("/livechat/client.php?locale=$lang&style=urlvn")?>" target="_blank" onclick="if(navigator.userAgent.toLowerCase().indexOf('opera') != -1 &amp;&amp; window.event.preventDefault) window.event.preventDefault();this.newWindow = window.open('<?php echo $this->Html->url("/livechat/client.php?locale=$lang&style=urlvn&url='+escape(document.location.href)+'&referrer='+escape(document.referrer), 'webim', 'toolbar=0,scrollbars=0,location=0,status=1,menubar=0,width=640,height=480,resizable=1")?>');this.newWindow.focus();this.newWindow.opener=window;return false;">
				<img src="<?php echo $this->Html->url("/livechat/button.php?i=webim&lang=$lang")?>" width="163" height="61" alt="LiveChat"/>
			</a>
		</li>
		<?php }foreach ($a_support_s as $val){
			$item_support = $val['Support']	;
		?>
		<li>
			<p class="name">
				<?php 
					echo $item_support['name'];
					if(!empty($item_support['phone'])) echo $this->Html->tag('span',' ('.$item_support['phone'].')');
				?>
			</p>
			<?php if(!empty($item_support['yahoo'])){?>
			<p class="yahoo">
				<a title="<?php echo __('Chat yahoo với',true).' '.$item_support['name'].' ('.$item_support['yahoo'].')'?>" href="ymsgr:sendIM?<?php echo $item_support['yahoo']?>" rel="nofollow">
					<img alt="Yahoo" src="http://opi.yahoo.com/online?u=<?php echo $item_support['yahoo']?>&amp;m=g&amp;t=2;img/&amp;l=us">
				</a>
			</p>
			<?php } if(!empty($item_support['skype'])){?>
			<p class="skype">
				<a href="skype:<?php echo $item_support['skype'] ?>?call" title="<?php echo __('Chat skype với',true).' '.$item_support['name'].' ('.$item_support['skype'].')'?>" onclick="return skypeCheck();" rel="nofollow"><?php echo $this->Html->image('skype64.png',array('alt'=>'skype icon'))?></a>
			</p>
			<?php }?>
		</li>
		<?php }?>
	</ul>
</aside> <!--  end .box -->