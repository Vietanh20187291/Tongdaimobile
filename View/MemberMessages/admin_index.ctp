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
				$("#loading").hide();
			}
		});
	}

	//Xóa comment
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
					$(".child_"+id).fadeOut(110);
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
		
		<?php echo $this->Form->create('MemberMessage',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tiêu đề',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tiêu đề',true).'";}','onfocus'=>'if (this.value=="'.__('Tiêu đề',true).'") { this.value=""; }','id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('MemberMessage',array('type'=>'comment','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name',__('Tiêu đề',true))?></th>
				<th><?php echo __('Nội dung',true)?></th>
				<th class="small center"><?php echo $this->Paginator->sort('model',__('Thành viên',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_member_message_c as $val){
				$item_member_message = $val['MemberMessage'];

				$url_edit = array('action'=>'edit',$item_member_message['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_member_message['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_member_message['id']?>" /></td>
				<td class="small">
					<?php 
						echo $this->Html->link($this->Text->truncate($item_member_message['title'],40,array('extact'=>false)),$url_edit,array('title'=>$item_member_message['title']));
					?>
				</td>
				<td>
					<div class="less_<?php echo $item_member_message['id']?>">
						<?php 
							echo $this->Text->truncate(strip_tags($item_member_message['message']),150);
							if(strlen($item_member_message['message'])>150) echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act more','title'=>__('Xem thêm',true),'onclick'=>"more_description({$item_member_message['id']})",'escape'=>false));
						?>
					</div>
					<div class="more_<?php echo $item_member_message['id']?>" style="display:none">
						<?php 
							echo $item_member_message['message'];
							echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act sub','title'=>__('Rút gọn',true),'onclick'=>"less_description({$item_member_message['id']})",'escape'=>false));
						?>
					</div>
				</td>
				<td class="center small">
				<?php echo $this->Text->truncate($item_member_message['member_receive'],20,array('exact'=>true)); ?>
				
				</td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item_member_message['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_member_message['status']==1)?'active':'unactive')));?></td>
				<td class="center">
					<?php 
						echo $this->Html->tag('p',date('d/m/Y',$item_member_message['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_member_message['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center action">
					<?php
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_member_message['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))
					?>
				</td>
			</tr>
			
				

			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->