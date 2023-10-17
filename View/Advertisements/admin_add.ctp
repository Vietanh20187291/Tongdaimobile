<?php echo $this->Html->script(array('ckeditor/ckeditor','ckfinder/ckfinder'));?>

<script type="text/javascript">
</script>

<div id="column_right">
	<!-- tab -->
	<div id="action_top">
		<ul class="tabs">
    		<li><a href="#tab1"><?php echo __('Thông tin',true)?></a></li>
    	</ul> <!-- end .tabs -->

    	<ul class="action_top_2">
    		<li><?php echo $this->Html->link('&nbsp;',array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit','escape'=>false))?></li>
    	</ul> <!-- end .action_top_2 -->
	</div> <!--  end #action_top -->

	<div id="content">
		<?php echo $this->Form->create('Advertisement',array('type'=>'file','id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)))?>

		<div class="tab_container">
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thêm mới',true),array('name'=>'save_add','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu và Thoát',true),array('name'=>'save_exit','div'=>false))?><span></span></li>
			</ul> <!-- end .submit -->

			<div id="tab1" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo $this->Form->label('name','Tên')?> <span class="im">*</span></th>
						<td>
						<?php echo $this->Form->input('name',array('type'=>'text','class'=>'medium'))?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('position','Loại quảng cáo')?> <span class="im">*</span></th>
						<td>
							<?php
								echo $this->Form->input('position',array('type'=>'select','options'=>$oneweb_advertisement['position'],'empty'=>'Chọn vị trí hiển thị','class'=>'medium','required'=>true));
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('content','Nội dung')?></th>
						<td><?php echo $this->Form->input('content',array('type'=>'textarea','class'=>'larger'));
						?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('status',__('Kích hoạt',true))?></th>
						<td><?php echo $this->Form->checkbox('status',array('checked'=>true))?></td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->

			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thêm mới',true),array('name'=>'save_add','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thoát',true),array('name'=>'save_exit','div'=>false))?><span></span></li>
				<li><?php echo $this->Html->link(__('Thoát',true),array('action'=>'index'),array('class'=>'exit'))?></li>
			</ul> <!-- end .submit -->

		</div> <!-- end .tab_container -->

		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->