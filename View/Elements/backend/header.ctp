<?php
$controller = $this->params['controller'];
$action = $this->params['action'];
?>
<div id="header">
	<div id="bg_nav" class="nav">
		<ul id="navmenu">

			<?php if ($admin['role'] == 'admin') { ?>

				<li<?php if($controller=='pages') echo ' class="current"'?>><?php echo $this->Html->link($this->Html->tag('span',__('Trang chủ',true)),array('plugin'=>false, 'controller'=>'pages','action'=>'index'),array('title'=>__('Trang chủ',true),'escape'=>false))?></li>

				<?php if(!empty($oneweb_information['enable'])){?>
					<li<?php if($controller=='information') echo ' class="current"'?>><?php echo $this->Html->link($this->Html->tag('span',__('Thông tin',true)),array('plugin'=>false, 'controller'=>'information','action'=>'index'),array('title'=>__('Thông tin',true),'escape'=>false))?></li>
				<?php }?>

			<?php } ?>

			<?php if(!empty($oneweb_post['enable'])){?>
				<li<?php if(in_array($controller, array('posts','post_categories'))) echo ' class="current"'?>>
					<?php echo $this->Html->link($this->Html->tag('span',__('Bài viết',true)),array('plugin'=>false, 'controller'=>'posts','action'=>'index'),array('title'=>__('Bài viết',true),'escape'=>false))?>
					<ul>
						<li><?php echo $this->Html->link('<span class="icon post"></span>'.__('Bài viết',true),array('plugin'=>false, 'controller'=>'posts','action'=>'index'),array('title'=>__('Bài viết',true),'escape'=>false))?></li>
						<li><?php echo $this->Html->link('<span class="icon cate"></span>'.__('Danh mục',true),array('plugin'=>false, 'controller'=>'post_categories','action'=>'index'),array('title'=>__('Danh mục',true),'escape'=>false))?></li>
					</ul>
				</li>
			<?php }?>
			<li<?php if($controller=='statistics') echo ' class="current"'?>>
				<?php echo $this->Html->link($this->Html->tag('span',__('Thống kê', true)),'javascript:;',array('title'=>__('Thống kê', true),'escape'=>false))?>
				<ul>
					<li><?php echo $this->Html->link('<span class="icon poll"></span>'.__('Bài viết kích hoạt',true),array('controller'=>'statistics','action'=>'status_posts'),array('title'=>__('Bài viết kích hoạt',true),'escape'=>false))?></li>
				</ul>
			</li>
			<?php if ($admin['role'] == 'admin') { ?>

				<?php if($oneweb_banner['enable']){?>
					<li<?php if(in_array($controller, array('galleries','gallery_categories','videos','video_categories','documents','document_categories','banners'))) echo ' class="current"'?>>
						<?php echo $this->Html->link($this->Html->tag('span','Media'),'javascript:;',array('title'=>'Media','escape'=>false))?>
						<ul>
							<?php if(!empty($oneweb_media['gallery']['enable'])){?>
								<li><?php echo $this->Html->link('<span class="icon gallery"></span>'.__('Hình ảnh',true),array('plugin'=>false, 'controller'=>'galleries','action'=>'index'),array('title'=>__('Hình ảnh',true),'escape'=>false))?></li>
							<?php }
							if(!empty($oneweb_media['video']['enable'])){?>
								<li><?php echo $this->Html->link('<span class="icon video"></span>'.__('Video',true),array('plugin'=>false, 'controller'=>'videos','action'=>'index'),array('title'=>__('Video',true),'escape'=>false))?></li>
							<?php }
							if(!empty($oneweb_media['document']['enable'])){?>
								<li><?php echo $this->Html->link('<span class="icon document"></span>'.__('Tài liệu',true),array('plugin'=>false, 'controller'=>'documents','action'=>'index'),array('title'=>__('Tài liệu',true),'escape'=>false))?></li>
							<?php }if(!empty($oneweb_banner['enable'])){?>
								<li><?php echo $this->Html->link('<span class="icon banner"></span>'.__('Banner',true),array('plugin'=>false, 'controller'=>'banners','action'=>'index'),array('title'=>__('Banner',true),'escape'=>false))?></li>
							<?php }?>

						</ul>
					</li>
				<?php }?>
				<?php if(!empty($oneweb_member['enable'])){?>
					<li<?php if($controller=='members') echo ' class="current"'?>>
						<?php echo $this->Html->link($this->Html->tag('span',__('Thành viên', true)), array('plugin'=>false, 'controller'=>'members', 'action'=>'index'),array('title'=>__('Thành viên', true),'escape'=>false));?>
						<ul>
							<?php if(!empty($oneweb_member['message'])){?>
								<li><?php echo $this->Html->link('<span class="icon promotion"></span>'.__('Thông báo',true),array('plugin'=>false, 'controller'=>'member_messages','action'=>'index'),array('title'=>__('Thông báo',true),'escape'=>false))?></li>
							<?php }?>

						</ul>
					</li>
				<?php }?>

				<?php if(!empty($oneweb_poll['enable'])){?>
					<li<?php if($controller=='poll_questions') echo ' class="current"'?>>
						<?php echo $this->Html->link($this->Html->tag('span',__('Bỏ phiếu', true)), array('plugin'=>false, 'controller'=>'poll_questions', 'action'=>'index'),array('title'=>__('Bỏ phiếu', true),'escape'=>false));?>
						<ul>
							<li><?php echo $this->Html->link('<span class="icon poll"></span>'.__('Câu trả lời',true),array('plugin'=>false, 'controller'=>'polls','action'=>'index'),array('title'=>__('Câu trả lời',true),'escape'=>false))?></li>

						</ul>
					</li>
				<?php }?>

				<?php if(!empty($oneweb_web['comment'])){ //Comment ?>
					<li id="comment_notice" class="<?php if($controller=='comments') echo ' current'?>">
						<?php echo $this->Html->link($this->Html->tag('span',__('Bình luận',true)),array('plugin'=>false, 'controller'=>'comments','action'=>'index'),array('title'=>__('Bình luận',true),'escape'=>false))?>
					</li>
				<?php }?>

				<li <?php if($controller == 'advertisements') echo 'current'?>><?php echo $this->Html->link($this->Html->tag('span','Quảng cáo'),array('plugin'=>false, 'controller'=>'advertisements','action'=>'index'),array('title'=>__('Chèn quảng cáo',true),'escape'=>false))?>
				</li>

				<li<?php if(in_array($controller, array('faqs','currencies','faq_categories','tags','newsletters','supports','sitemaps','countries','question_secrets','users','trashes'))) echo ' class="current"'?>>
					<?php echo $this->Html->link($this->Html->tag('span',__('Khác',true)),'javascript:;',array('title'=>__('Khác',true),'escape'=>false))?>
					<ul>
						<li>
							<?php echo $this->Html->link('<span class="icon xml"></span> Lấy mã form nhúng', array('controller'=>'contact_forms', 'action'=>'admin_getcode'), array('escape'=>false))?>
						</li>
						<?php if(!empty($oneweb_faq['enable'])){?>
							<li><?php echo $this->Html->link('<span class="icon faq"></span>'.__('FAQs',true),array('plugin'=>false, 'controller'=>'faqs','action'=>'index'),array('title'=>__('FAQs',true),'escape'=>false))?></li>
						<?php }if(!empty($oneweb_product['enable']) && !empty($oneweb_product['currency'])){?>
							<li><?php echo $this->Html->link('<span class="icon currency"></span>'.__('Đơn vị tiền',true),array('plugin'=>false, 'controller'=>'currencies','action'=>'index'),array('title'=>__('Đơn vị tiền',true),'escape'=>false))?></li>
						<?php }
						if(!empty($oneweb_product['tag']) || !empty($oneweb_post['tag'])){?>
							<li><?php echo $this->Html->link('<span class="icon tag"></span>Tag',array('plugin'=>false, 'controller'=>'tags','action'=>'index'),array('title'=>'Tag','escape'=>false))?></li>
						<?php }
						if(!empty($oneweb_newsletter['enable'])){?>
							<li><?php echo $this->Html->link('<span class="icon newsletter"></span>'.__('Newsletter',true),array('plugin'=>false, 'controller'=>'newsletters','action'=>'index'),array('title'=>__('Newsletter',true),'escape'=>false))?></li>
						<?php }
						if(!empty($oneweb_support['enable'])){?>
							<li><?php echo $this->Html->link('<span class="icon support"></span>'.__('Hỗ trợ trực tuyến',true),array('plugin'=>false, 'controller'=>'supports','action'=>'index'),array('title'=>__('Hỗ trợ trực tuyến',true),'escape'=>false))?></li>
							<?php if(!empty($oneweb_support['livechat'])){?>
								<li><?php echo $this->Html->link('<span class="icon livechat"></span>'.__('Live Chat',true),'/livechat',array('title'=>__('Live Chat',true),'target'=>('_blank'),'escape'=>false))?></li>
							<?php }}
						if(!empty($oneweb_seo)){?>
							<li><?php echo $this->Html->link('<span class="icon robots"></span>'.__('Robots.txt',true),array('plugin'=>false, 'controller'=>'sitemaps','action'=>'robots'),array('title'=>__('Robots.txt',true),'escape'=>false))?></li>
						<?php }
						if(!empty($oneweb_sitemap['xml'])){?>
							<li><?php echo $this->Html->link('<span class="icon xml"></span>'.__('Sitemap XML',true),'/all-sitemap.xml',array('title'=>__('Sitemap XML',true),'target'=>'_blank','escape'=>false))?></li>
							<li><?php echo $this->Html->link('<span class="icon robots"></span>'.__('add Url',true),array('controller'=>'pages','action'=>'addUrl'),array('title'=>__('addUrl',true),'escape'=>false))?></li>
							<li><?php echo $this->Html->link('<span class="icon robots"></span>'.__('add Url ',true).'<b style="font-size: 11px">new</b>',array('controller'=>'pages','action'=>'addUrl_new'),array('title'=>__('addUrl',true),'escape'=>false))?></li>
							<li><?php echo $this->Html->link('<span class="icon robots"></span>'.__('New Sitemap',true),array('controller'=>'pages','action'=>'newSiteMap'),array('title'=>__('addUrl123',true),'escape'=>false))?></li>
						<?php }?>
						<li ><?php echo $this->Html->link('<span class="icon account"></span>'.__('Tài khoản',true),array('plugin'=>false, 'controller'=>'users','action'=>'index'),array('title'=>__('Tài khoản',true),'escape'=>false))?></li>
						<li class="line"><?php echo $this->Html->link('<span class="icon exportemail"></span>'.__('Export Email',true),array('plugin'=>false, 'controller'=>'newsletters','action'=>'exportEmail'),array('title'=>__('Export Email',true),'escape'=>false))?></li>
						<li><?php echo $this->Html->link('<span class="icon cache"></span>'.__('Xóa Cache',true),array('plugin'=>false, 'controller'=>'pages','action'=>'delCache'),array('title'=>__('Xóa Cache',true),'class'=>'del_cache','escape'=>false))?></li>
						<li class="trash_nav"><?php echo $this->Html->link('<span class="icon trash_nav"></span>'.__('Thùng rác',true),array('plugin'=>false, 'controller'=>'trashes','action'=>'index'),array('title'=>__('Thùng rác',true),'escape'=>false))?></li>
					</ul>
				</li>
				<li<?php if($controller=='configs') echo ' class="current"'?>><?php echo $this->Html->link($this->Html->tag('span',__('Cấu hình',true)),array('plugin'=>false, 'controller'=>'configs','action'=>'edit'),array('title'=>__('Cấu hình',true),'escape'=>false))?></li>

			<?php } ?>
		</ul> <!--  end #nav -->
	</div> <!--  end #bg_nav -->

	<?php echo $this->Html->image('admin/loading.gif',array('height'=>25,'id'=>'loading'))?>

	<?php
	if ($admin['role'] == 'admin') {
		if(!empty($oneweb_product['order']) || !empty($oneweb_contact['enable'])){
			$url_mess = array('plugin'=>false, 'controller'=>'orders','action'=>'index');
			if(empty($oneweb_product['order'])) $url_mess = array('plugin'=>false, 'controller'=>'contacts','action'=>'index');
			echo $this->Html->link('&nbsp;',$url_mess,array('title'=>__('Bạn có thư',true),'class'=>'notice tooltip','escape'=>false));
		}
	}
	?>

	<div class="h_right">
		<div class="del_cache"><?php echo $this->Html->link('&nbsp;',array('plugin'=>false, 'controller'=>'pages','action'=>'delCache'),array('title'=>__('Xóa Cache',true),'class'=>'del_cache','escape'=>false))?></div>
		<?php if(count($oneweb_language)>1){?>
			<div>
				<?php
				$lang = $this->Session->read('lang');
				echo $this->Form->create('Page',array('url'=>array('plugin'=>false, 'controller'=>'pages','action'=>'changeLanguage'),'inputDefaults'=>array('label'=>false,'div'=>false)));
				echo $this->Form->input('lang',array('type'=>'select','options'=>$oneweb_language,'value'=>$lang,'onchange'=>'form.submit()'));
				echo $this->Form->end();
				?>
			</div>
		<?php }?>

		<ul class="account">
			<li><?php echo $this->Html->link('Hi: '.$this->Text->truncate($admin['name'],10),'javascript:;',array('title'=>'Tài khoản của bạn'))?>
				<ul>
					<li><?php echo $this->Html->link(__('Tài khoản của tôi',true),array('plugin'=>false, 'controller'=>'users','action'=>'myAcount'),array('title'=>__('Tài khoản của tôi',true)))?></li>
					<?php if ($admin['role'] == 'admin') { ?>
						<li><?php echo $this->Html->link(__('Quản lý tài khoản',true),array('plugin'=>false, 'controller'=>'users','action'=>'index'),array('title'=>__('Quản lý tài khoản',true)))?></li>
					<?php } ?>
					<li><?php echo $this->Html->link(__('Thoát',true),array('plugin'=>false, 'controller'=>'users','action'=>'logout','admin'=>true),array('title'=>__('Thoát',true),'class'=>'logout tooltip','confirm'=>__('Bạn có chắc chắn muốn thoát không?',true)))?></li>
				</ul>
			</li>
		</ul>
	</div> <!--  end .h_right -->
</div> <!--  end #header -->