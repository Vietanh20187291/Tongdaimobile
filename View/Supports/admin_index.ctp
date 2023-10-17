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

	//Xóa support
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
					alert('<?php echo __('Có lỗi, vui lòng thử lại',true)?>');
				}
				$("#loading").hide();
			}
		});
	};

	//Sắp xếp lại hỗ trợ trực tuyến
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
	};

	//Thay đổi trạng thái
	function changeStatus(id){
		$.ajax({
			type:'post',
			url: '<?php echo $this->Html->url(array('action'=>'ajaxChangeStatus'));?>',
			data: 'id='+id,
			beforeSend: function(){
				$("#loading").show();
			},
			dataType:'json',
			success: function(result){
				$("#item_"+id+" .status a.act").removeClass(result.remove);
				$("#item_"+id+" .status a.act").addClass(result.add);
				$("#loading").hide();
			}
		});
	};
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
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('Support',array('type'=>'support','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('phone',__('Điện thoại',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('yahoo',__('Yahoo',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('skype',__('Skype',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('sort',__('Sắp xếp',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_supports_c as $val){
				$item_support = $val['Support'];
				$url_edit = array('controller'=>'supports','action'=>'edit',$item_support['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_support['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_support['id']?>" /></td>
				<td><?php echo $this->Html->link($this->Text->truncate($item_support['name'],200,array('extact'=>false)),$url_edit,array('title'=>$item_support['name'],'class'=>'tooltip'));?></td>
				<td><?php echo $item_support['phone']?></td>
				<td><?php echo $item_support['yahoo']?></td>
				<td><?php echo $item_support['skype']?></td>
				<td class="center"><?php echo $this->Form->input('sort',array('class'=>'small','value'=>$item_support['sort'],'onchange'=>"changeSort(this.value,{$item_support['id']})"));?></td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>'Thay đổi','onclick'=>"changeStatus({$item_support['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_support['status']==1)?'active':'unactive')));?></td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_support['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->