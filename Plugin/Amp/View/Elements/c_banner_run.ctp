<!-- start c_product_run.ctp -->
<?php if (!empty($data)){ ?>
	<amp-carousel height="150"
  layout="fixed-height"
  class="carousel"
  type="carousel"
  autoplay
  delay="2000">
	<?php
			foreach ($data as $val) {
				$item = $val['Banner'];
	                if (!empty($item)){
	                    $link_attr = array('title'=>$item['name'],'target'=>$item['target']);
	                    $link_img_attr = array_merge($link_attr, array('escape' => false));
					    if($item['rel']!='dofollow') $link_attr['rel'] = $item['rel'];
					    if(!empty($item['link']))
					    	$link = $item['link'];
					    else
					    	$link = '#';
			 ?>
			
						<?php
						if(!empty($item['image'])){
							echo $this->Html->link($this->HtmlAmp->thumbAmp('banners/'.$item['image'],array('width'=>$oneweb_banner['size'][$position][0],'height'=>$oneweb_banner['size'][$position][1],'layout'=>'fixed')),$link,$link_img_attr);
						}else{
							echo $this->Html->link($this->HtmlAmp->amp_image('no_maker.jpg',array('layout'=>'fixed')),$link,$link_img_attr);
						}
						?> <?php //echo $this->Html->link($item['name'],$link,$link_attr)?>
						
			<!--  end .box_post -->
			<?php }}?>
</amp-carousel>
<!-- end .box-other -->
<?php }?>
<!-- end c_product_run.ctp -->
