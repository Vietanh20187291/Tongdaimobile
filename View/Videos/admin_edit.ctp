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
    		<li><?php echo $this->Html->link('&nbsp;',(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit','escape'=>false))?></li>
   		</ul> <!-- end .action_top_2 -->
	</div> <!--  end #action_top -->
	
	<div id="content">
		<?php  
			echo $this->Form->create('Video',array('id'=>'form','url'=>array('action'=>'edit','?'=>array('url'=>(!empty($_GET['url']))?urldecode($_GET['url']):'')),'inputDefaults'=>array('label'=>false,'div'=>false)));
			echo $this->Form->input('id');
		?>
		
		<div class="tab_container">
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thêm mới',true),array('name'=>'save_add','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu và Thoát',true),array('name'=>'save_exit','div'=>false))?><span></span></li>
			</ul> <!-- end .submit -->
			
			<div id="tab1" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo $this->Form->label('name',__('Tên',true))?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('name',array('class'=>'larger','onchange'=>'getFieldByName("Video")'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('video_category_id',__('Danh mục',true))?> <span class="im">*</span></th>
						<td>
							<?php 
								echo $this->Form->input('video_category_id',array('type'=>'select','options'=>$a_list_categories_c,'empty'=>'Chọn danh mục','class'=>'medium','required'=>true));
								echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Chọn danh mục hiển thị khác',true),'onclick'=>"more('more_category')",'class'=>'act add tooltip','escape'=>false));
								echo '('.count($this->request->data['Video']['category_other']).')';
								echo $this->Form->input('category_other',array('type'=>'select','options'=>$a_list_categories_c,'empty'=>__('Chọn danh mục hiển thị khác',true),'class'=>'medium','multiple'=>true,'size'=>8,'div'=>array('id'=>'more_category')));
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('youtube',__('Link Youtube',true))?> <span class="im">*</span></th>
						<td>
							<iframe width="300" height="169" src="http://www.youtube.com/embed/<?php echo $this->request->data['Video']['youtube']?>?rel=0" frameborder="0"></iframe> <br />
							<?php echo $this->Form->input('youtube',array('class'=>'larger','value'=>'http://youtu.be/'.$this->request->data['Video']['youtube']))?>
						</td>
					</tr>
					<?php if(!empty($oneweb_media['video']['display'])){?>
					<tr>
						<th><?php echo __('Vị trí hiển thị',true)?></th>
						<td class="display">
							<ul>
								<?php foreach($oneweb_media['video']['display'] as $key=>$val){?>
								<li>
									<?php 
										echo $this->Form->checkBox('pos_'.$key);
										echo $this->Form->label('pos_'.$key,__($val,true));
									?>
								</li>
								<?php }?>
							</ul> <!-- end .display -->
						</td>
					</tr>
					<?php }?>
					<tr>
						<td colspan="2">
							<div class="tab_container_2">
								<div id="tab21" class="tab_content_2">
									<?php 
										echo $this->Form->input('description', array('type'=> 'textarea','div'=>'description'));
										echo $this->CkEditor->create('Video.description',array('toolbar'=>'full'));	
									?>
								</div> <!-- end #tab21 -->
							</div> <!-- end .tab_container_2 -->	
						</td>
					</tr>
					<?php if(!empty($oneweb_seo)){?>
					<tr>
						<th><?php echo $this->Form->label('target',__('Target',true))?></th>
						<td><?php echo $this->Form->input('target',array('type'=>'select','options'=>array('_self'=>'_self','_blank'=>'_blank'),'class'=>'medium'))?></td>
					</tr>
					<?php }?>
					<tr>
						<th><?php echo $this->Form->label('status',__('Kích hoạt',true))?></th>
						<td><?php echo $this->Form->checkbox('status')?></td>
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
				<li><?php echo $this->Html->link(__('Thoát',true),(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('class'=>'exit'))?></li>
			</ul> <!-- end .submit -->
			
		</div> <!-- end .tab_container -->
		
		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->