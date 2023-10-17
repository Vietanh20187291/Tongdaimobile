<article class="box_content sitemap">
	<header class="title">
		<div class="title_right">
			<div class="title_center">
				<h1><span><?php echo __('Sơ đồ',true)?></span></h1>
			</div> <!--  end .title_center -->
		</div> <!--  end .title_right -->
	</header> <!--  end .title -->
	
	<div class="des">
		<ul>
			<li><?php echo $this->Html->link($this->Html->tag('span',__('Trang chủ',true)),array('controller'=>'pages','action'=>'home','lang'=>$lang),array('title'=>__('Trang chủ',true),'escape'=>false))?></li>
			<?php if(!empty($a_information_nav)) echo $this->OnewebVn->linkInformation($a_information_nav,array(1,2,3,4,5,6,7,8,9,10),$sub=true,$span=false)?>
			<?php //SẢN PHẨM
			if(!empty($a_product_categories_s)){
				if(count($a_product_categories_s)==1){	//Chỉ có 1 danh mục gốc	
					$item_product_category = $a_product_categories_s[0]['ProductCategory'];
					$a_attr_pro = array('title'=>$item_product_category['meta_title'],'target'=>$item_product_category['target'],'escape'=>false);
					if($item_product_category['rel']!='dofollow') $a_attr_pro['rel'] = $item_product_category['rel'];
			?>
				<li>
					<?php echo $this->Html->link($this->Html->tag('span',$item_product_category['name']),array('controller'=>'products','action'=>'index','lang'=>$lang,'slug0'=>$item_product_category['slug']),$a_attr_pro)?>
					<?php echo $this->OnewebVn->productCategory($a_product_categories_s[0]['children'],0)?>
				</li>
			<?php }else{	//Có nhiều danh mục gốc?>
			<li><?php echo $this->Html->link($this->Html->tag('span',__('Sản phẩm',true)),'javascript:;',array('title'=>__('Sản phẩm',true),'escape'=>false))?>
				<?php echo $this->OnewebVn->productCategory($a_product_categories_s,0)?>
			</li>
			<?php }}?>
			
			<?php 
			if(!empty($a_post_categories_s)) //BÀI VIẾT
				echo $this->OnewebVn->postCategoryMenu($a_post_categories_s,array(1,2,3,4,5,6,7,8,9,10),true);
			?>
			
			<?php if(!empty($oneweb_media['document']['enable'])){ //DOCUMENT ?>
			<li>
				<?php echo $this->Html->link($this->Html->tag('span',__('Tài liệu',true)),array('controller'=>'documents','action'=>'index','lang'=>$lang),array('title'=>__('Tài liêu',true),'escape'=>false))?>
				<?php if(!empty($a_document_categories_c)){?>
				<ul>
					<?php foreach($a_document_categories_c as $val){
						$item_document = $val['DocumentCategory'];
						
						$link_document_attr = array('title'=>$item_document['meta_title'],'target'=>$item_document['target']);
						if($item_document['rel']!='dofollow') $link_document_attr['rel'] = $item_document['rel']; 
					?>
					<li><?php echo $this->Html->link($item_document['name'],array('controller'=>'documents','action'=>'view','lang'=>$lang,'slug_cate'=>$item_document['slug']),$link_document_attr)?></li>
					<?php }?>
				</ul>
				<?php }?>
			</li>
			<?php }?>
			
			<?php if(!empty($oneweb_faq['enable'])){	//FAQs ?>
			<li><?php echo $this->Html->link($this->Html->tag('span',__('Hỏi đáp')),array('controller'=>'faqs','action'=>'view','lang'=>$lang),array('title'=>__('Hỏi đáp',true),'escape'=>false))?><ul></ul></li>
			<?php }?>
			
			<?php if(!empty($oneweb_media['video']['enable'])){  //VIDEO ?>
			<li><?php echo $this->Html->link($this->Html->tag('span',__('Video',true)),array('controller'=>'videos','action'=>'index','lang'=>$lang),array('title'=>__('Video',true),'escape'=>false))?>
				<?php if(!empty($a_video_categories_h)){?>
				<ul>
					<?php foreach($a_video_categories_h as $val){
						$item_video_category = $val['VideoCategory'];
						
						$link_video_attr = array('title'=>$item_video_category['meta_title'],'target'=>$item_video_category['target']);
						if($item_video_category['rel']!='dofollow') $link_video_attr['rel'] = $item_video_category['rel']; 
					?>
					<li><?php echo $this->Html->link($item_video_category['name'],array('controller'=>'videos','action'=>'index','lang'=>$lang,'slug0'=>$item_video_category['slug']),$link_video_attr)?></li>
					<?php }?>
				</ul>
				<?php }?>
			</li>
			<?php }?>	
			
			<?php if(!empty($oneweb_media['gallery']['enable'])){  //Gallery ?>
			<li><?php echo $this->Html->link($this->Html->tag('span',__('Hình ảnh',true)),array('controller'=>'galleries','action'=>'index','lang'=>$lang),array('title'=>__('Hình ảnh',true),'escape'=>false))?>
				<?php if(!empty($a_gallery_categories_h)){?>
				<ul>
					<?php foreach($a_gallery_categories_h as $val){
						$item_gallery_category = $val['GalleryCategory'];
						
						$link_gallery_attr = array('title'=>$item_gallery_category['meta_title'],'target'=>$item_gallery_category['target']);
						if($item_gallery_category['rel']!='dofollow') $link_gallery_attr['rel'] = $item_gallery_category['rel']; 
					?>
					<li><?php echo $this->Html->link($item_gallery_category['name'],array('controller'=>'galleries','action'=>'index','lang'=>$lang,'slug0'=>$item_gallery_category['slug']),$link_gallery_attr)?></li>
					<?php }?>
				</ul>
				<?php }?>
			</li>
			<?php }?>
			
			<?php if(!empty($oneweb_contact['enable'])){?>
			<li><?php echo $this->Html->link($this->Html->tag('span',__('Liên hệ',true)),array('controller'=>'contacts','action'=>'index','lang'=>$lang,'ext'=>'html'),array('title'=>__('Liên hệ',true),'rel'=>'nofollow','escape'=>false))?><ul></ul></li>
			<?php }?>
		</ul>
	</div> <!--  end .des -->
			
	<div class="top"></div>
	<div class="bottom"></div>
</article> <!--  end .box_content -->