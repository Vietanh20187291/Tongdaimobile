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

	//Thay đổi trạng thái poll_question
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
	//thay doi trang thai poll
	function changeStatusPoll(field,id){
		$.ajax({
			type:'post',
			url: '<?php echo $this->Html->url(array('controller'=>'polls','action'=>'ajaxChangeStatus'));?>',
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
	

	//Xóa poll_question
	function deleteItem(id){
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxDeleteItem'))?>',
			data:'id='+id,
			beforeSend:function(){		
				$("#loading").show();
			},
			dataType:'json',
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

	//Xóa poll
	function deleteItemPoll(id){
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('controller'=>'polls','action'=>'ajaxDeleteItem'))?>',
			data:'id='+id,
			beforeSend:function(){		
				$("#loading").show();
			},
			dataType:'json',
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

	//Sắp xếp lại poll_question
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

	//Sắp xếp lại poll
	function changeSortPoll(val,id){
		if(val<1) val=1;
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('controller'=>'polls','action'=>'ajaxChangeSort'))?>',
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
				<li class="delete"><?php echo __('Xóa',true)?></li>
			</ul>
		</div> <!--  end .box_select -->
		
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('PollQuestion',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name',__('Tiêu đề',true))?></th>
				<th class="small"><?php echo __('Lựa chọn',true)?></th>
				<th class="small center"><?php echo __('Sắp xếp',true)?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_pollquestion_a_c as $val){
				$item_poll_question = $val['PollQuestion'];
				$item_poll = $val['Poll'];
				
				$url_edit = array('controller'=>'poll_questions','action'=>'edit',$item_poll_question['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_poll_question['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_poll_question['id']?>" /></td>
				<td>
					<?php
						if(!empty($item_poll)) echo $this->Html->link('&nbsp;','javascript:;',array('name'=>$item_poll_question['id'],'class'=>'act more get_info','escape'=>false));
						else echo $this->Html->link('&nbsp;','javascript:;',array('name'=>$item_poll_question['id'],'class'=>'act not-more','escape'=>false));
						echo $this->Html->link($this->Text->truncate($item_poll_question['name'],100,array('extact'=>false)),$url_edit,array('title'=>$item_poll_question['name'],'class'=>'tooltip name'));
						if(!empty($item_poll)) echo ' ('.count($item_poll).')';
					?>
				</td>
				<td class="center">
				</td>
				<td class="center"><?php echo $this->Form->input('sort',array('class'=>'small','value'=>$item_poll_question['sort'],'onchange'=>"changeSort(this.value,{$item_poll_question['id']})"))?></td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item_poll_question['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_poll_question['status']==1)?'active':'unactive')));?></td>
				<td class="center">
					<?php 
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_poll_question['id']});",array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
					?>
				</td>
			</tr>
			<?php
			 if(!empty($item_poll)){
				foreach ($item_poll as $val2){
					$url_edit_child = array('controller'=>'polls','action'=>'edit',$val2['id'],'?'=>array('url'=>$current_url_c));
			?>
				<tr id="<?php echo 'item_'.$val2['id']?>" class="info<?php echo $item_poll_question['id']?> sub_info">
					<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $val2['id']?>" /></td>
					<td>
						<?php
							echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act act_more','escape'=>false));
							echo $this->Html->link($this->Text->truncate($val2['description'],80,array('extact'=>false)),$url_edit_child,array('title'=>$val2['description'],'class'=>'tooltip'));
						?>
					</td>
					<td class="center">
						<?php 
							echo $val2['number'];
						?>
					</td>
					<td class="center"><?php echo $this->Form->input('sort',array('class'=>'small','value'=>$val2['sort'],'onchange'=>"changeSortPoll(this.value,{$val2['id']})"))?></td>
					<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatusPoll('status',{$val2['id']})",'escape'=>false,'class'=>'act tooltip '.(($val2['status']==1)?'active':'unactive')));?></td>
					<td class="center">
						<?php 
							echo $this->Html->link('&nbsp;',$url_edit_child,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
							echo $this->Html->link('&nbsp;',"javascript:deleteItemPoll({$val2['id']});",array('title'=>__('Xóa',true),'class'=>'act trash','escape'=>false))
						?>
					</td>
				</tr>
			<?php }}?>
			<?php } ?>

		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->