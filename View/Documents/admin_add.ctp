<?php echo $this->Html->script(array('ckeditor/ckeditor','ckfinder/ckfinder'));?>

<script type="text/javascript"> 
  $(document).ready(function(){
    //Thiết lập kiểu file up lên
    $("#DocumentType").change(function(){
		if($(this).val()=='link'){
			$("#d_link").show();
			$("#d_file").hide();
		}else{
			$("#d_link").hide();
			$("#d_file").show();
		}
    })

    if($("#DocumentType").val()=='link'){
		$("#d_link").show();
		$("#d_file").hide();
	}else{
		$("#d_link").hide();
		$("#d_file").show();
	}
});
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
		<?php echo $this->Form->create('Document',array('type'=>'file','id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)))?>
		
		<div class="tab_container">
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thêm mới',true),array('name'=>'save_add','div'=>false))?><span></span></li>
				<li><?php echo $this->Form->submit(__('Lưu và Thoát',true),array('name'=>'save_exit','div'=>false))?><span></span></li>
			</ul> <!-- end .submit -->
			
			<div id="tab1" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo $this->Form->label('name',__('Tiêu đề',true))?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('name',array('class'=>'larger'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('document_category_id',__('Danh mục',true))?> <span class="im">*</span></th>
						<td>
							<?php 
								echo $this->Form->input('document_category_id',array('type'=>'select','options'=>$a_list_categories_c,'empty'=>'Chọn danh mục','class'=>'medium','required'=>true));
								echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Chọn danh mục hiển thị khác',true),'onclick'=>"more('more_category')",'class'=>'act add tooltip','escape'=>false));
								echo $this->Form->input('category_other',array('type'=>'select','options'=>$a_list_categories_c,'empty'=>__('Chọn danh mục hiển thị khác',true),'class'=>'medium','multiple'=>true,'size'=>8,'div'=>array('id'=>'more_category')));
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('type',__('Kiểu file',true))?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('type',array('type'=>'select','options'=>array('file'=>__('Upload lên server',true),'link'=>__('Link từ bên ngoài',true)),'class'=>'medium'))?></td>
					</tr>
					<tr id="d_link">
						<th><?php echo $this->Form->label('link',__('Link',true))?> <span class="im">*</span></th>
						<td>
							<?php 
								echo $this->Form->input('link',array('class'=>'larger','value'=>'http://'));
								if(!empty($a_errors_c['link'])) echo $this->Form->label('link',__('This field is required.',true),array('class'=>'error'));
							?>
						</td>
					</tr>
					<tr id="d_file">
						<th><?php echo $this->Form->label('file',__('Tệp tin',true))?> <span class="im">*</span></th>
						<td>
							<?php 
								echo $this->Form->input('file',array('type'=>'file','required'=>true));
								if(!empty($a_errors_c['file'])) echo $this->Form->label('file',__('This field is required.',true),array('class'=>'error'));
								elseif(!empty($a_errors_c['ext'])) echo $this->Form->label('file',__('Sai định dạng tệp tin',true),array('class'=>'error'));
								
								echo $this->Html->tag('p',implode(', ', $a_exts_c));
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="tab_container_2">
								<div id="tab21" class="tab_content_2">
									<?php 
										echo $this->Form->input('description', array('type'=> 'textarea','div'=>'description'));
										echo $this->CkEditor->create('Document.description',array('toolbar'=>'user'));	
									?>
								</div> <!-- end #tab21 -->
							</div> <!-- end .tab_container_2 -->	
						</td>
					</tr>
					<?php if(!empty($oneweb_media['document']['display'])){?>
					<tr>
						<th><?php echo __('Vị trí hiển thị',true)?></th>
						<td class="display">
							<ul>
								<?php foreach($oneweb_media['document']['display'] as $key=>$val){?>
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