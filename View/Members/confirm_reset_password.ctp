<article class="box_content">
	<header class="title">
		<div class="title_right">
			<div class="title_center">
				<h1><span><?php echo __('Xác nhận lấy lại mật khẩu',true);?></span></h1>
			</div> <!--  end .title_center -->
		</div> <!--  end .title_right -->
	</header> <!--  end .title -->
	
	<div class="des">
		<?php 
			if($success_c) echo __('Mật khẩu mới đã được gửi tới Email',true);
			else echo __('Có lỗi, bạn vui lòng liên hệ với quan trị website để được hướng dẫn.',true);
		?>
	</div> <!--  end .des -->
			
	<div class="top"></div>
	<div class="bottom"></div>
</article> <!--  end .box_content -->