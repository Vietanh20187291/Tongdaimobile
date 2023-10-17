<article class="box_content change_address">
        <div class="title">
			<div class="title_right">
				<div class="title_center">
				<header><h1><span><?php echo __('Thay đổi mật khẩu', true);?></span></h1></header>
				</div> <!--  end .title_center -->
			</div> <!--  end .title_right -->
		</div>
		<div class="des">
	     	<?php echo $this->Form->create('Member',array('url'=>array('controller'=>'members','action'=>'changePassword','lang'=>$lang),'inputDefaults'=>array('label'=>false,'div'=>false),'id'=>'form'));?>
	     		<div>
	     			<?php 
	     				echo $this->Form->label('password', __('Mật khẩu cũ', true).'<span>*</span>');
	     				echo $this->Form->input('password', array('type'=>'password', 'div'=>false, 'label'=>false, 'class'=>'larger','id'=>'password','required'=>'required'));
	     				if(!empty($a_errors_c['wrong_password'])) echo $this->Html->tag('span',__('Tài khoản này không đúng',true),array('class'=>'error'));
	     			?>
	     		</div>
	     		<div>
	     			<?php 
	     				echo $this->Form->label('re_password', __('nhập lại', true).'<span>*</span>');
	     				echo $this->Form->input('re_password', array('type'=>'password', 'div'=>false, 'label'=>false, 'class'=>'larger','required'=>'required'));
	     				if(!empty($a_errors_c['re_password'])) echo $this->Html->tag('span',__('Nhập lại mật khẩu không đúng',true),array('class'=>'error'));
	     			?>
	     		</div>
	     		<div>
	     			<?php 
	     				echo $this->Form->label('password_new', __('Mật khẩu mới', true).'<span>*</span>');
	     				echo $this->Form->input('password_new', array('type'=>'password', 'div'=>false, 'label'=>false, 'class'=>'larger','id'=>'password_new','required'=>'required'));
	     				if(!empty($a_errors_c['empty_password_new'])) echo $this->Html->tag('span',__('Mật khẩu mới không được trống',true),array('class'=>'error'));
	     				if(!empty($a_errors_c['strlen_password_new'])) echo $this->Html->tag('span',__('Mật khẩu phải nhiều hơn 5 ký tự',true),array('class'=>'error'));
	     			?>
	     		</div>
	     		<div>
	     			<?php 
	     				echo $this->Form->label('re_password_new', __('Nhập lại', true).'<span>*</span>');
	     				echo $this->Form->input('re_password_new', array('type'=>'password', 'div'=>false, 'label'=>false, 'class'=>'larger','required'=>'required'));
	     				if(!empty($a_errors_c['re_password_new'])) echo $this->Html->tag('span',__('Nhập lại mật khẩu mới không đúng',true),array('class'=>'error'));
	     			?>
	     		</div>
	     		<div class="submit">
	     			<p><span>*</span> Thông tin bắt buộc</p>
	     			<?php echo $this->Form->submit(__('Lưu thay đổi'), array('div'=>false))?>
	     		</div>
	     		<?php echo $this->Form->end();?>
		</div><!-- End .des -->
	
</article> <!--  end .box_content -->