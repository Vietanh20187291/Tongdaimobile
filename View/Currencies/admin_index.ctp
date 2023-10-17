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

	//Xóa đơn vị tiền
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
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('Currency',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name','Tên')?></th>
				<th class="small center"><?php echo $this->Paginator->sort('value',__('Tỉ giá (VNĐ)',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('unit',__('Ký hiệu',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('location',__('Vị trí',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_currencies_c as $val){
				$item_currency = $val['Currency'];
				$url_edit = array('controller'=>'currencies','action'=>'edit',$item_currency['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_currency['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_currency['id']?>" /></td>
				<td><?php echo $this->Html->link($this->Text->truncate($item_currency['name'],100,array('extact'=>false)),$url_edit,array('title'=>$item_currency['name'],'class'=>'tooltip'));?></td>
				<td class="center"><?php echo number_format($item_currency['value'])?></td>
				<td class="center"><?php echo $item_currency['unit']?></td>
				<td class="center"><?php echo ($item_currency['location']=='first')?__('Trước',true):__('Sau',true)?></td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus({$item_currency['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_currency['status']==1)?'active':'unactive')));?></td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_currency['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->