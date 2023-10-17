<?php
	$controller = $this->params['controller'];
	$action 	= $this->params['action'];

?>

<?php if($controller=='pages'){?>
	<div id="nav_column_left" class="nav">
		<ul>
			<?php if ($admin['role'] == 'admin') { ?>
				<?php if(!empty($oneweb_product['enable'])){?>

				<?php } ?>
			<?php } ?>
			<?php if(!empty($oneweb_post['enable'])){?>
			<li>
				<?php
					echo $this->Html->link(__('Bài viết',true),array('plugin'=>false, 'controller'=>'posts','action'=>'index'),array('title'=>__('Bài viết',true), 'class'=>'tooltip'));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'posts','action'=>'add'),array('title'=>__('Thêm bài viết',true), 'class'=>'act add tooltip', 'escape'=>false));
				?>
			</li>
			<?php } ?>
			<?php if ($admin['role'] == 'admin') { ?>
				<?php if(!empty($oneweb_product['enable']) && !empty($oneweb_product['order'])){?>
				<?php } if(!empty($oneweb_contact['enable'])){?>
				<?php } ?>

			<?php }?>
		</ul>
	</div> <!--  end #nav_column_left -->
<?php }elseif($controller=='posts' || $controller=='post_categories'){

	//**** BÀI VIẾT *****/
	switch ($controller){
		case 'posts':
			$url_add = array('plugin'=>false, 'controller'=>'posts','action'=>'add');
			$title_add = __('Thêm bài viết',true);
			break;
		case 'post_categories':
			$url_add = array('plugin'=>false, 'controller'=>'post_categories','action'=>'add');
			$title_add = __('Thêm danh mục bài viết',true);
			break;
	}

	if(!empty($url_add)) echo $this->Html->link(__('Thêm',true),$url_add,array('title'=>$title_add, 'class'=>'add tooltip'));
	else echo $this->Html->link(__('Thêm',true),'javascript:;',array('title'=>'', 'class'=>'not_add'));
?>
	<div id="nav_column_left" class="nav">
		<ul>
			<li<?php if($controller=='posts') echo ' class="current"'?>><?php echo $this->Html->link(__('Bài viết',true),array('plugin'=>false, 'controller'=>'posts','action'=>'index'),array('title'=>__('Danh sách bài viết',true)))?>
				<?php if(!empty($oneweb_post['display'])){?>
				<ul>
					<?php foreach($oneweb_post['display'] as $key=>$val){?>
					<li><?php echo $this->Html->link($val,array('plugin'=>false, 'controller'=>'posts','action'=>'index','?'=>array('position'=>$key)),array('title'=>$val))?></li>
					<?php }?>
				</ul>
				<?php }?>
			</li>
			<li<?php if($controller=='post_categories') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Danh mục',true),array('plugin'=>false, 'controller'=>'post_categories','action'=>'index'),array('title'=>__('Danh mục bài viết',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'post_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true),'class'=>'add tooltip'));
				?>
			</li>
		</ul>
	</div> <!--  end #nav_column_left -->
