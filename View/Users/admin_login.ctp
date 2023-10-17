<div id="login">
	<?php
		echo $this->Session->flash();
		echo $this->Session->flash('auth');
	?>
	<?php echo $this->Form->create('User',array('inputDefaults'=>array('div'=>false)));?>
	<table>
		<tr>
			<td><?php echo $this->Form->input('username',array('label'=>__('Tài khoản',true),'class'=>'larger', 'autofocus' => true))?></td>
		</tr>
		<tr>
			<td><?php echo $this->Form->input('password',array('label'=>__('Mật khẩu',true),'class'=>'larger','value'=>''))?></td>
		</tr>
		<tr>
			<td><?php echo $this->Form->input('remember',array('type'=>'checkbox','label'=>' '.__('Ghi nhớ tài khoản',true)))?></td>
		</tr>
		<tr class="submit">
			<td>
				<?php
					echo $this->Form->submit(__('Đăng nhập',true),array('div'=>false));
					echo $this->Html->link(__('Quên mật khẩu',true),array('controller'=>'users', 'action'=>'forgetPassword'),array('title'=>__('Quên mật khẩu',true),'class'=>'tooltip forget'))
				?>
			</td>
		</tr>
	</table>
	<?php echo $this->Form->end()?>
	<a href="javascript:;" title="Công ty thiết kế web URL" class="design tooltip" rel="nofollow">URL.vn</a>
</div> <!-- end #login -->