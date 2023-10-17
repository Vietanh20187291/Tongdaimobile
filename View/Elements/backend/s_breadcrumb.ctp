<?php
$controller = $this->params['controller'];
$action 	= $this->params['action'];
$i = 1;
?>
<ul class="breadcrumb">
	<li<?php if(($controller=='posts') && $action=='admin_import' && empty($_GET['position'])) echo ' class="current"'?>><span>*</span><?php echo $this->Html->link('Import Bài Viết',array('controller'=>'posts','action'=>'import'),array('title'=>'Import Bài Viết'));?></li>
	<li<?php if($controller=='pages') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Trang chủ',true),array('plugin'=>false, 'controller'=>'pages','action'=>'index'),array('title'=>__('Trang chủ',true)));?></li>

	<?php if($controller=='posts' || $controller=='post_categories'){

		//*********************** BÀI VIẾT ***********************//?>

		<li<?php if(($controller=='posts') && $action=='admin_index' && empty($_GET['position'])) echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Bài viết',true),array('plugin'=>false, 'controller'=>'posts','action'=>'index'),array('title'=>__('Bài viết',true)));?></li>
		<?php if($controller=='posts'){?>
			<?php if(!empty($_GET['position']) && !empty($oneweb_post['display'][$_GET['position']])){?>
				<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__($oneweb_post['display'][$_GET['position']],true),array('plugin'=>false, 'controller'=>'posts','action'=>'index','?'=>array('position'=>$_GET['position'])),array('title'=>__($oneweb_post['display'][$_GET['position']],true)));?></li>
			<?php }
			if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm bài viết',true),array('plugin'=>false, 'controller'=>'posts','action'=>'add'),array('title'=>__('Thêm bài viết',true)));?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa bài viết',true),'javascript:;',array('title'=>__('Sửa bài viết',true)))?></li>
			<?php }?>
		<?php }?>

		<?php if($controller=='post_categories'){ //Danh muc bai viet?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Danh mục',true),array('plugin'=>false, 'controller'=>'post_categories','action'=>'index'),array('title'=>__('Danh mục',true)))?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm danh mục',true),array('plugin'=>false, 'controller'=>'post_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa danh mục',true),'javascript:;',array('title'=>__('Sửa danh mục',true)))?></li>
			<?php }?>

		<?php }?>

	<?php }elseif($controller=='faqs' || $controller=='faq_categories'){

		//*********************** FAQs **********************//?>

		<li<?php if($controller=='faqs' && $action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link('FAQs',array('plugin'=>false, 'controller'=>'faqs','action'=>'index'),array('title'=>'FAQs'));?></li>

		<?php if($controller=='faqs'){?>
			<?php
			if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm FAQs',true),array('plugin'=>false, 'controller'=>'faqs','action'=>'add'),array('title'=>__('Thêm FAQs',true)));?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa FAQs',true),'javascript:;',array('title'=>__('Sửa FAQs',true)))?></li>
			<?php }?>
		<?php }?>

		<?php if($controller=='faq_categories'){ //Danh muc FAQs?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Nhóm',true),array('plugin'=>false, 'controller'=>'faq_categories','action'=>'index'),array('title'=>__('Nhóm',true)))?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm nhóm',true),array('plugin'=>false, 'controller'=>'faq_categories','action'=>'add'),array('title'=>__('Thêm nhóm',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa nhóm',true),'javascript:;',array('title'=>__('Sửa nhóm',true)))?></li>
			<?php }?>
		<?php }?>

	<?php }elseif($controller=='supports'){

		//*********************** Support **********************//?>

		<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Hỗ trợ trực tuyến',true),array('plugin'=>false, 'controller'=>'supports','action'=>'index'),array('title'=>__('Hỗ trợ trực tuyến',true)));?></li>

		<?php
		if($action=='admin_add'){?>
			<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm hỗ trợ',true),array('plugin'=>false, 'controller'=>'supports','action'=>'add'),array('title'=>__('Thêm hỗ trợ trực tuyến',true)));?></li>
		<?php }elseif($action=='admin_edit'){?>
			<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa hỗ trợ',true),'javascript:;',array('title'=>__('Sửa hỗ trợ trực tuyến',true)))?></li>
		<?php }?>
	<?php }elseif($controller=='information'){

		//*********************** Information **********************//?>

		<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thông tin',true),array('plugin'=>false, 'controller'=>'information','action'=>'index'),array('title'=>__('Thông tin',true)));?></li>

		<?php
		if($action=='admin_add'){?>
			<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm trang thông tin',true),array('plugin'=>false, 'controller'=>'information','action'=>'add'),array('title'=>__('Thêm trang thông tin',true)));?></li>
		<?php }elseif($action=='admin_edit'){?>
			<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa trang thông tin',true),'javascript:;',array('title'=>__('Sửa trang thông tin',true)))?></li>
		<?php }?>
	<?php }elseif($controller=='galleries' || $controller=='gallery_categories'){

		//*********************** GALLERY **********************//?>

		<li<?php if($controller=='galleries' && $action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Hình ảnh',true),array('plugin'=>false, 'controller'=>'galleries','action'=>'index'),array('title'=>__('Hình ảnh',true)))?></li>

		<?php if($controller=='galleries'){?>
			<?php
			if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm hình ảnh',true),array('plugin'=>false, 'controller'=>'galleries','action'=>'add'),array('title'=>__('Hình ảnh',true)));?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa hình ảnh',true),'javascript:;',array('title'=>__('Sửa hình ảnh',true)))?></li>
			<?php }?>
		<?php }?>

		<?php if($controller=='gallery_categories'){ //Danh muc hình ảnh?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Danh mục',true),array('plugin'=>false, 'controller'=>'gallery_categories','action'=>'index'),array('title'=>__('Danh mục',true)))?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm danh mục',true),array('plugin'=>false, 'controller'=>'gallery_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa danh mục',true),'javascript:;',array('title'=>__('Sửa danh mục',true)))?></li>
			<?php }?>
		<?php }?>

	<?php }elseif($controller=='videos' || $controller=='video_categories'){

		//*********************** VIDEO **********************//?>

		<li<?php if($controller=='videos' && $action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Video',true),array('plugin'=>false, 'controller'=>'videos','action'=>'index'),array('title'=>__('Video',true)));?></li>

		<?php if($controller=='videos'){?>
			<?php
			if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm video',true),array('plugin'=>false, 'controller'=>'videos','action'=>'add'),array('title'=>__('Video',true)));?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa video',true),'javascript:;',array('title'=>__('Sửa video',true)))?></li>
			<?php }?>
		<?php }?>

		<?php if($controller=='video_categories'){ //Danh muc video?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Danh mục',true),array('plugin'=>false, 'controller'=>'video_categories','action'=>'index'),array('title'=>__('Danh mục',true)))?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm danh mục',true),array('plugin'=>false, 'controller'=>'video_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa danh mục',true),'javascript:;',array('title'=>__('Sửa danh mục',true)))?></li>
			<?php }?>
		<?php }?>

	<?php }elseif($controller=='documents' || $controller=='document_categories'){

		//*********************** Tai lieu **********************//?>

		<li<?php if($controller=='documents' && $action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Tài liệu',true),array('plugin'=>false, 'controller'=>'documents','action'=>'index'),array('title'=>__('Tài liệu',true)));?></li>

		<?php if($controller=='documents'){?>
			<?php
			if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm tài liệu',true),array('plugin'=>false, 'controller'=>'documents','action'=>'add'),array('title'=>__('Thêm tài liệu',true)));?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa tài liệu',true),'javascript:;',array('title'=>__('Sửa tài liệu',true)))?></li>
			<?php }?>
		<?php }?>

		<?php if($controller=='document_categories'){ //Danh muc tai lieu?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Danh mục',true),array('plugin'=>false, 'controller'=>'document_categories','action'=>'index'),array('title'=>__('Danh mục',true)))?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm danh mục',true),array('plugin'=>false, 'controller'=>'document_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa danh mục',true),'javascript:;',array('title'=>__('Sửa danh mục',true)))?></li>
			<?php }?>
		<?php }?>

	<?php }elseif($controller=='banners'){

		//*********************** BANNER **********************//?>

		<li<?php if($action=='admin_index' && empty($_GET['position'])) echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Banner',true),array('plugin'=>false, 'controller'=>'banners','action'=>'index'),array('title'=>__('Banner',true)));?></li>

		<?php if(!empty($_GET['position']) && !empty($oneweb_banner['display'][$_GET['position']])){?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__($oneweb_banner['display'][$_GET['position']],true),array('plugin'=>false, 'controller'=>'banners','action'=>'index','?'=>array('position'=>$_GET['position'])),array('title'=>__($oneweb_banner['display'][$_GET['position']],true)))?></li>
		<?php }
		if($action=='admin_add'){?>

			<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm banner',true),array('plugin'=>false, 'controller'=>'banners','action'=>'add'),array('title'=>__('Thêm banner',true)));?></li>
		<?php }elseif($action=='admin_edit'){?>
			<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa banner',true),'javascript:;',array('title'=>__('Sửa banner',true)))?></li>
		<?php }?>
	<?php }elseif($controller=='users'){

		//*********************** USER **********************//?>

		<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Tài khoản',true),array('plugin'=>false, 'controller'=>'users','action'=>'index'),array('title'=>__('Tài khoản',true)));?></li>

		<?php
		if($action=='admin_add'){?>
			<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm tài khoản',true),array('plugin'=>false, 'controller'=>'users','action'=>'add'),array('title'=>__('Thêm tài khoản',true)))?></li>
		<?php }elseif($action=='admin_edit'){?>
			<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa tài khoản',true),'javascript:;',array('title'=>__('Sửa tài khoản',true)))?></li>
		<?php }?>
	<?php }elseif($controller=='currencies'){

		//*********************** Đơn vị tiền **********************//?>

		<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Đơn vị tiền',true),array('plugin'=>false, 'controller'=>'currencies','action'=>'index'),array('title'=>__('Đơn vị tiền',true)));?></li>

		<?php
		if($action=='admin_add'){?>
			<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm đơn vị tiền',true),array('plugin'=>false, 'controller'=>'currencies','action'=>'add'),array('title'=>__('Thêm đơn vị tiền',true)))?></li>
		<?php }elseif($action=='admin_edit'){?>
			<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa đơn vị tiền',true),'javascript:;',array('title'=>__('Sửa đơn vị tiền',true)))?></li>
		<?php }?>
	<?php }elseif($controller=='newsletters'){

		//*********************** Newsletters **********************//?>

		<li<?php if(($controller=='newsletters')) echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Newsletter',true),array('plugin'=>false, 'controller'=>'newsletters','action'=>'index'),array('title'=>__('Newsletters',true)));?></li>

	<?php }elseif($controller=='contacts' || $controller=='contact_categories'){

		//*********************** Liên hệ của khách hàng **********************//?>

		<li<?php if(($controller=='contacts')) echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Liên hệ',true),array('plugin'=>false, 'controller'=>'contacts','action'=>'index'),array('title'=>__('Liên hệ',true)));?></li>
		<?php if($controller=='contact_categories'){?>
			<li <?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Nhóm liên hệ',true),array('plugin'=>false, 'controller'=>'contact_categories','action'=>'index'),array('title'=>__('Nhóm liên hệ',true)));?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm nhóm liên hệ',true),array('plugin'=>false, 'controller'=>'contact_categories','action'=>'add'),array('title'=>__('Thêm nhóm liên hệ',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa nhóm liên hệ',true),'javascript:;',array('title'=>__('Sửa nhóm liên hệ',true)))?></li>
			<?php }}?>

	<?php }elseif($controller=='orders' || $controller=='order_categories'){

		//*********************** Đơn đặt hàng **********************//?>

		<li<?php if(($controller=='orders')) echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Đơn hàng',true),array('plugin'=>false, 'controller'=>'orders','action'=>'index'),array('title'=>__('Đơn hàng',true)))?></li>
		<?php if($controller=='order_categories'){?>
			<li <?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Nhóm đơn hàng',true),array('plugin'=>false, 'controller'=>'order_categories','action'=>'index'),array('title'=>__('Nhóm đơn hàng',true)))?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm nhóm đơn hàng',true),array('plugin'=>false, 'controller'=>'order_categories','action'=>'add'),array('title'=>__('Thêm nhóm đơn hàng',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa nhóm đơn hàng',true),'javascript:;',array('title'=>__('Sửa nhóm đơn hàng',true)))?></li>
			<?php }}?>

	<?php }elseif(in_array($controller, array('comments','product_comments','post_comments','gallery_comments','video_comments'))){

		//*********************** Comment **********************//?>
		<?php if($controller=='comments' && empty($_GET['model'])){?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Tất cả bình luận',true),array('plugin'=>false, 'controller'=>'comments','action'=>'index'),array('title'=>__('Tất cả bình luận',true)));?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Trả lời',true),'javascript:;',array('title'=>__('Trả lời',true)));?></li>
			<?php }?>
		<?php }elseif($controller=='comments' && !empty($_GET['model']) && $_GET['model']=='Product'){?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Bình luận sản phẩm',true),'javascript:;',array('title'=>__('Bình luận sản phẩm',true)))?></li>
		<?php }elseif($controller=='comments' && !empty($_GET['model']) && $_GET['model']=='Post'){?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Bình luận bài viết',true),'javascript:;',array('title'=>__('Bình luận bài viết',true)))?></li>
		<?php }elseif($controller=='comments' && !empty($_GET['model']) && $_GET['model']=='Gallery'){?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Bình luận hình ảnh',true),'javascript:;',array('title'=>__('Bình luận hình ảnh',true)))?></li>
		<?php }elseif($controller=='comments' && !empty($_GET['model']) && $_GET['model']=='Video'){?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Bình luận video',true),'javascript:;',array('title'=>__('Bình luận video',true)))?></li>
		<?php }?>

		<?php if($action=='admin_edit'){?>
			<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa bình luận',true),'javascript:;',array('title'=>__('Sửa bình luận',true)));?></li>
		<?php }?>
	<?php }elseif($controller=='sitemaps' && $action=='admin_robots'){

		//*********************** Robots.txt **********************//?>

		<li<?php if(($controller=='sitemaps' && $action=='admin_robots')) echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Robots.txt',true),array('plugin'=>false, 'controller'=>'sitemaps','action'=>'robots'),array('title'=>__('Robots.txt',true)));?></li>

	<?php }elseif($controller=='tags'){

		//*********************** Tag **********************//?>

		<li<?php if(($controller=='tags')) echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Tags',true),array('plugin'=>false, 'controller'=>'tags','action'=>'index'),array('title'=>__('Tags',true)));?></li>

	<?php }elseif($controller=='configs'){

		//*********************** Config **********************//?>

		<li<?php if(($controller=='configs')) echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Cấu hình',true),array('plugin'=>false, 'controller'=>'configs','action'=>'edit'),array('title'=>__('Cấu hình',true)));?></li>

	<?php }elseif($controller=='trashes'){

		//*********************** Config **********************//?>

		<li<?php if(($controller=='trashes')) echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thùng rác',true),array('plugin'=>false, 'controller'=>'trashes','action'=>'index'),array('title'=>__('Thùng rác',true)));?></li>

	<?php }elseif($controller!='pages'){

		//*********************** SẢN PHẨM ***********************// ?>

		<li<?php if(($controller=='products') && $action=='admin_index' && empty($_GET['position'])) echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sản phẩm',true),array('plugin'=>false, 'controller'=>'products','action'=>'index'),array('title'=>__('Sản phẩm',true)));?></li>
		<?php if($controller=='products'){?>
			<?php if(!empty($_GET['position']) && !empty($oneweb_product['display'][$_GET['position']])){?>
				<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__($oneweb_product['display'][$_GET['position']],true),array('plugin'=>false, 'controller'=>'products','action'=>'index','?'=>array('position'=>$_GET['position'])),array('title'=>__($oneweb_product['display'][$_GET['position']],true)))?></li>
			<?php }
			if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm sản phẩm',true),array('plugin'=>false, 'controller'=>'products','action'=>'add'),array('title'=>__('Thêm sản phẩm',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa sản phẩm',true),'javascript:;',array('title'=>__('Sửa sản phẩm',true)))?></li>
			<?php }?>
		<?php }?>

		<?php if($controller=='product_categories'){ //Danh muc sp?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Danh mục',true),array('plugin'=>false, 'controller'=>'product_categories','action'=>'index'),array('title'=>__('Danh mục',true)))?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm danh mục',true),array('plugin'=>false, 'controller'=>'product_categories','action'=>'add'),array('title'=>__('Thêm danh mục',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa danh mục',true),'javascript:;',array('title'=>__('Sửa danh mục',true)))?></li>
			<?php }?>

		<?php }elseif($controller=='product_makers'){	//Hang san xuat?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Hãng sản xuất',true),array('plugin'=>false, 'controller'=>'product_makers','action'=>'index'),array('title'=>__('Hãng sản xuất',true)))?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm hãng sản xuất',true),array('plugin'=>false, 'controller'=>'product_makers','action'=>'add'),array('title'=>__('Thêm hãng sản xuất',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa hãng sản xuất',true),'javascript:;',array('title'=>__('Sửa hãng sản xuất',true)))?></li>
			<?php }?>

		<?php }elseif($controller=='product_taxes'){	//Thue?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thuế',true),array('plugin'=>false, 'controller'=>'product_taxes','action'=>'index'),array('title'=>__('Thuế',true)))?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm thuế',true),array('plugin'=>false, 'controller'=>'product_taxes','action'=>'add'),array('title'=>__('Thêm thuế',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa thuế',true),'javascript:;',array('title'=>__('Sửa thuế',true)))?></li>
			<?php }?>

		<?php }elseif($controller=='product_currencies'){	//Don vi tien?>
			<li<?php if($action=='admin_index') echo ' class="current"'?>><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Đơn vị tiền',true),array('plugin'=>false, 'controller'=>'product_currencies','action'=>'index'),array('title'=>__('Đơn vị tiền',true)))?></li>
			<?php if($action=='admin_add'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Thêm đơn vị tiền',true),array('plugin'=>false, 'controller'=>'product_currencies','action'=>'add'),array('title'=>__('Thêm đơn vị tiền',true)))?></li>
			<?php }elseif($action=='admin_edit'){?>
				<li class="current"><span><?php echo $i; $i++;?></span><?php echo $this->Html->link(__('Sửa đơn vị tiền',true),'javascript:;',array('title'=>__('Sửa đơn vị tiền',true)))?></li>
			<?php }?>

		<?php }?>

	<?php }?>
</ul> <!--  end .breadcrumb -->