<article class="box_content">
	<header class="title">
		<div class="title_right">
			<div class="title_center">
				<h1><span><?php echo __('Hình ảnh',true)?></span></h1>
			</div> <!--  end .title_center -->
		</div> <!--  end .title_right -->
	</header> <!--  end .title -->
	
	<div class="des">
		<?php if(!empty($a_gallery_configs_c['gallery_description'])){?>
		<div class="box_info_page">
			<div class="des">
				<?php echo $a_gallery_configs_c['gallery_description']?>
			</div> <!--  end .des -->
				
			<div class="top"></div>
			<div class="bottom"></div>
		</div> <!--  end .box_info_page -->
		<?php }?>
		
		<ul class="list_category2">
			<?php 
			if(!empty($a_gallery_categories_h)){
			foreach($a_gallery_categories_h as $key=>$val){
				$item_cate = $val['GalleryCategory'];
				$url = array('controller'=>'galleries','action'=>'index','lang'=>$lang,'slug0'=>$item_cate['slug']);
				
				$link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target'],'class'=>'name');
				if($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel']; 
				
				$link_img_attr = array_merge($link_attr,array('escape'=>false));
				$link_img_attr['class'] = 'thumb';
			?>
			<li class="<?php if(($key+1)%3==0) echo 'last'?>">
				<?php 
					//Kich thước ảnh thumbnail
					$full_size = $oneweb_product['size']['product'];
					$w = 228;
					$h = 150;
					if(!empty($item_cate['image'])) $img = 'images/galleries/'.$item_cate['image'];
					
					echo $this->Html->link($this->Html->image($img,array('width'=>$w,'height'=>$h,'alt'=>$item_cate['meta_title'])),$url,$link_img_attr);
					echo $this->Html->link($this->Text->truncate($item_cate['name'],80,array('extra'=>false)),$url,$link_attr);
				?>
			</li>
			<?php }}?>
		</ul> <!--  end .list_category2 -->
	</div> <!--  end .des -->
			
	<div class="top"></div>
	<div class="bottom"></div>
</article> <!--  end .box_content -->