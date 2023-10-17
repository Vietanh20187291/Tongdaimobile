<!-- start videos/list_category.ctp -->
<article class="box_content">
	<header class="title">
		<div class="title_right">
			<div class="title_center">
				<h1><span><?php echo __('Video',true)?></span></h1>
			</div> <!--  end .title_center -->
		</div> <!--  end .title_right -->
	</header> <!--  end .title -->

	<div class="des">
		<?php if(!empty($a_video_configs_c['video_description'])){?>
		<div class="box_info_page">
			<div class="des">
				<?php echo $a_video_configs_c['video_description']?>
			</div> <!--  end .des -->

			<div class="top"></div>
			<div class="bottom"></div>
		</div> <!--  end .box_info_page -->
		<?php }?>

		<ul class="list_category2">
			<?php
			if(!empty($a_video_categories_h)){
			foreach($a_video_categories_h as $val){
				$item_cate = $val['VideoCategory'];
				$url = array('action'=>'index','lang'=>$lang,'slug0'=>$item_cate['slug']);

				$link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target'],'class'=>'name tooltip');
				if($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel'];

				$link_img_attr = array_merge($link_attr,array('escape'=>false));
				$link_img_attr['class'] = 'thumb tooltip';
			?>
			<li>
				<?php
					echo $this->Html->link($this->Html->image('video.png',array('alt'=>$item_cate['meta_title'])),$url,$link_img_attr);
					echo $this->Html->link($this->Text->truncate($item_cate['name'],28,array('exact'=>false)),$url,$link_attr);
				?>
			</li>
			<?php }}?>
		</ul> <!--  end .list_category2 -->
	</div> <!--  end .des -->

	<div class="top"></div>
	<div class="bottom"></div>
</article> <!--  end .box_content -->
<!-- end videos/list_category.ctp -->