<?php echo $this->Html->script(array('ckeditor/ckeditor','ckfinder/ckfinder'));?>

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
		<?php echo $this->Form->create('ProductMaker',array('type'=>'file','id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)))?>
		
		<div class="tab_container">
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thêm mới',true),array('name'=>'save_add','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu và Thoát',true),array('name'=>'save_exit','div'=>false))?><span></span></li>
			</ul> <!-- end .submit -->
			
			<div id="tab1" class="tab_content">
				<table class="add column1">
					<tr>
						<th><?php echo $this->Form->label('name',__('Tên hãng',true))?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('name',array('class'=>'larger','onchange'=>'getFieldByName("ProductMaker")'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('link',__('Liên kết',true))?></th>
						<td><?php echo $this->Form->input('link',array('class'=>'larger'))?></td>
					</tr>
					<?php if(!empty($oneweb_product['maker_image'])){?>
					<tr>
						<th><?php echo $this->Form->label('image',__('Ảnh đại diện',true))?></th>
						<td>
							<?php 
								echo $this->Form->input('image',array('type'=>'file'));
								echo $this->Html->tag('span',__('Kích thước',true).": {$oneweb_product['size']['maker'][0]} x {$oneweb_product['size']['maker'][1]} (px)",array('class'=>'size_img'));
							?>
						</td>
					</tr>
					<?php }if(!empty($oneweb_product['maker_banner'])){?>
					<tr>
						<th><?php echo $this->Form->label('banner','Banner')?></th>
						<td>
							<?php 
								echo $this->Form->input('banner',array('type'=>'file'));
								echo $this->Html->tag('span',__('Kích thước',true).": {$oneweb_product['size']['maker_banner'][0]} x {$oneweb_product['size']['category_banner'][1]} (px)",array('class'=>'size_img'));
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('banner_link',__('Liên kết banner',true))?></th>
						<td><?php echo $this->Form->input('banner_link',array('class'=>'larger'))?></td>
					</tr>
					<?php }if(!empty($oneweb_product['maker_description'])){?>
					<tr>
						<td colspan="2">
							<div class="tab_container_2">
								<div id="tab21" class="tab_content_2">
									<?php 
										echo $this->Form->input('description', array('type'=> 'textarea','div'=>'description'));
										echo $this->CkEditor->create('ProductMaker.description',array('toolbar'=>'full'));	
									?>
								</div> <!-- end #tab21 -->
							</div> <!-- end .tab_container_2 -->	
						</td>
					</tr>
					<?php }?>
					<?php if(!empty($oneweb_seo)){?>
					<tr>
						<th><?php echo $this->Form->label('target',__('Target',true))?></th>
						<td><?php echo $this->Form->input('target',array('type'=>'select','options'=>array('_self'=>'_self','_blank'=>'_blank'),'class'=>'medium'))?></td>
					</tr>
					<?php }?>
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