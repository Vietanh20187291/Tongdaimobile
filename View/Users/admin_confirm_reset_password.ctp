		
<div id="forget_password">
	<h2 class="title"><?php echo __('Xác nhận lấy lại mật khẩu',true);?></h2>
	<?php 
		echo $this->Session->flash();
		echo $this->Session->flash('auth'); 
	?>
	
	<?php 
			if($success_c) echo __('Mật khẩu mới đã được gửi tới Email',true);
			else echo __('Có lỗi, bạn vui lòng liên hệ với quan trị website để được hướng dẫn.',true);
	?>
	
</div> <!-- end #login -->