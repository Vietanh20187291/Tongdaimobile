<div id="column_right">
	<!-- tab --> 
	<div id="action_top">
		<ul class="tabs">
    		<li><a href="#tab1"><?php echo __('Thông tin',true)?></a></li>
    		<?php if(!empty($oneweb_seo)){?>
    		<li><a href="#tab2"><?php echo __('SEO',true)?></a></li>
    		<?php }?>
    	</ul> <!-- end .tabs -->
    		
    	<ul class="action_top_2">
    		<li><?php echo $this->Html->link('&nbsp;',array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit','escape'=>false))?></li>
    	</ul> <!-- end .action_top_2 -->
	</div> <!--  end #action_top -->
	
	<div id="content">
		<?php echo $this->Form->create('DocumentCategory',array('id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)))?>
		
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo $this->Form->label('name',__('Tên',true))?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('name',array('class'=>'larger','onchange'=>'getFieldByName("DocumentCategory")'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('sort',__('Sắp xếp',true))?></th>
						<td><?php echo $this->Form->input('sort',array('class'=>'small','required'=>false))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('status',__('Kích hoạt',true))?></th>
						<td><?php echo $this->Form->checkbox('status',array('checked'=>true))?></td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->
			
			<?php if(!empty($oneweb_seo)){?>
			<div id="tab2" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo $this->Form->label('slug',__('Slug',true))?></th>
						<td><?php echo $this->Form->input('slug',array('class'=>'larger','required'=>false))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('meta_title',__('Meta title',true))?></th>
						<td><?php echo $this->Form->input('meta_title',array('class'=>'larger','required'=>false))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('meta_keyword',__('Meta keyword',true))?></th>
						<td><?php echo $this->Form->input('meta_keyword',array('class'=>'larger'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('meta_description',__('Meta description',true))?></th>
						<td><?php echo $this->Form->input('meta_description',array('class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('meta_robots',__('Meta robots',true))?></th>
						<td><?php echo $this->Form->input('meta_robots',array('type'=>'select','options'=>array('index,follow'=>'index,follow','noindex,nofollow'=>'noindex,nofollow','index,nofollow'=>'index,nofollow','noindex,follow'=>'noindex,follow'),'class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('rel',__('Rel',true))?></th>
						<td><?php echo $this->Form->input('rel',array('type'=>'select','options'=>array('dofollow'=>'dofollow','nofollow'=>'nofollow'),'class'=>'medium'))?></td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab2 -->
			<?php }?>
			
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