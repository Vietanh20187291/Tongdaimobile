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
				if(result.cl=='active') remove_class = 'unactive';
				else remove_class = 'active';
				for(i=0;i<result.id.length;i++){
					$("#item_"+result.id[i]+" td.status a").addClass(result.cl);
					$("#item_"+result.id[i]+" td.status a").removeClass(remove_class);
				}
				$("#loading").hide();
			}
		});
	};

	//Xóa information
	function trashItem(id){
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('action'=>'ajaxTrashItem'))?>',
			data:'id='+id,
			beforeSend:function(){		
				$("#loading").show();
			},
			dataType:'json',
			success: function(result){
				if(result){
					for(i=0;i<result.length;i++){
						$("#item_"+result[i]).fadeOut(110);
					}
				}else{
					$(".question").fadeOut();
					alert('<?php echo __('Có lỗi, vui lòng thử lại',true)?>');
				}
				$("#loading").hide();
			}
		});
	};

	//Sắp xếp lại information
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
				<li class="trashes"><?php echo __('Thùng rác',true)?></li>
			</ul>
		</div> <!--  end .box_select -->
		
		<?php echo $this->Form->create('Information',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tìm kiếm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tìm kiếm',true).'";}','onfocus'=>'if (this.value=="'.__('Tìm kiếm',true).'") { this.value=""; }','id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('trang thông tin chính',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('Information',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<th class="small"><?php echo __('Kiểu dữ liệu',true)?></th>
				<th class="small center"><?php echo __('Sắp xếp',true)?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_information_c as $val){
				$item_information = $val['Information'];
				$item_child = $val['ChildInformation'];
				
				$url_edit = array('controller'=>'information','action'=>'edit',$item_information['id'],'?'=>array('url'=>$current_url_c));
				if(empty($item_information['link'])) $url_view = array('controller'=>'information','action'=>'view','lang'=>$item_information['lang'],'position'=>$item_information['position'],'slug'=>$item_information['slug'],'admin'=>false);
				else $url_view = $item_information['link'];
			?>
			<tr id="<?php echo 'item_'.$item_information['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_information['id']?>" /></td>
				<td>
					<?php
						if(!empty($item_child)) echo $this->Html->link('&nbsp;','javascript:;',array('name'=>$item_information['id'],'class'=>'act more get_info','escape'=>false));
						else echo $this->Html->link('&nbsp;','javascript:;',array('name'=>$item_information['id'],'class'=>'act not-more','escape'=>false));
						echo $this->Html->link($this->Text->truncate($item_information['name'],100,array('extact'=>false)),$url_edit,array('title'=>$item_information['name'],'class'=>'tooltip name'));
						if(!empty($item_child)) echo ' ('.count($item_child).')';
					?>
				</td>
				<td class="center">
					<?php 
						if(!empty($item_information['link'])) echo $this->Html->link($this->Text->truncate($item_information['link'],40),$item_information['link'],array('title'=>$item_information['link'],'target'=>'_blank'));
						else echo __('Nội dung',true);
					?>
				</td>
				<td class="center"><?php echo $this->Form->input('sort',array('class'=>'small','value'=>$item_information['sort'],'onchange'=>"changeSort(this.value,{$item_information['id']})"))?></td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus({$item_information['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_information['status']==1)?'active':'unactive')));?></td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',$url_view,array('title'=>__('Xem',true),'class'=>'act '.(empty($item_information['link'])?'view':'view_link'), 'target'=>'_blank','escape'=>false));
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:trashItem({$item_information['id']});",array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
					?>
				</td>
			</tr>
			<?php if(!empty($item_child)){
				foreach ($item_child as $val2){
					$url_edit_child = array('controller'=>'information','action'=>'edit',$val2['id'],'?'=>array('url'=>$current_url_c));
					if(empty($val2['link'])) $url_view_child = array('controller'=>'information','action'=>'view','lang'=>$val2['lang'],'position'=>$val2['position'],'slug'=>$val2['slug'],'ext'=>'html','admin'=>false);
					else $url_view_child = $val2['link']
			?>
				<tr id="<?php echo 'item_'.$val2['id']?>" class="info<?php echo $item_information['id']?> sub_info">
					<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $val2['id']?>" /></td>
					<td>
						<?php
							echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act act_more','escape'=>false));
							echo $this->Html->link($this->Text->truncate($val2['name'],80,array('extact'=>false)),$url_edit_child,array('title'=>$val2['name'],'class'=>'tooltip'));
						?>
					</td>
					<td class="center">
						<?php 
							if(!empty($val2['link'])) echo $this->Html->link($this->Text->truncate($val2['link'],40),$val2['link'],array('title'=>$val2['link'],'target'=>'_blank'));
							else echo __('Nội dung',true);
						?>
					</td>
					<td class="center"><?php echo $this->Form->input('sort',array('class'=>'small','value'=>$val2['sort'],'onchange'=>"changeSort(this.value,{$val2['id']})"))?></td>
					<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus({$val2['id']})",'escape'=>false,'class'=>'act tooltip '.(($val2['status']==1)?'active':'unactive')));?></td>
					<td class="center action">
						<?php 
							echo $this->Html->link('&nbsp;',$url_view_child,array('title'=>__('Xem',true),'class'=>'act '.(empty($val2['link'])?'view':'view_link'), 'target'=>'_blank','escape'=>false));
							echo $this->Html->link('&nbsp;',$url_edit_child,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
							echo $this->Html->link('&nbsp;',"javascript:trashItem({$val2['id']});",array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
						?>
					</td>
				</tr>
			<?php }}?>
			<?php }
			if(!empty($a_information_error_c)){
			?>
			<tr>
				<th colspan="2">&nbsp;</th>
			</tr>
			<?php 
			foreach($a_information_error_c as $val){
				$url_edit_error = array('controller'=>'information','action'=>'edit',$val['id'],'?'=>array('url'=>$current_url_c));
				$url_view_error = array('controller'=>'information','action'=>'view','lang'=>$val['lang'],'position'=>$val['position'],'slug'=>$val['slug'],'ext'=>'html','admin'=>false);
			?>
				<tr id="<?php echo 'item_'.$val['id']?>">
					<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $val['id']?>" /></td>
					<td>
						<?php
							echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act more get_info','escape'=>false));
							echo $this->Html->link($this->Text->truncate($val['name'],50,array('extact'=>false)),$url_edit_error,array('title'=>$val['name'],'class'=>'tooltip'));
							echo ' <span class="error">**&nbsp;ERROR&nbsp;**</span>';
						?>
					</td>
					<td class="center">
						<?php 
							if(!empty($val['link'])) echo $this->Html->link($this->Text->truncate($val['link'],40),$val['link'],array('title'=>$val['link'],'target'=>'_blank'));
							else echo __('Nội dung',true);
						?>
					</td>
					<td class="center"><?php echo $this->Form->input('sort',array('class'=>'small','value'=>$val['sort'],'onchange'=>"changeSort(this.value,{$val['id']})"))?></td>
					<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus({$val['id']})",'escape'=>false,'class'=>'act tooltip '.(($val['status']==1)?'active':'unactive')));?></td>
					<td class="center action">
						<?php 
							echo $this->Html->link('&nbsp;',$url_view_error,array('title'=>__('Xem',true),'class'=>'act view', 'target'=>'_blank','escape'=>false));
							echo $this->Html->link('&nbsp;',$url_edit_error,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
							echo $this->Html->link('&nbsp;',"javascript:trashItem({$val['id']});",array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
						?>
					</td>
				</tr>
			<?php }}?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->