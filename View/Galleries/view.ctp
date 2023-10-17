<?php 
	echo $this->Html->script(array('fancybox/jquery.mousewheel-3.0.4.pack','fancybox/jquery.fancybox-1.3.4.pack'));
	echo $this->Html->css('template1/fancybox/jquery.fancybox-1.3.4');
	$item_gallery = $a_gallery_c['Gallery'];
	$item_images = $a_gallery_c['GalleryImage'];
	//echo $this->Html->script(array('fancybox/jquery.mousewheel-3.0.4.pack','fancybox/jquery.fancybox-1.3.4.pack'));
	//echo $this->Html->css('cssall/fancybox/jquery.fancybox-1.3.4');
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("a[rel=group]").fancybox({
			'transitionIn'		: 'elastic',		<?php //none, elastic, fade?>
			'transitionOut'		: 'fade',
			'titlePosition' 	: 'over',
			'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
				return '<span id="fancybox-title-over">Ảnh ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
			}
		});
	});
</script> 
<article class="box_content read">
	<header class="title">
		<div class="title_right">
			<div class="title_center">
				<h1><span><?php echo $item_gallery['name'] ?></span></h1>
			</div> <!--  end .title_center -->
		</div> <!--  end .title_right -->
	</header> <!--  end .title -->
	<div class="detail">
	<?php echo $item_gallery['description']?>
	</div> <!--  end .detail -->
	<?php if(!empty($item_images)){?>
		<div class="photo-container views_show">
			<?php 
				$count_photos = count($item_images);
				//Kich thước ảnh thumbnail
				$full_size = $oneweb_media['size']['gallery'];
				$w = 275;
				$h = intval($w*$full_size[1]/$full_size[0]);
			?>
			<div id="list-photo" class="row">
				<?php for($i=0;$i<$count_photos;$i++){?>
					<div class="col-xs-12 col-sm-4 col-md-4 item_photo <?php if(($i+1)%3 == 0) echo ' last'?>">
					<?php 
					echo $this->Html->tag('a',$this->Html->image('images/galleries/'.$item_images[$i]['image'],array('title'=>$item_images[$i]['name'],'width'=>$w,'height'=>$h)),array('href'=>$this->Html->url('/img/images/galleries/'.$item_images[$i]['image']),'rel'=>'group','class'=>'zoom'));
					?>
					<p class="title"><?php echo $this->Text->truncate($item_images[$i]['name'],85,array('extra'=>false))?></p>
					</div>
				<?php }?>
			</div>
			<div class="clear"></div>
		</div>
		<?php }?>
	<div class="des">
		<?php //if(!empty($oneweb_web['social'])) echo $this->element('frontend/c_social')?>
		<?php if(!empty($a_other_galleries_c)){?>
		<section class="related">
			<span class="title"><?php echo __('Liên quan',true)?></span>
			<ul>
				<?php foreach($a_other_galleries_c as $val){
					$item_other_gallery = $val['Gallery'];
					$item_cate = $val['GalleryCategory']
				?>
				<li><?php echo $this->Html->link($item_other_gallery['name'],array('action'=>'index','lang'=>$lang,'slug0'=>$item_cate['slug'],'slug1'=>$item_other_gallery['slug'],'ext'=>'html'),array('title'=>$item_other_gallery['meta_title'],'rel'=>$item_other_gallery['rel'],'target'=>$item_other_gallery['target'],'class'=>''))?></li>
				<?php }?>
			</ul>
		</section><!--  end .related -->
		<?php }?>
		<div class="clear"></div>
	</div> <!--  end .des -->
			
</article> <!--  end .box_content -->