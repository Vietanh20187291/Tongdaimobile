<div id="column_right">
	<!-- tab -->
	<div id="action_top">
		<ul class="tabs">
				<li><a href="#tab1">Thông tin</a></li>
			</ul> <!-- end .tabs -->

			<ul class="action_top_2">
				<li><?php echo $this->Html->link('&nbsp;',array('action'=>'index'),array('title'=>'Thoát','class'=>'exit','escape'=>false))?></li>
			</ul> <!-- end .action_top_2 -->
	</div> <!-- end #action_top -->

	<div id="content">
		<?php echo $this->Form->create('User',array('id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)))?>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo $this->Form->label('username','Tài khoản')?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('username',array('class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('name','Họ tên')?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('name',array('class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('email','Email')?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('email',array('class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('password','Mật khẩu')?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('password',array('type'=>'password','class'=>'medium','required'=>true))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('password_confirm','Nhập lại mật khẩu')?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('password_confirm',array('type'=>'password','class'=>'medium','required'=>true))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('group_id','Nhóm')?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('group_id',array('type'=>'select','options'=>array('1'=>'admin', '2'=>'staff'),'empty'=>'Chọn nhóm','class'=>'medium','required'=>true))?></td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->

			<ul class="submit">
				<li><?php echo $this->Form->submit('LƯU',array('name'=>'save','div'=>false))?></li>
				<li><?php echo $this->Form->submit('LƯU & THOÁT',array('name'=>'save_exit','div'=>false))?></li>
				<li><?php echo $this->Html->link('Thoát',array('action'=>'index'),array('title'=>'Thoát','class'=>'exit'))?></li>
			</ul> <!-- end .submit -->

		</div> <!-- end .tab_container -->

		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->