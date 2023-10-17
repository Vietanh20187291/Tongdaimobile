<?php 
$lang = $this->Session->read('lang');
?>
<div id="forget_password">
	<h2 class="title"><?php echo __('Quên mật khẩu', true); ?></h2>
	<?php 
		echo $this->Session->flash();
		echo $this->Session->flash('auth'); 
	?>
	<?php echo $this->Form->create('User',array('inputDefaults'=>array('div'=>false)));?>
	<table>
		<tr>
			<td><?php echo $this->Form->input('email',array('label'=>__('Email',true),'class'=>'larger','required'=>'required'))?></td>
		</tr>
		<tr>
			<td>
				<?php 
					echo $this->Form->label('captcha',__('Mã xác nhận',true).'<span class="im">*</span>');
					echo $this->Recaptcha->display(array('recaptchaOptions'=>array(
																			 		'theme'=>'white',			//red, white, blackglass, clean
																			 		'lang'=>$lang
																			 	)));
				?>
			</td>
		</tr>
		<tr class="submit">
			<td>
				<?php 
					echo $this->Form->submit(__('Gửi yêu cầu',true),array('div'=>false));
				?>
			</td>
		</tr>
	</table>
	<?php echo $this->Form->end()?>
</div> <!-- end #login -->