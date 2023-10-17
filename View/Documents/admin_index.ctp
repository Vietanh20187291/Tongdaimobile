<?php if(!empty($_GET['keyword'])) echo $this->Html->script('admin/highlight');?>

<script type="text/javascript">
	//submit action top
	$(document).ready(function(){
		$("#action_1").find('li').click(function(){
			var c = confirm("<?php echo __('Bạn có chắc chắn muốn thực hiện hành động này',true)?>?");
			if(c==true){
				val = $(this).attr('class');
				$("#action").val(val);
				document.form.submit();
			}
		});
	});

	//Thay đổi trạng thái
	function changeStatus(field,id){
		$.ajax({
				type:'post',
				url: '<?php echo $this->Html->url(array('action'=>'ajaxChangeStatus'));?>',
				data: 'field='+field+'&id='+id,
				beforeSend: function(){
					$("#loading").show();
				},
				dataType:'json',
				success: function(result){
					$("#item_"+id+" ."+field+" a.act").removeClass(result.remove);
					$("#item_"+id+" ."+field+" a.act").addClass(result.add);
					if(field!='status') $("#item_"+id+" a.display").text(result.count);
					$("#loading").hide();

				}
			});
	}

	//Xóa document
	function trashItem(id){
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxTrashItem'))?>',
			data:'id='+id,
			beforeSend:function(){		
				$("#loading").show();
			},
			success: function(result){
				if(result){
					$("#item_"+id).fadeOut(110);
				}else{
					$(".question").fadeOut();
					alert('<?php echo __('Có lỗi, vui lòng thử lại',true)?>');
				}
				$("#loading").hide();
			}
		});
	}

	//Sắp xếp lại document
	function changeSort(val,field,id){
		if(val<1) val=1;
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxChangeSort'))?>',
			data:'val='+val+'&field='+field+'&id='+id,
			beforeSend:function(){
				$("#loading").show();
			},
			success:function(){
				$("#loading").hide();
			}
		});
	}
</script>
	
<div id="column_right">
	<div id="action_top">
		<div id="action_1" class="box_select">
			<ul>
				<li class="active"><?php echo __('Kích hoạt',true)?></li>
				<li class="unactive"><?php echo __('Bỏ kích hoạt',true)?></li>
				<li class="trashes"><?php echo __('Thùng rác',true)?></li>
			</ul>
		</div> <!--  end .box_select -->
		
		<?php echo $this->Form->create('Document',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('category_id',array('type'=>'select','options'=>$a_list_categories_c,'empty'=>__('Chọn danh mục',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tìm kiếm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tìm kiếm',true).'";}','onfocus'=>'if (this.value=="'.__('Tìm kiếm',true).'") { this.value=""; }','id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('tài liệu',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('Document',array('type'=>'document','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('DocumentCategory.name',__('Danh mục',true))?></th>
				<th class="small center"><?php echo __('Download',true)?></th>
				<th class="small center"><?php echo __('Sắp xếp',true)?></th>
				<th class="small center"><?php echo __('Hiển thị',true)?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_documents_c as $val){
				$item_document = $val['Document'];
				$item_category = $val['DocumentCategory'];
				
				$url_edit = array('controller'=>'documents','action'=>'edit',$item_document['id'],'?'=>array('url'=>$current_url_c));
				
				if(!empty($item_document['link'])) $link_file = $item_document['link'];
				else $link_file = 'http://'.$_SERVER['HTTP_HOST'].$this->Html->url('/img/files/documents/'.$item_document['file']);
			?>
			<tr id="<?php echo 'item_'.$item_document['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_document['id']?>" /></td>
				<td><?php echo $this->Html->link($this->Text->truncate($item_document['name'],100,array('extact'=>false)),$url_edit,array('title'=>$item_document['name'],'class'=>'tooltip name'))?>
					<div class="view-comment">
						<?php 
							echo '<p class="view">'.$this->Html->link(__('Lượt tải',true).': '.number_format($item_document['download'],0,',','.'),'javascript:;',array('title'=>__('Có',true).' '.number_format($item_document['download'],0,',','.').' '.__('lượt tải',true),'class'=>'tooltip')).'</p>';
						?>
					</div>
				</td>
				<td class="cate">
					<?php 
						echo (!empty($item_category['name']) && !$item_category['trash'])?$this->Html->link($this->Text->truncate($item_category['name'],20,array('exact'=>false)),array('controller'=>'documents','action'=>'index','?'=>array('category_id'=>$item_category['id'])),array('title'=>$item_category['name'],'class'=>'tooltip'.(!$item_category['status']?' unactive':''))):'<span class="error">**&nbsp;ERROR&nbsp;**</span>';
						
						//Danh mục hiển thị khác
						if(!empty($item_document['category_other'])){
							$a_category_other = array_filter(explode('-', $item_document['category_other']));
							echo '<div>';
							foreach($a_category_other as $val2){
								if($val2!=$item_category['id'] && !empty($a_list_categories_c[$val2])){
									$item = array_filter(explode('_', $a_list_categories_c[$val2]));
									sort($item);
									echo $this->Html->link($this->Text->truncate($item[0],15,array('exact'=>false)),array('action'=>'index','?'=>array('category_id'=>$val2)),array('title'=>$item[0],'class'=>'tooltip')).', ';
								}
							}
							echo '</div>';
						}
					?>
				</td>
				<td class="small center"><?php echo $this->Html->link('&nbsp;',$link_file,array('title'=>__('Tải về',true),'target'=>'_blank','escape'=>false,'class'=>'act download'));?></td>
				<td class="center">
					<?php 
						if(!empty($_GET['position'])){
							$sort = $item_document['pos_'.$_GET['position']];
							$field_sort = 'pos_'.$_GET['position'];
						}else{
							$sort = $item_document['sort'];
							$field_sort = 'sort';
						}
						echo $this->Form->input('sort',array('class'=>'small','value'=>$sort,'onchange'=>"changeSort(this.value,'$field_sort',{$item_document['id']})"));
					?>
				</td>
				<td class="center">
					<div class="display">
						<ul>
							<?php 
								$count_pos = 0;
								foreach($oneweb_media['document']['display'] as $key2=>$val2){
									if(!empty($item_document['pos_'.$key2])) $count_pos++;
							?>
							<li class="pos_<?php echo $key2?>">
								<?php 
									echo $this->Html->link('&nbsp;','javascript:;',array('onclick'=>"changeStatus('pos_$key2',{$item_document['id']})",'class'=>'act '.((empty($item_document['pos_'.$key2]))?'unactive':'active'),'escape'=>false));
									echo $this->Html->link(__($val2,true),array('controller'=>'documents','action'=>'index','?'=>array('position'=>$key2)),array('title'=>__($val2,true),'class'=>'tooltip'));
								?>
							</li>
							<?php }?>
						</ul>
						<a href="javascript:;" class="act display"><?php echo $count_pos ?></a>
					</div> <!-- end .display -->
				</td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item_document['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_document['status']==1)?'active':'unactive')));?></td>
				<td class="center">
					<?php 
						echo $this->Html->tag('p',date('d/m/Y',$item_document['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_document['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:trashItem({$item_document['id']});",array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->