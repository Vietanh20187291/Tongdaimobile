<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo __('Đăng nhập',true)?></h4>
			</div>
			<div class="modal-body">
				<?php echo $this->Form->create('Member',array('url'=>'javascript:;'), array('id'=>'form_login'));?>
				<div class="row">
					<?php 
						echo $this->Form->label('email',__('Email', true),array('for'=>'email_login'));
						echo $this->Form->input('email', array('label'=>false,'id'=>'email_login', 'div'=>false,'value'=>''));
					?>
				</div>
				<div class="row">
					<?php 
						echo $this->Form->label('password',__('Mật khẩu', true),array('for'=>'password_login'));
						echo $this->Form->input('password', array('label'=>false, 'id'=>'password_login','div'=>false, 'value'=>''));
					?>
				</div>
				<div class="row">
					<p class="text"><?php echo $this->Html->link(__('Quên mật khẩu', true),array('controller'=>'members','action'=>'forgetPassword', 'lang'=>$lang), array('title'=>__('Quên mật khẩu', true)));?></p>
				</div>
				<div class"row">
				<?php 
					echo $this->Form->submit(__('Đăng nhập',true), array('type'=>'submit','name'=>'login', 'div'=>false,'id'=>'login'));
					echo $this->Form->submit(__('Đăng ký',true), array('type'=>'button','name'=>'register', 'div'=>false, 'id'=>'register'));
				?>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>	
	
	
	
	
	
	
	
	
	
