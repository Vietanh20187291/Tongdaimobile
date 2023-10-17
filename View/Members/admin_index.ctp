<?php echo $this->Html->script('admin/highlight');?>

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
		})
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
			})
	}

	//Xóa sản phẩm
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
		})
	}

	//Sắp xếp lại sản phẩm
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
		})
	}
</script>

<?php echo $this->element('backend/c_comment')?>
	
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
		echo $this->Form->create('Member',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)));?>
		<ul class="search">
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tìm kiếm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tìm kiếm',true).'";}','onfocus'=>'if (this.value=="'.__('Tìm kiếm',true).'") { this.value=""; }','id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('Member',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<th><?php echo $this->Paginator->sort('email',__('Email',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_members_c as $val){
				$member = $val['Member'];
				
				$url_edit = array('controller'=>'members','action'=>'edit',$member['id']);
				
			?>
			<tr id="<?php echo 'item_'.$member['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $member['id']?>" /></td>
				<td>
					<?php 
						echo $this->Html->link($this->Text->truncate($member['name'],100,array('extact'=>false)),$url_edit,array('title'=>$member['name'],'class'=>'tooltip name'));
					?>
				</td>
				<td>
				<?php 
					echo $this->Html->link($this->Text->truncate($member['email'],100,array('extact'=>false)),$url_edit,array('title'=>$member['email'],'class'=>'tooltip email'));
				?>
				</td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$member['id']})",'escape'=>false,'class'=>'act tooltip '.(($member['status']==1)?'active':'unactive')));?></td>
				<td class="center">
					<?php 
						echo $this->Html->tag('p',date('d/m/Y',$member['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$member['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:trashItem({$member['id']});",array('title'=>'Thùng rác','class'=>'act trash','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->