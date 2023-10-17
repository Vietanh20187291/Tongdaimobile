<div id="column_right">
	<div id="action_top">
		<div id="action_1" class="box_select">
			<ul>
				<li class="active"><?php echo __('Kích hoạt',true)?></li>
				<li class="unactive"><?php echo __('Bỏ kích hoạt',true)?></li>
				<li class="trashes"><?php echo __('Thùng rác',true)?></li>
			</ul>
		</div> <!--  end .box_select -->
		
		<?php 
		echo $this->Form->create('Product',array('type'=>'get','url'=>array('controller'=>'products','action'=>'index'),'inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('position',array('type'=>'hidden','value'=>(!empty($_GET['position']))?$_GET['position']:''));
		?>
		<ul class="search">
			<li><?php echo $this->Form->input('category_id',array('type'=>'select','options'=>$a_product_categories_tree_c,'empty'=>__('Chọn danh mục',true),'class'=>'medium'))?></li>
			<li<?php if(empty($oneweb_product['maker'])) echo ' class="hidden"'?>><?php echo $this->Form->input('maker_id',array('type'=>'select','options'=>$a_product_makers_c,'empty'=>__('Chọn hãng sản xuất',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tìm kiếm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tìm kiếm',true).'";}','onfocus'=>'if (this.value=="'.__('Tìm kiếm',true).'") { this.value=""; }'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('ProductCategory',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<?php if(!empty($oneweb_product['category_image'])){?>
				<th class="small center"><?php echo __('Ảnh',true)?></th>
				<?php }?>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<th class="small center"><?php echo __('Sắp xếp',true)?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_product_categories_c as $val){
				$item_category = $val['ProductCategory'];
				$item_parent_category = $val['ParentProductCategory'];
				
				$url_edit = array('controller'=>'product_categories','action'=>'edit',$item_category['id'],'?'=>array('url'=>$current_url_c));
				
				if(empty($item_category['link'])){
					$url_view = array('controller'=>'products','action'=>'index','lang'=>$item_category['lang']);
					$tmp = explode(',', $item_category['path']);
					for($i=0;$i<count($tmp);$i++){
						$url_view['slug'.$i]=$tmp[$i];
					}
					$url_view['admin'] = false;
				}else $url_view = $item_category['link'];
				
				if(!empty($item_category['image'])) $img = '/timthumb.php?src='.$this->Html->url('/webroot/img/images/product_categories/').$item_category['image'];
			
				$img_small = $img."&h=30&w=30&zc=2";
				$img_larger = $img."&h=100&w=100&zc=2";
			?>
			<tr id="<?php echo 'item_'.$item_category['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_category['id']?>" /></td>
				<?php if(!empty($oneweb_product['category_image'])){?>
				<td class="center"><?php echo $this->Html->link($this->Html->image($img_small,array('alt'=>$item_category['name'])),$img_larger,array('title'=>$item_category['name'],'class'=>'preview','escape'=>false))?></td>
				<?php }?>
				<td>
					<?php
						$tmp = explode('_', $a_product_categories_tree_c[$item_category['id']]);
						for($i=0;$i<count($tmp);$i++){
							if(!empty($tmp[$i])) break;
						}

						echo $this->Html->link('&nbsp;',array('action'=>'index','?'=>array('parent_id'=>$item_category['id'])),array('class'=>"act folder sub_$i",'escape'=>false));
						echo $this->Html->link($this->Text->truncate($item_category['name'],50,array('extact'=>false)),$url_edit,array('title'=>$item_category['name'],'class'=>'tooltip'));
						
						$a_counter = unserialize($item_category['counter']);
						echo ' <span class="counter">[ '.$this->Html->link(number_format($a_counter['cate']),array('action'=>'index','?'=>array('parent_id'=>$item_category['id'])),array('title'=>number_format($a_counter['cate']).' '.__('danh mục con',true),'class'=>'tooltip'))
								.' - '.$this->Html->link(number_format($a_counter['item']),array('controller'=>'products','action'=>'index','?'=>array('category_id'=>$item_category['id'])),array('title'=>number_format($a_counter['item']).' '.__('sản phẩm',true),'class'=>'tooltip'))
								 .' ]</span>';
						
						if((!empty($item_parent_category['id']) && $item_parent_category['id']!=$item_category['parent_id']) 
							||(empty($item_parent_category['id']) && !empty($item_category['parent_id']))
						) echo ' <span class="error">**&nbsp;ERROR&nbsp;**</span>';
					?>
				</td>
				<td class="center">
					<?php 
						echo $this->Html->link('&nbsp;',array('action'=>'moveUp',$item_category['id'],1),array('title'=>__('Lên',true),'class'=>'act up tooltip','escape'=>false));
						echo $this->Html->link('&nbsp;',array('action'=>'moveDown',$item_category['id'],1),array('title'=>__('Xuống',true),'class'=>'act down tooltip','escape'=>false));
					?>
				</td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item_category['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_category['status']==1)?'active':'unactive')));?></td>
				<td class="center">
					<?php 
						echo $this->Html->link('&nbsp;',$url_view,array('title'=>__('Xem',true),'class'=>'act '.(empty($item_category['link'])?'view':'view_link'),'escape'=>false,'target'=>'_blank'));
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',array('action'=>'trash',$item_category['id']),array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->