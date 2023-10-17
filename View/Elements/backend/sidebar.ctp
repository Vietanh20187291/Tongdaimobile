<div id="column_left">
	<div id="left_top">
		<?php echo $this->Html->link($this->Html->image('admin/logo2.png',array('alt'=>'Thiết kế bởi URL.vn')),'/',array('title'=>__('Trang chủ',true),'target'=>'_blank','escape'=>false,'class'=>'logo tooltip'))?>
	</div> <!--  end #left_top -->

	<div id="left_middle">
		<?php
			echo $this->element('backend/s_nav');
			echo $this->element('backend/s_breadcrumb');
		?>

		<div class="del_cache" <?php if($this->Session->check('modified')) echo 'style = "display:block"'?>>
			<?php echo $this->Html->link(__('Xóa Cache',true),array('plugin'=>false, 'controller'=>'pages','action'=>'delCache'),array('title'=>__('Khi có sự thay đổi, bạn cần phải xóa cache để trang web được cập nhật lại',true),'class'=>'tooltip'))?>
		</div>

		<div id="left_footer">
			<p>Công ty thiết kế web URL</p>
		</div> <!--  end #left_footer -->
	</div> <!--  end #left_middle -->
</div> <!--  end #column_left -->