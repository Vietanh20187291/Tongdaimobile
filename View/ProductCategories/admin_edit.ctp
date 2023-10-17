<?php echo $this->Html->script(array('ckeditor/ckeditor','ckfinder/ckfinder'));?>

<script type="text/javascript">
	//Xóa ảnh
	function delImg(id,field){
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxDelImage'))?>',
			data:'id='+id+'&field='+field,
			beforeSend:function(){
				$("#loading").show();
			},
			success:function(result){
				$("td#"+field+" a").fadeOut(200);
				$(".question").hide();
				$("#loading").hide();
			}
		})
	}
</script>

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
			echo $this->Form->create('ProductCategory',array('type'=>'file','id'=>'form','url'=>array('action'=>'edit','?'=>array('url'=>(!empty($_GET['url']))?urldecode($_GET['url']):'')),'inputDefaults'=>array('label'=>false,'div'=>false)));
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
						<td><?php echo $this->Form->input('name',array('class'=>'larger','onchange'=>'getFieldByName("ProductCategory")'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('parent_id',__('Danh mục cha',true))?></th>
						<td><?php echo $this->Form->input('parent_id',array('type'=>'select','options'=>$a_categories_c,'empty'=>__('Chọn danh mục cha',true),'class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('link',__('Liên kết',true))?></th>
						<td><?php echo $this->Form->input('link',array('class'=>'larger'))?></td>
					</tr>
					<tr<?php if(empty($oneweb_product['category_image'])) echo ' class="hidden"'?>>
						<th><?php echo $this->Form->label('image',__('Ảnh đại diện',true))?></th>
						<td id="image">
							<?php
								if(!empty($this->request->data['ProductCategory']['image'])){
									if(!empty($this->request->data['ProductCategory']['image'])) $img = '/timthumb.php?src='.$this->Html->url('/webroot/img/images/product_categories/').$this->request->data['ProductCategory']['image'];
									$img_small = $img."&h=90&w=90&zc=2";
									$img_larger = $img."&h=300&w=300&zc=2";

									echo $this->Html->link($this->Html->image($img_small,array('alt'=>$this->request->data['ProductCategory']['name'])),$img_larger,array('title'=>$this->request->data['ProductCategory']['name'],'class'=>'preview','target'=>'_blank','escape'=>false));
									echo $this->Html->link('&nbsp;',"javascript:delImg({$this->request->data['ProductCategory']['id']},'image');",array('title'=>'Xóa ảnh','class'=>'act delete tooltip ask','escape'=>false)).'<br />';
								}
								echo $this->Form->input('image',array('type'=>'file'));
								echo $this->Html->tag('span',__('Kích thước',true).": {$oneweb_product['size']['category'][0]} x {$oneweb_product['size']['category'][1]} (px)",array('class'=>'size_img'));
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('icon',__('Icon',true))?></th>
						<td id="icon">
							<?php
								if(!empty($this->request->data['ProductCategory']['icon'])){
									if(!empty($this->request->data['ProductCategory']['icon'])) $img = '/timthumb.php?src='.$this->Html->url('/webroot/img/images/product_categories/').$this->request->data['ProductCategory']['icon'];
									$img_small = $img."&h=90&w=90&zc=2";
									$img_larger = $img."&h=300&w=300&zc=2";

									echo $this->Html->link($this->Html->image($img_small,array('alt'=>$this->request->data['ProductCategory']['name'])),$img_larger,array('title'=>$this->request->data['ProductCategory']['name'],'class'=>'preview','target'=>'_blank','escape'=>false));
									echo $this->Html->link('&nbsp;',"javascript:delImg({$this->request->data['ProductCategory']['id']},'icon');",array('title'=>'Xóa ảnh','class'=>'act delete tooltip ask','escape'=>false)).'<br />';
								}
								echo $this->Form->input('icon',array('type'=>'file'));
								echo $this->Html->tag('span',__('Kích thước',true).": {$oneweb_product['size']['icon'][0]} x {$oneweb_product['size']['icon'][1]} (px)",array('class'=>'size_img'));
							?>
						</td>
					</tr>
					<tr<?php if(empty($oneweb_product['category_banner'])) echo ' class="hidden"'?>>
						<th><?php echo $this->Form->label('banner',__('Banner',true))?></th>
						<td id="banner">
							<?php
								if(!empty($this->request->data['ProductCategory']['banner'])){
									if(!empty($this->request->data['ProductCategory']['banner'])) $img = '/timthumb.php?src='.$this->Html->url('/webroot/img/images/product_categories/').$this->request->data['ProductCategory']['banner'];
									$img_small = $img."&h=60&w=200&zc=2";
									$img_larger = $img."&h=150&w=500&zc=2";

									echo $this->Html->link($this->Html->image($img_small,array('alt'=>$this->request->data['ProductCategory']['name'])),$img_larger,array('title'=>$this->request->data['ProductCategory']['name'],'class'=>'preview','target'=>'_blank','escape'=>false));
									echo $this->Html->link('&nbsp;',"javascript:delImg({$this->request->data['ProductCategory']['id']},'banner');",array('title'=>'Xóa ảnh','class'=>'act delete tooltip ask','escape'=>false)).'<br />';
								}
								echo $this->Form->input('banner',array('type'=>'file'));
								echo $this->Html->tag('span',__('Kích thước',true).": {$oneweb_product['size']['category_banner'][0]} x {$oneweb_product['size']['category_banner'][1]} (px)",array('class'=>'size_img'));
							?>
						</td>
					</tr>
					<tr<?php if(empty($oneweb_product['category_banner'])) echo ' class="hidden"'?>>
						<th><?php echo $this->Form->label('banner_link',__('Liên kết banner',true))?></th>
						<td><?php echo $this->Form->input('banner_link',array('class'=>'larger'))?></td>
					</tr>
					<?php if(!empty($oneweb_product['category_description'])){?>
					<tr>
						<td colspan="2">
							<div class="tab_container_2">
								<div id="tab21" class="tab_content_2">
									<?php
										echo $this->Form->input('description', array('type'=> 'textarea','div'=>'description'));
										echo $this->CkEditor->create('ProductCategory.description',array('toolbar'=>'full'));
									?>
								</div> <!-- end #tab21 -->
							</div> <!-- end .tab_container_2 -->
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<?php
								echo $this->Form->input('description2', array('type'=> 'textarea','div'=>'description'));
								echo $this->CkEditor->create('ProductCategory.description2',array('toolbar'=>'full'));
							?>
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
						<th><?php echo $this->Form->label('show_in_home',__('Hiển thị trên trang chủ',true))?></th>
						<td><?php echo $this->Form->checkbox('show_in_home')?></td>
					</tr>
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