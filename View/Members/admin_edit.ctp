<?php echo $this->Html->script(array('ckeditor/ckeditor','ckfinder/ckfinder'));?>


<div id="column_right">
	<!-- tab --> 
	<div id="action_top">
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo __('Thông tin',true)?></a></li>
   		</ul> <!-- end .tabs -->
    		
    	<ul class="action_top_2">
    		<li><?php echo $this->Html->link('&nbsp;',(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit','escape'=>false))?></li>
   		</ul> <!-- end .action_top_2 -->
	</div> <!--  end #action_top -->
	
	<div id="content">
		<?php  
			echo $this->Form->create('Member',array('type'=>'file','id'=>'form','url'=>array('action'=>'edit','?'=>array('url'=>(!empty($_GET['url']))?urldecode($_GET['url']):'')),'inputDefaults'=>array('label'=>false,'div'=>false)));
			echo $this->Form->input('id');
		?>
		
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="add column1">
					<tr>
						<th><?php echo $this->Form->label('name',__('Họ tên',true))?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('name',array('class'=>'larger'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('email',__('Email',true))?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('email',array('class'=>'larger','disabled'=>'disabled'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('gender',__('Giới tính',true))?></th>
						<td><?php echo $this->Form->input('gender',array('type'=>'select','options'=>array('Mr'=>'Mr','Ms'=>'Ms'),'class'=>'small','required'=>true))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('phone',__('Điện thoại',true))?></th>
						<td><?php echo $this->Form->input('phone',array('class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('address',__('Địa chỉ',true))?><span class="im">*</span></th>
						<td><?php echo $this->Form->input('address',array('class'=>'larger'))?></td>
					</tr>
					
					
					<tr>
						<th><?php echo $this->Form->label('status',__('Kích hoạt',true))?></th>
						<td><?php echo $this->Form->checkbox('status')?></td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->
			
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?></li>
				<li><?php echo $this->Form->submit(__('Lưu và Thoát',true),array('name'=>'save_exit','div'=>false))?></li>
				<li><?php echo $this->Html->link(__('Thoát',true),(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('class'=>'exit'))?></li>
			</ul> <!-- end .submit -->
			
		</div> <!-- end .tab_container -->
		
		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->