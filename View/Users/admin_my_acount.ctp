<?php echo $this->Html->script('admin/validate');?>

<script type="text/javascript"> 
  $(document).ready(function(){
    $("#form").validate({
    	rules: {
    		"data[User][name]":"required",
    		"data[User][username]":"required",
    		"data[User][group_id]":"required"
    	}
    });
});
</script>

<div id="column_right">
	<!-- tab --> 
	<div id="action_top">
		<ul class="tabs">
    		<li><a href="#tab1">Thông tin</a></li>
    	</ul> <!-- end .tabs -->
    		
	</div> <!-- end #action_top -->
	
	<div id="content">
		<?php  
			echo $this->Form->create('User',array('id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)));
			echo $this->Form->input('id');
		?>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo $this->Form->label('username','Tài khoản')?> <span class="im">*</span></th>
						<td><?php echo $this->request->data['User']['username']?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('name','Họ tên')?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('name',array('class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('password_old','Mật khẩu cũ')?></th>
						<td><?php echo $this->Form->input('password_old',array('type'=>'password','class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('password','Mật khẩu mới')?></th>
						<td><?php echo $this->Form->input('password',array('class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('password_confirm','Nhập lại mật khẩu')?></th>
						<td><?php echo $this->Form->input('password_confirm',array('type'=>'password','class'=>'medium'))?></td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->
			
			<ul class="submit">
				<li><?php echo $this->Form->submit('LƯU',array('name'=>'save','div'=>false))?></li>
				<li><?php echo $this->Html->link('Thoát',array('action'=>'index'),array('title'=>'Thoát','class'=>'exit'))?></li>
			</ul> <!-- end .submit -->
			
		</div> <!-- end .tab_container -->
		
		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->