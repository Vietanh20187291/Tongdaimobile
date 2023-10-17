
<div class="box_content forget_password">
	<div class="title">
		<div class="title_right">
			<div class="title_center">
				<h1><span><?php echo __('Quên mật khẩu',true)?></span></h1>
			</div> <!--  end .title_center -->
		</div> <!--  end .title_right -->
	</div> <!--  end .title -->
	
	<div class="des">
		
		<div class="form_register">
			 <?php echo $this->Form->create('Member',array('url'=>array('controller'=>'members','action'=>'forgetPassword','lang'=>$lang),'inputDefaults'=>array('label'=>false,'div'=>false),'id'=>'form')); ?>
			<div class="error">
				<span class="error_register">
				</span>
			</div>
			
			<div class="row">
				<?php 
					echo $this->Form->label('email',__('Email',true).'<span class="im">*</span>');
					echo $this->Form->input('email',array('type'=>'email'));
					if(!empty($a_errors_c['empty_email'])) echo $this->Html->tag('span',__('Email không được để trống',true),array('class'=>'error'));
				?>
			</div>
			<div class="row">
				<?php 
					echo $this->Form->label('captcha',__('Mã xác nhận',true).'<span class="im">*</span>');
					echo $this->Recaptcha->display(array('recaptchaOptions'=>array(
																			 		'theme'=>'white',			//red, white, blackglass, clean
																			 		'lang'=>"$lang"
																			 	)));
				?>
			</div>
			<div class="row submit">
				<?php echo $this->Form->submit(__('Gửi',true),array('div'=>false))?>
			</div>
			
			<?php echo $this->Form->end();?>
		</div> <!-- end .form -->
		
		<div class="clear"></div>
	</div> <!--  end .des -->
			
	<div class="top"></div>
	<div class="bottom"></div>
</div> <!--  end .box_content -->