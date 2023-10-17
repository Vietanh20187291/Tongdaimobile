<?php echo $this->Html->script(array('ckeditor/ckeditor','ckfinder/ckfinder'));?>

<script type="text/javascript"> 
	//Xóa ảnh
	function delImg(id){
		$.ajax({
			type: 'post',
			url : '<?php echo $this->Html->url(array('action'=>'ajaxDelImg'))?>',
			data: 'id='+id,
			beforeSend:function(){
				$("#loading").show();
			},
			success:function(result){
				if(result) $("#img_"+id).fadeOut();
				$("#loading").hide();
			}
		})
	};

	//Sửa tên ảnh ở bảng gallery_images
	function changeNameImage(val,id){
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxChangeNameImage'))?>',
			data:'id='+id+'&name='+val,
			beforeSend:function(){
				$("#loading").show();
			},
			success:function(result){
				if(result) $("#loading").hide();
			}
		})
	}
</script>

<div id="column_right">
	<!-- tab --> 
	<div id="action_top">
		<ul class="tabs">
    		<li><a href="#tab1"><?php echo __('Hình ảnh',true)?></a></li>
   			<li><a href="#tab2"><?php echo __('Thông tin',true)?></a></li>
    		<?php if(!empty($oneweb_seo)){?>
    		<li><a href="#tab3"><?php echo __('SEO',true)?></a></li>
    		<?php }?>
   		</ul> <!-- end .tabs -->
    		
    	<ul class="action_top_2">
    		<li><?php echo $this->Html->link('&nbsp;',(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit','escape'=>false))?></li>
   		</ul> <!-- end .action_top_2 -->
	</div> <!--  end #action_top -->
	
	<div id="content">
		<?php  
			echo $this->Form->create('Gallery',array('type'=>'file','id'=>'form','url'=>array('action'=>'edit','?'=>array('url'=>(!empty($_GET['url']))?urldecode($_GET['url']):'')),'inputDefaults'=>array('label'=>false,'div'=>false)));
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
					<?php if(!empty($this->request->data['GalleryImage'])){?>
					<tr>
						<td colspan="2">
							<?php 
							foreach($this->request->data['GalleryImage'] as $val){
								if(!empty($val['image'])) $img = '/timthumb.php?src='.$this->Html->url('/webroot/img/images/galleries/').$val['image'];
								
								$img_small = $img."&h=100&w=100&zc=2";
								$img_larger = $img."&h=300&w=300&zc=2";
							?>
							<div id="img_<?php echo $val['id']?>" class="more_image">
								<?php 
									echo $this->Html->link($this->Html->image($img_small,array('alt'=>$val['name'])),$img_larger,array('title'=>$val['name'],'class'=>'preview','target'=>'_blank','escape'=>false));
									echo $this->Html->link('&nbsp;',"javascript:delImg({$val['id']});",array('title'=>__('Xóa ảnh',true),'class'=>'act delete tooltip','escape'=>false));
									echo $this->Form->input("GalleryImageOld.name.{$val['id']}",array('value'=>$val['name'],'onchange'=>"changeNameImage(this.value,{$val['id']})"));
								?>
							</div> <!-- end .more_image -->
							<?php }?>
						</td>
					</tr>
					<?php }?>
					<tr>
						<th><?php echo $this->Form->label('GalleryImage',__('Thêm ảnh',true))?></th>
						<td>
							<?php 
								echo $this->Form->input('GalleryImage',array('type'=>'file','name'=>'data[GalleryImage][]','multiple'=>true));
								echo $this->Html->tag('span',__('Kích thước',true).": {$oneweb_media['size']['gallery'][0]} x {$oneweb_media['size']['gallery'][1]} (px)",array('class'=>'size_img'));
							?>
						</td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->
			
			<div id="tab2" class="tab_content">
				<table class="add">
					<tr>
						<th><?php echo $this->Form->label('name',__('Tên',true))?> <span class="im">*</span></th>
						<td><?php echo $this->Form->input('name',array('class'=>'larger','onchange'=>'getFieldByName("Gallery")'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('gallery_category_id',__('Danh mục',true))?> <span class="im">*</span></th>
						<td>
							<?php 
								echo $this->Form->input('gallery_category_id',array('type'=>'select','options'=>$a_list_categories_c,'empty'=>__('Chọn danh mục',true),'class'=>'medium','required'=>true));
								echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Chọn danh mục hiển thị khác',true),'onclick'=>"more('more_category')",'class'=>'act add tooltip','escape'=>false));
								echo '('.count($this->request->data['Gallery']['category_other']).')';
								echo $this->Form->input('category_other',array('type'=>'select','options'=>$a_list_categories_c,'empty'=>__('Chọn danh mục hiển thị khác',true),'class'=>'medium','multiple'=>true,'size'=>8,'div'=>array('id'=>'more_category')));
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('image',__('Ảnh đại diện',true))?></th>
						<td>
							<?php 
								if(!empty($this->request->data['Gallery']['image'])) $img = '/timthumb.php?src='.$this->Html->url('/webroot/img/images/galleries/').$this->request->data['Gallery']['image'];
								
								$img_small = $img."&h=90&w=90&zc=2";
								$img_larger = $img."&h=300&w=300&zc=2";
								
								echo $this->Html->link($this->Html->image($img_small,array('alt'=>$this->request->data['Gallery']['name'])),$img_larger,array('title'=>$this->request->data['Gallery']['name'],'class'=>'preview','target'=>'_blank','escape'=>false)).'<br />';
								echo $this->Form->input('image',array('type'=>'file'));
								echo $this->Html->tag('span',__('Kích thước',true).": {$oneweb_media['size']['gallery'][0]} x {$oneweb_media['size']['gallery'][1]} (px)",array('class'=>'size_img'));
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="tab_container_2">
								<div id="tab21" class="tab_content_2">
									<?php 
										echo $this->Form->input('description', array('type'=> 'textarea','div'=>'description'));
										echo $this->CkEditor->create('Gallery.description',array('toolbar'=>'full'));	
									?>
								</div> <!-- end #tab21 -->
							</div> <!-- end .tab_container_2 -->	
						</td>
					</tr>
					<?php if(!empty($oneweb_media['gallery']['display'])){?>
					<tr>
						<th><?php echo __('Vị trí hiển thị',true)?></th>
						<td class="display">
							<ul>
								<?php foreach($oneweb_media['gallery']['display'] as $key=>$val){?>
								<li>
									<?php 
										echo $this->Form->checkBox('pos_'.$key);
										echo $this->Form->label('pos_'.$key,$val);
									?>
								</li>
								<?php }?>
							</ul> <!-- end .display -->
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
						<td><?php echo $this->Form->checkbox('status')?></td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab2 -->
			<?php if(!empty($oneweb_seo)){?>
			<div id="tab3" class="tab_content">
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
			</div> <!-- end #tab3 -->
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