<?php
}elseif($controller=='information'){

	//**** Information *****/

	echo $this->Html->link('Thêm',array('plugin'=>false, 'controller'=>'information','action'=>'add'),array('title'=>__('Thêm trang thông tin',true), 'class'=>'add tooltip'));
?>
	<div id="nav_column_left" class="nav">
		<ul>
			<li<?php if($controller=='information') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Thông tin',true),array('plugin'=>false, 'controller'=>'information','action'=>'index'),array('title'=>__('Thông tin',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'information','action'=>'add'),array('title'=>__('Thêm trang thông tin',true),'class'=>'add tooltip'));
				?>
			</li>
		</ul>
	</div> <!--  end #nav_column_left -->


<?php }elseif($controller=='advertisements'){

	//**** Quảng cáo *****/

	echo $this->Html->link('Thêm',array('plugin'=>false, 'controller'=>'advertisements','action'=>'add'),array('title'=>__('Thêm quảng cáo',true), 'class'=>'add tooltip'));
?>
	<div id="nav_column_left" class="nav">
		<ul>
			<li<?php if($controller=='advertisements') echo ' class="current"'?>>
				<?php
					echo $this->Html->link('Quảng cáo',array('plugin'=>false, 'controller'=>'advertisements','action'=>'index'),array('title'=>__('Quảng cáo',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'advertisements','action'=>'add'),array('title'=>__('Thêm quảng cáo',true),'class'=>'add tooltip'));
				?>
			</li>
		</ul>
	</div> <!--  end #nav_column_left -->


<?php }elseif(in_array($controller,array('videos','video_categories','galleries','gallery_categories','documents','document_categories','banners'))){

	//**** Media *****/
	switch ($controller){
		case 'galleries':
			$url_add = array('plugin'=>false, 'controller'=>'galleries','action'=>'add');
			$title_add = __('Thêm hình ảnh',true);
			break;
		case 'gallery_categories':
			$url_add = array('plugin'=>false, 'controller'=>'gallery_categories','action'=>'add');
			$title_add = __('Thêm danh mục',true);
			break;
		case 'videos':
			$url_add = array('plugin'=>false, 'controller'=>'videos','action'=>'add');
			$title_add = __('Thêm video',true);
			break;
		case 'video_categories':
			$url_add = array('plugin'=>false, 'controller'=>'video_categories','action'=>'add');
			$title_add = __('Thêm danh mục',true);
			break;
		case 'documents':
			$url_add = array('plugin'=>false, 'controller'=>'documents','action'=>'add');
			$title_add = __('Thêm tài liệu',true);
			break;
		case 'document_categories':
			$url_add = array('plugin'=>false, 'controller'=>'document_categories','action'=>'add');
			$title_add = __('Thêm danh mục',true);
			break;
		case 'banners':
			$url_add = array('plugin'=>false, 'controller'=>'banners','action'=>'add');
			$title_add = __('Thêm banner',true);
			break;
	}

	if(!empty($url_add)) echo $this->Html->link('Thêm',$url_add,array('title'=>$title_add, 'class'=>'add tooltip'));
	else echo $this->Html->link('Thêm','javascript:',array('title'=>'', 'class'=>'not_add'));
?>
	<div id="nav_column_left" class="nav">
		<ul>
			<?php if($controller=='galleries' || $controller=='gallery_categories'){?>
			<li<?php if($controller=='galleries') echo ' class="current"'?>>
				<?php echo $this->Html->link(__('Hình ảnh',true),array('plugin'=>false, 'controller'=>'galleries','action'=>'index'),array('title'=>__('Hình ảnh',true)))?>
				<?php if(!empty($oneweb_media['gallery']['display'])){?>
				<ul>
					<?php foreach($oneweb_media['gallery']['display'] as $key=>$val){?>
					<li><?php echo $this->Html->link(__($val,true),array('plugin'=>false, 'controller'=>'galleries','action'=>'index','?'=>array('position'=>$key)),array('title'=>__($val,true)))?></li>
					<?php }?>
				</ul>
				<?php }?>
			</li>
			<li<?php if($controller=='gallery_categories') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Danh mục',true),array('plugin'=>false, 'controller'=>'gallery_categories','action'=>'index'),array('title'=>__('Danh mục',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'gallery_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true),'class'=>'add tooltip'));
				?>
			</li>
			<?php }elseif($controller=='videos' || $controller=='video_categories'){?>
			<li<?php if($controller=='videos') echo ' class="current"'?>>
				<?php echo $this->Html->link('Video',array('plugin'=>false, 'controller'=>'videos','action'=>'index'),array('title'=>'Video'))?>
				<?php if(!empty($oneweb_media['video']['display'])){?>
				<ul>
					<?php foreach($oneweb_media['video']['display'] as $key=>$val){?>
					<li><?php echo $this->Html->link(__($val,true),array('plugin'=>false, 'controller'=>'videos','action'=>'index','?'=>array('position'=>$key)),array('title'=>__($val,true)))?></li>
					<?php }?>
				</ul>
				<?php }?>
			</li>
			<li<?php if($controller=='video_categories') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Danh mục',true),array('plugin'=>false, 'controller'=>'video_categories','action'=>'index'),array('title'=>__('Danh mục',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'video_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true),'class'=>'add tooltip'));
				?>
			</li>
			<?php }elseif($controller=='documents' || $controller=='document_categories'){?>
			<li<?php if($controller=='documents') echo ' class="current"'?>>
				<?php echo $this->Html->link(__('Tài liệu',true),array('plugin'=>false, 'controller'=>'documents','action'=>'index'),array('title'=>__('Tài liệu',true)))?>
				<?php if(!empty($oneweb_media['document']['display'])){?>
				<ul>
					<?php foreach($oneweb_media['document']['display'] as $key=>$val){?>
					<li><?php echo $this->Html->link(__($val,true),array('plugin'=>false, 'controller'=>'documents','action'=>'index','?'=>array('position'=>$key)),array('title'=>__($val,true)))?></li>
					<?php }?>
				</ul>
				<?php }?>
			</li>
			<li<?php if($controller=='document_categories') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Danh mục',true),array('plugin'=>false, 'controller'=>'document_categories','action'=>'index'),array('title'=>__('Danh mục',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'document_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true),'class'=>'add tooltip'));
				?>
			</li>
			<?php }elseif($controller=='banners'){?>
			<li class="current">
				<?php echo $this->Html->link('Banner',array('plugin'=>false, 'controller'=>'banners','action'=>'index'),array('title'=>'Banner'));?>
				<?php if(!empty($oneweb_banner['display'])){?>
				<ul>
					<?php foreach($oneweb_banner['display'] as $key=>$val){?>
					<li><?php echo $this->Html->link(__($val,true),array('plugin'=>false, 'controller'=>'banners','action'=>'index','?'=>array('position'=>$key)),array('title'=>__($val,true)))?></li>
					<?php }?>
				</ul>
				<?php }?>
			</li>
			<?php }?>
		</ul>

		<p class="line"></p>

		<ul>
			<?php if(!empty($oneweb_media['gallery']['enable'])){?>
			<li<?php if($controller=='galleries' || $controller=='gallery_categories') echo ' class="current"'?>>
				<?php echo $this->Html->link(__('Hình ảnh',true),array('plugin'=>false, 'controller'=>'galleries','action'=>'index'),array('title'=>__('Hình ảnh',true)));?>
			</li>
			<?php }if(!empty($oneweb_media['video']['enable'])){?>
			<li<?php if($controller=='videos' || $controller=='video_categories') echo ' class="current"'?>>
				<?php echo $this->Html->link(__('Video',true),array('plugin'=>false, 'controller'=>'videos','action'=>'index'),array('title'=>__('Video',true)));?>
			</li>
			<?php }if(!empty($oneweb_media['document']['enable'])){?>
			<li<?php if($controller=='documents' || $controller=='document_categories') echo ' class="current"'?>>
				<?php echo $this->Html->link(__('Tài liệu',true),array('plugin'=>false, 'controller'=>'documents','action'=>'index'),array('title'=>__('Tài liệu',true)));?>
			</li>
			<?php }if(!empty($oneweb_banner['enable'])){?>
			<li<?php if($controller=='banners') echo ' class="current"'?>>
				<?php echo $this->Html->link(__('Banner',true),array('plugin'=>false, 'controller'=>'banners','action'=>'index'),array('title'=>__('Banner',true)));?>
			</li>
			<?php }?>
		</ul>
	</div> <!--  end #nav_column_left -->

