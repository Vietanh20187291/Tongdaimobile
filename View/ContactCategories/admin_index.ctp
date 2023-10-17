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
					$("#loading").hide();
				}
			});
	};

	//Xóa danh mục
	function deleteItem(id){
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxDeleteItem'))?>',
			data:'id='+id,
			beforeSend:function(){		
				$("#loading").show();
			},
			success: function(result){
				if(result){
					$("#item_"+id).fadeOut(110);
				}else{
					$(".question").fadeOut();
					alert('<?php echo __('Có lỗi, vui lòng thử lại',true) ?>');
				}
				$("#loading").hide();
			}
		});
	}

	//Sắp xếp lại danh mục
	function changeSort(val,id){
		if(val<1) val=1;
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxChangeSort'))?>',
			data:'val='+val+'&id='+id,
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
				<li class="del"><?php echo __('Xóa',true)?></li>
			</ul>
		</div> <!--  end .box_select -->
		
		<?php echo $this->Form->create('Contact',array('type'=>'get','name'=>'search','url'=>array('controller'=>'contacts','action'=>'index'),'inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('category_id',array('type'=>'select','options'=>$a_list_categories_s,'empty'=>__('Nhóm khách hàng',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tiêu đề, họ tên, email, phone',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tiêu đề, họ tên, email, phone',true).'";}','onfocus'=>'if (this.value=="'.__('Tiêu đề, họ tên, email, phone',true).'") { this.value=""; }'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('nhóm',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('ContactCategory',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name','Tên')?></th>
				<th class="small center"><?php echo $this->Paginator->sort('sort',__('Sắp xếp',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('set_default',__('Mặc định',true),array('title'=>__('Mặc định lấy danh mục này khi có đơn hàng mới',true)))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_categories_c as $val){
				$item_category = $val['ContactCategory'];
				
				$url_edit = array('controller'=>'contact_categories','action'=>'edit',$item_category['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_category['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_category['id']?>" /></td>
				<td><?php echo $this->Html->link($this->Text->truncate($item_category['name'],200,array('extact'=>false)).' ('.count($val['Contact']).')',array('controller'=>'contacts','action'=>'index','?'=>array('category_id'=>$item_category['id'])),array('title'=>$item_category['name'],'class'=>'tooltip'));?></td>
				<td class="center"><?php echo $this->Form->input('sort',array('class'=>'small','value'=>$item_category['sort'],'onchange'=>"changeSort(this.value,{$item_category['id']})"));?></td>
				<td class="center set_default"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('set_default',{$item_category['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_category['set_default']==1)?'active':'unactive')));?></td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item_category['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_category['status']==1)?'active':'unactive')));?></td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_category['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->