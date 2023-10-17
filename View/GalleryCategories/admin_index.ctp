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

	//Xóa danh mục
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
	};

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
				<li class="trashes"><?php echo __('Thùng rác',true)?></li>
			</ul>
		</div> <!--  end .box_select -->
		
		<?php echo $this->Form->create('Gallery',array('type'=>'get','name'=>'search','url'=>array('controller'=>'galleries','action'=>'index'),'inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('category_id',array('type'=>'select','options'=>$a_list_categories_c,'empty'=>__('Chọn danh mục',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tìm kiếm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tìm kiếm',true).'";}','onfocus'=>'if (this.value=="'.__('Tìm kiếm',true).'") { this.value=""; }'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('danh mục',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('GalleryCategory',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('sort',__('Sắp xếp',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_categories_c as $val){
				$item_category = $val['GalleryCategory'];
				
				$url_view = array('controller'=>'galleries','action'=>'index','lang'=>$item_category['lang'],'slug0'=>$item_category['slug'],'admin'=>false);
				$url_edit = array('controller'=>'gallery_categories','action'=>'edit',$item_category['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_category['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_category['id']?>" /></td>
				<td>
					<?php 
						echo $this->Html->link($this->Text->truncate($item_category['name'],200,array('extact'=>false)),array('controller'=>'galleries','action'=>'index','?'=>array('cate_id'=>$item_category['id'])),array('title'=>$item_category['name'],'class'=>'tooltip'));
						$a_counter = unserialize($item_category['counter']);
						echo ' <span class="counter">[ '.$this->Html->link(number_format($a_counter['item']),array('controller'=>'galleries','action'=>'index','?'=>array('cate_id'=>$item_category['id'])),array('title'=>number_format($a_counter['item']).' '.__('album',true),'class'=>'tooltip'))
								 .' ]</span>';
					?>
				</td>
				<td class="center"><?php echo $this->Form->input('sort',array('class'=>'small','value'=>$item_category['sort'],'onchange'=>"changeSort(this.value,{$item_category['id']})"));?></td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus({$item_category['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_category['status']==1)?'active':'unactive')));?></td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',$url_view,array('title'=>__('Xem',true),'class'=>'act view','escape'=>false,'target'=>'_blank'));
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:trashItem({$item_category['id']});",array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->