<?php }elseif($controller=='orders'  || $controller=='order_categories'|| $controller=='contacts' || $controller=='contact_categories'){

	//**** Đơn hàng - Liên hệ *****/
	switch ($controller){
		case 'order_categories':
			$url_add = array('plugin'=>false, 'controller'=>'order_categories','action'=>'add');
			$title_add = __('Thêm nhóm đơn hàng',true);
			break;
		case 'contact_categories':
			$url_add = array('plugin'=>false, 'controller'=>'contact_categories','action'=>'add');
			$title_add = __('Thêm nhóm liên hệ',true);
			break;
	}

	if(!empty($url_add)) echo $this->Html->link(__('Thêm',true),$url_add,array('title'=>$title_add, 'class'=>'add tooltip'));
	else echo $this->Html->link(__('Thêm',true),'javascript:',array('title'=>'', 'class'=>'not_add'));
?>
	<div id="nav_column_left" class="nav">
		<ul>
			<?php if($controller=='orders' || $controller=='order_categories'){?>
			<li<?php if($controller=='orders') echo ' class="current"'?>>
				<?php echo $this->Html->link(__('Danh sách đơn hàng',true),array('plugin'=>false, 'controller'=>'orders','action'=>'index'),array('title'=>__('Danh sách đơn hàng',true)))?>
				<?php if(!empty($a_list_categories_s)){?>
				<ul>
					<?php foreach($a_list_categories_s as $key=>$val){?>
					<li><?php echo $this->Html->link(__($val,true),array('plugin'=>false, 'controller'=>'orders','action'=>'index','?'=>array('category_id'=>$key)),array('title'=>__($val,true)))?></li>
					<?php }?>
					<li><?php echo $this->Html->link(__('Tất cả',true).' ('.$total_order_s.')',array('plugin'=>false, 'controller'=>'orders','action'=>'index'),array('title'=>__('Tất cả',true)))?></li>
				</ul>
				<?php }?>
			</li>
			<li<?php if($controller=='order_categories') echo ' class="current"'?>>
				<?php echo $this->Html->link(__('Nhóm đơn hàng',true),array('plugin'=>false, 'controller'=>'order_categories','action'=>'index'),array('title'=>__('Nhóm đơn hàng',true))) ?>
			</li>
			<?php }elseif($controller=='contacts' || $controller=='contact_categories'){?>
			<li<?php if($controller=='contacts') echo ' class="current"'?>>
				<?php echo $this->Html->link(__('Danh sách liên hệ',true),array('plugin'=>false, 'controller'=>'contacts','action'=>'index'),array('title'=>__('Danh sách liên hệ',true)))?>
				<?php if(!empty($a_list_categories_s)){?>
				<ul>
					<?php foreach($a_list_categories_s as $key=>$val){?>
					<li><?php echo $this->Html->link(__($val,true),array('plugin'=>false, 'controller'=>'contacts','action'=>'index','?'=>array('category_id'=>$key)),array('title'=>__($val,true)))?></li>
					<?php }?>
					<li><?php echo $this->Html->link(__('Tất cả',true).' ('.$total_contact_s.')',array('plugin'=>false, 'controller'=>'contacts','action'=>'index'),array('title'=>__('Tất cả',true)))?></li>
				</ul>
				<?php }?>
			</li>
			<li<?php if($controller=='contact_categories') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Nhóm liên hệ',true),array('plugin'=>false, 'controller'=>'contact_categories','action'=>'index'),array('title'=>__('Nhóm liên hệ',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'contact_categories','action'=>'add'),array('title'=>__('Thêm nhóm liên hệ',true),'class'=>'add tooltip'));
				?>
			</li>
			<?php }?>
		</ul>

		<p class="line"></p>

		<ul>
			<?php if(!empty($oneweb_product['enable']) && !empty($oneweb_product['order'])){?>
			<li<?php if($controller=='orders' || $controller=='order_categories') echo ' class="current"'?>>
				<?php echo $this->Html->link(__('Đơn hàng',true),array('plugin'=>false, 'controller'=>'orders','action'=>'index'),array('title'=>__('Đơn hàng',true)));?>
			</li>
			<?php } if(!empty($oneweb_contact['enable'])){?>
			<li<?php if($controller=='contacts' || $controller=='contact_categories') echo ' class="current"'?>>
				<?php echo $this->Html->link(__('Liên hệ',true),array('plugin'=>false, 'controller'=>'contacts','action'=>'index'),array('title'=>__('Liên hệ',true)));?>
			</li>
			<?php }?>
		</ul>
	</div> <!--  end #nav_column_left -->

<?php }elseif($controller=='faqs' || $controller=='faq_categories' || $controller=='currencies' || $controller=='tags' || $controller=='newsletters' || $controller=='supports' || $controller=='sitemaps' || $controller=='users'){
		//Muc khac

	switch ($controller){
		case 'faqs':
			$url_add = array('plugin'=>false, 'controller'=>'faqs','action'=>'add');
			$title_add = __('Thêm FAQ',true);
			break;
		case 'faq_categories':
			$url_add = array('plugin'=>false, 'controller'=>'faq_categories','action'=>'add');
			$title_add = __('Thêm nhóm',true);
			break;
		case 'currencies':
			$url_add = array('plugin'=>false, 'controller'=>'currencies','action'=>'add');
			$title_add = __('Thêm đơn vị tiền',true);
			break;
		case 'supports':
			$url_add = array('plugin'=>false, 'controller'=>'supports','action'=>'add');
			$title_add = __('Thêm hỗ trợ',true);
			break;
		case 'users':
			$url_add = array('plugin'=>false, 'controller'=>'users','action'=>'add');
			$title_add = __('Thêm tài khoản',true);
			break;
		case 'tags':
			$url_add = array('plugin'=>false, 'controller'=>'tags','action'=>'synTag');
			$title_add = __('Đồng bộ lại',true);
			break;
	}
	if ($admin['role'] == 'admin') {
		if(!empty($url_add)){
			if($controller=='tags'){
				echo $this->Html->link(__('Đồng bộ',true),$url_add,array('title'=>$title_add, 'class'=>'add tooltip'));
			}else echo $this->Html->link(__('Thêm',true),$url_add,array('title'=>$title_add, 'class'=>'add tooltip'));
		}else echo $this->Html->link(__('Thêm',true),'javascript:',array('title'=>'', 'class'=>'add not_add'));
	}
?>
	<div id="nav_column_left" class="nav">
		<ul>
			<?php if ($admin['role'] == 'admin') { ?>
			<?php if($controller=='faqs' || $controller=='faq_categories'){?>
			<li<?php if($controller=='faqs') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Danh sách FAQs',true),array('plugin'=>false, 'controller'=>'faqs','action'=>'index'),array('title'=>__('Danh sách FAQs',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'faqs','action'=>'add'),array('title'=>__('Thêm FAQs',true),'class'=>'add tooltip'));
				?>
			</li>
			<li<?php if($controller=='faq_categories') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Nhóm',true),array('plugin'=>false, 'controller'=>'faq_categories','action'=>'index'),array('title'=>__('Nhóm',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'faq_categories','action'=>'add'),array('title'=>__('Thêm nhóm',true),'class'=>'add tooltip'));
				?>
			</li>
			<?php }elseif($controller=='currencies'){?>
			<li class="current">
				<?php
					echo $this->Html->link(__('Đơn vị tiền',true),array('plugin'=>false, 'controller'=>'currencies','action'=>'index'),array('title'=>__('Đơn vị tiền',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'currencies','action'=>'add'),array('title'=>__('Thêm đơn vị tiền',true),'class'=>'add tooltip','escape'=>false));
				?>
			</li>
			<?php }elseif($controller=='tags'){?>
			<li class="current">
				<?php echo $this->Html->link(__('Tags',true),array('plugin'=>false, 'controller'=>'currencies','action'=>'index'),array('title'=>__('Tags',true))) ?>
			</li>
			<?php }elseif($controller=='newsletters' && $action=='admin_index'){?>
			<li class="current">
				<?php echo $this->Html->link(__('Newsletters',true),array('plugin'=>false, 'controller'=>'newsletters','action'=>'index'),array('title'=>__('Newsletters',true)))?>
			</li>
			<?php }elseif($controller=='newsletters' && $action =='admin_exportEmail'){?>
			<li class="current">
				<?php echo $this->Html->link(__('Export Email',true),array('plugin'=>false, 'controller'=>'newsletters','action'=>'exportEmail'),array('title'=>__('Export Email',true)))?>
			</li>
			<?php }elseif($controller=='supports'){?>
			<li class="current">
				<?php
					echo $this->Html->link(__('Hỗ trợ trực tuyến',true),array('plugin'=>false, 'controller'=>'supports','action'=>'index'),array('title'=>__('Hỗ trợ trực tuyến',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'supports','action'=>'add'),array('title'=>__('Thêm hỗ trợ',true),'class'=>'add tooltip','escape'=>false));
				?>
			</li>
			<?php }elseif($controller=='sitemaps' && $action=='admin_robots'){?>
			<li class="current">
				<?php echo $this->Html->link(__('Robots.txt',true),array('plugin'=>false, 'controller'=>'sitemaps','action'=>'robots'),array('title'=>__('Robots.txt',true)))?>
			</li>
			<?php }elseif($controller=='users'){?>
			<li class="current">
				<?php
					echo $this->Html->link(__('Tài khoản',true),array('plugin'=>false, 'controller'=>'users','action'=>'index'),array('title'=>__('Tài khoản',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'users','action'=>'add'),array('title'=>__('Thêm tài khoản',true),'class'=>'add tooltip','escape'=>false));
				?>
			</li>
			<?php }?>
			<?php }?>
		</ul>

		<p class="line"></p>

		<ul>
			<?php if ($admin['role'] == 'admin') { ?>
				<?php if(!empty($oneweb_faq['enable'])){?>
				<li<?php if($controller=='faqs' || $controller=='faq_categories') echo ' class="current"'?>>
					<?php echo $this->Html->link(__('FAQs',true),array('plugin'=>false, 'controller'=>'faqs','action'=>'index'),array('title'=>__('FAQs',true)));?>
				</li>
				<?php }if(!empty($oneweb_product['enable']) && !empty($oneweb_product['currency'])){?>
				<li<?php if($controller=='currencies') echo ' class="current"'?>>
					<?php echo $this->Html->link(__('Đơn vị tiền',true),array('plugin'=>false, 'controller'=>'currencies','action'=>'index'),array('title'=>__('Đơn vị tiền',true)));?>
				</li>
				<?php }if(!empty($oneweb_product['tag']) || !empty($oneweb_post['tag'])){?>
				<li<?php if($controller=='tags') echo ' class="current"'?>>
					<?php echo $this->Html->link(__('Tags',true),array('plugin'=>false, 'controller'=>'tags','action'=>'index'),array('title'=>__('Tags',true)));?>
				</li>
				<?php }if(!empty($oneweb_newsletter['enable'])){?>
				<li<?php if($controller=='newsletters' && $action=='admin_index') echo ' class="current"'?>>
					<?php echo $this->Html->link(__('Newsletter',true),array('plugin'=>false, 'controller'=>'newsletters','action'=>'index'),array('title'=>__('Newsletter',true)));?>
				</li>
				<?php }if(!empty($oneweb_support['enable'])){?>
				<li<?php if($controller=='supports') echo ' class="current"'?>>
					<?php echo $this->Html->link(__('Hỗ trợ trực tuyến',true),array('plugin'=>false, 'controller'=>'supports','action'=>'index'),array('title'=>__('Hỗ trợ trực tuyến',true)))?>
				</li>
				<?php }?>
				<li<?php if($controller=='users') echo ' class="current"'?>>
					<?php echo $this->Html->link(__('Tài khoản',true),array('plugin'=>false, 'controller'=>'users','action'=>'index'),array('title'=>__('Tài khoản',true)));?>
				</li>
				<?php if(!empty($oneweb_seo)){?>
				<li<?php if($controller=='sitemaps' && $action=='admin_robots') echo ' class="current"'?>>
					<?php echo $this->Html->link(__('Robots.txt',true),array('plugin'=>false, 'controller'=>'sitemaps','action'=>'robots'),array('title'=>__('Robots.txt',true)));?>
				</li>
				<?php }if(!empty($oneweb_sitemap['xml'])){?>
				<li<?php if($controller=='sitemaps' && $action!='admin_robots') echo ' class="current"'?>>
					<?php echo $this->Html->link(__('Sitemap XML',true),'/sitemap.xml',array('title'=>__('Sitemap XML',true),'target'=>'_blank'));?>
				</li>
				<?php }?>
				<li<?php if($controller=='newsletters' && $action=='admin_exportEmail') echo ' class="current"'?>>
					<?php echo $this->Html->link(__('Export Email',true),array('plugin'=>false, 'controller'=>'newsletters','action'=>'exportEmail'),array('title'=>__('Export Email',true)));?>
				</li>
			<?php } ?>
		</ul>
	</div> <!--  end #nav_column_left -->
<?php }elseif($controller=='comments'){

	//**** Binh luan *****/

	echo $this->Html->link(__('Thêm',true),'javascript:;',array('title'=>'', 'class'=>'not_add'));
?>
	<div id="nav_column_left" class="nav">
		<ul>
			<li<?php if($controller=='comments' && empty($_GET['model'])) echo ' class="current"'?>><?php echo $this->Html->link(__('Tất cả bình luận',true),array('plugin'=>false, 'controller'=>'comments','action'=>'index'),array('title'=>__('Bình luận',true)))?></li>
			<?php if($oneweb_product['comment']){?>
			<li<?php if($controller=='comments' && !empty($_GET['model']) && $_GET['model']=='Product') echo ' class="current"'?>><?php echo $this->Html->link(__('Bình luận sản phẩm',true),array('plugin'=>false, 'controller'=>'comments','action'=>'index','?'=>array('model'=>'Product')),array('title'=>__('Bình luận sản phẩm',true)))?></li>
			<?php }if($oneweb_post['comment']){?>
			<li<?php if($controller=='comments' && !empty($_GET['model']) && $_GET['model']=='Post') echo ' class="current"'?>><?php echo $this->Html->link(__('Bình luận bài viết',true),array('plugin'=>false, 'controller'=>'comments','action'=>'index','?'=>array('model'=>'Post')),array('title'=>__('Bình luận bài viết',true)))?></li>
			<?php }if($oneweb_media['gallery']['comment']){?>
			<li<?php if($controller=='comments' && !empty($_GET['model']) && $_GET['model']=='Gallery') echo ' class="current"'?>><?php echo $this->Html->link(__('Bình luận hình ảnh',true),array('plugin'=>false, 'controller'=>'comments','action'=>'index','?'=>array('model'=>'Gallery')),array('title'=>__('Bình luận hình ảnh',true)))?></li>
			<?php }if($oneweb_media['video']['comment']){?>
			<li<?php if($controller=='comments' && !empty($_GET['model']) && $_GET['model']=='Video') echo ' class="current"'?>><?php echo $this->Html->link(__('Bình luận video',true),array('plugin'=>false, 'controller'=>'comments','action'=>'index','?'=>array('model'=>'Video')),array('title'=>__('Bình luận video',true)))?></li>
			<?php }?>
		</ul>
	</div> <!--  end #nav_column_left -->
<?php
}elseif ($controller=='members' || $controller=='member_messages'){
	//**** member *****/
	switch ($controller){
		case 'member_messages':
			$url_add = array('plugin'=>false, 'controller'=>'member_messages','action'=>'add');
			$title_add = __('Thêm thông báo',true);
			break;
	}
	if(!empty($url_add)) echo $this->Html->link('Thêm',$url_add,array('title'=>$title_add, 'class'=>'add tooltip'));
	else echo $this->Html->link('Thêm','javascript:',array('title'=>'', 'class'=>'not_add'));
?>
	<div id="nav_column_left" class="nav">
		<ul>
			<li<?php if($controller=='member_messages') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Thông báo',true),array('plugin'=>false, 'controller'=>'member_messages','action'=>'index'),array('title'=>__('Danh sách thông báo',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'member_messages','action'=>'add'),array('title'=>__('Thêm tin thông báo',true),'class'=>'add tooltip'));
				?>
			</li>
		</ul>
	</div> <!--  end #nav_column_left -->
<?php
}elseif ($controller=='poll_questions' || $controller=='polls'){
	//**** poll *****/
	switch ($controller){
		case 'poll_questions':
			$url_add = array('plugin'=>false, 'controller'=>'poll_questions','action'=>'add');
			$title_add = __('Thêm câu hỏi',true);
			break;
		case 'polls':
			$url_add = array('plugin'=>false, 'controller'=>'polls','action'=>'add');
			$title_add = __('Thêm câu trả lời',true);
			break;
	}
	if(!empty($url_add)) echo $this->Html->link('Thêm',$url_add,array('title'=>$title_add, 'class'=>'add tooltip'));
	else echo $this->Html->link('Thêm','javascript:',array('title'=>'', 'class'=>'not_add'));
?>
	<div id="nav_column_left" class="nav">
		<ul>
			<li<?php if($controller=='poll_questions') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Poll Question',true),array('plugin'=>false, 'controller'=>'poll_questions','action'=>'index'),array('title'=>__('Danh sách câu hỏi',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'poll_questions','action'=>'add'),array('title'=>__('Thêm tin câu hỏi',true),'class'=>'add tooltip'));
				?>
			</li>
			<li<?php if($controller=='polls') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Poll',true),array('plugin'=>false, 'controller'=>'polls','action'=>'index'),array('title'=>__('Danh sách thông báo',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'polls','action'=>'add'),array('title'=>__('Thêm câu trả lời',true),'class'=>'add tooltip'));
				?>
			</li>

		</ul>
	</div> <!--  end #nav_column_left -->
<?php
	}elseif($controller=='trashes'){
?>
	<div id="nav_column_left" class="nav">
		<ul><li></li></ul>
	</div> <!--  end #nav_column_left -->
<?php }else{

	//**** SẢN PHẨM *****/
	switch ($controller){
		case 'product_categories':
			$url_add = array('plugin'=>false, 'controller'=>'product_categories','action'=>'add');
			$title_add = __('Thêm danh mục sản phẩm',true);
			break;
		case 'product_makers':
			$url_add = array('plugin'=>false, 'controller'=>'product_makers','action'=>'add');
			$title_add = __('Thêm hãng sản xuất',true);
			break;
		case 'product_colors':
			$url_add = array('plugin'=>'AdvancedProductAttributes', 'controller'=>'product_colors','action'=>'add');
			$title_add = __('Thêm màu sắc',true);
			break;
		case 'product_sizes':
			$url_add = array('plugin'=>'AdvancedProductAttributes', 'controller'=>'product_sizes','action'=>'add');
			$title_add = __('Thêm kích cỡ',true);
			break;
		case 'product_taxes':
			$url_add = array('plugin'=>false, 'controller'=>'product_taxes','action'=>'add');
			$title_add = __('Thêm thuế',true);
			break;
		case 'products':
			$url_add = array('plugin'=>false, 'controller'=>'products','action'=>'add');
			$title_add = __('Thêm sản phẩm',true);
			break;
	}

	if(!empty($url_add)) echo $this->Html->link(__('Thêm',true),$url_add,array('title'=>$title_add, 'class'=>'add tooltip'));
	else echo $this->Html->link('Thêm','javascript:;',array('title'=>'', 'class'=>'not_add'));
?>
	<div id="nav_column_left" class="nav">
		<ul>
			<li<?php if($controller=='products') echo ' class="current"'?>><?php echo $this->Html->link(__('Sản phẩm',true),array('plugin'=>false, 'controller'=>'products','action'=>'index'),array('title'=>__('Danh sách sản phẩm',true)))?>
				<?php if(!empty($oneweb_product['display'])){?>
				<ul>
					<?php foreach($oneweb_product['display'] as $key=>$val){?>
					<li><?php echo $this->Html->link(__($val,true),array('plugin'=>false, 'controller'=>'products','action'=>'index','?'=>array('position'=>$key)),array('title'=>__($val,true)))?></li>
					<?php }?>
				</ul>
				<?php }?>
			</li>
			<li<?php if($controller=='product_categories') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Danh mục',true),array('plugin'=>false, 'controller'=>'product_categories','action'=>'index'),array('title'=>__('Danh mục sản phẩm',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'product_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true),'class'=>'add tooltip'));
				?>
			</li>
			<?php if(!empty($oneweb_product['maker'])){?>
			<li<?php if($controller=='product_makers') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Hãng sản xuất',true),array('plugin'=>false, 'controller'=>'product_makers','action'=>'index'),array('title'=>__('Hãng sản xuất',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'product_makers','action'=>'add'),array('title'=>__('Thêm hãng sản xuất',true),'class'=>'add tooltip'));
				?>
			</li>
			<?php }?>
			<?php if(!empty($oneweb_product['tax'])){?>
			<li<?php if($controller=='product_taxes') echo ' class="current"'?>>
				<?php
					echo $this->Html->link(__('Thuế',true),array('plugin'=>false, 'controller'=>'product_taxes','action'=>'index'),array('title'=>__('Thuế',true)));
					echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'product_taxes','action'=>'add'),array('title'=>__('Thêm thuế',true),'class'=>'add tooltip'));
				?>
			</li>
			<?php }?>
		</ul>

		<ul>
			<li>
					<?php
						echo $this->Html->link(__('Đăng ký tư vấn',true).' '.$this->Html->tag('span','('.$count_tv_new_c.'/'.$count_tv_c.')',array('class'=>'blink')),array('controller'=>'contact_forms','action'=>'index'),array('title'=>__('Bạn có',true).' '.$count_tv_new_c.' '.__(' mới',true), 'class'=>'tooltip','escape'=>false));
					?>
				</li>
				<li>
					<?php
						echo $this->Html->link(__('Nhận quà tặng',true).' '.$this->Html->tag('span','('.$count_gift_new_c.'/'.$count_gift_c.')',array('class'=>'blink')),array('controller'=>'contact_forms','action'=>'index', '?'=>array('type'=>'gift')),array('title'=>__('Bạn có',true).' '.$count_gift_new_c.' '.__(' mới',true), 'class'=>'tooltip','escape'=>false));
					?>
				</li>
				<li>
					<?php
						echo $this->Html->link(__('Tham gia sự kiện',true).' '.$this->Html->tag('span','('.$count_event_new_c.'/'.$count_event_c.')',array('class'=>'blink')),array('controller'=>'contact_forms','action'=>'index', '?'=>array('type'=>'event')),array('title'=>__('Bạn có',true).' '.$count_event_new_c.' '.__(' mới',true), 'class'=>'tooltip','escape'=>false));
					?>
				</li>
		</ul>
	</div> <!--  end #nav_column_left -->

<?php }?>