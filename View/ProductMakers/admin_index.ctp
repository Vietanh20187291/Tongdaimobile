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

	//Xóa hãng sx
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

	//Sắp xếp lại hãng sx
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
		
		<?php 
		echo $this->Form->create('Product',array('type'=>'get','url'=>array('controller'=>'products','action'=>'index'),'inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('position',array('type'=>'hidden','value'=>(!empty($_GET['position']))?$_GET['position']:''));
		?>
		<ul class="search">
			<li><?php echo $this->Form->input('category_id',array('type'=>'select','options'=>$a_product_categories_tree_c,'empty'=>__('Chọn danh mục',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('maker_id',array('type'=>'select','options'=>$a_product_makers_c,'empty'=>__('Chọn hãng sản xuất',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tên sản phẩm, Mã sản phẩm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tên sản phẩm, Mã sản phẩm',true).'";}','onfocus'=>'if (this.value=="'.__('Tên sản phẩm, Mã sản phẩm',true).'") { this.value=""; }'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('hãng sản xuất',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('ProductMaker',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th class="small center"><?php echo __('Ảnh',true)?></th>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<th class="small center"><?php echo __('Sắp xếp',true)?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_makers_c as $val){
				$item_maker = $val['ProductMaker'];
				
				$url_edit = array('controller'=>'product_makers','action'=>'edit',$item_maker['id'],'?'=>array('url'=>$current_url_c));
				
				if(empty($item_maker['link']))
					$url_view = array('controller'=>'products','action'=>'maker','lang'=>$item_maker['lang'],'slug'=>$item_maker['slug'],'admin'=>false);
				else $url_view = $item_maker['link'];
				
				if(!empty($item_maker['image'])) $img = '/timthumb.php?src='.$this->Html->url('/webroot/img/images/product_makers/').$item_maker['image'];
				
				$img_small = $img."&h=30&w=30&zc=2";
				$img_larger = $img."&h=100&w=100&zc=2";
			?>
			<tr id="<?php echo 'item_'.$item_maker['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_maker['id']?>" /></td>
				<td class="center"><?php echo $this->Html->link($this->Html->image($img_small,array('alt'=>$item_maker['name'])),$img_larger,array('title'=>$item_maker['name'],'class'=>'preview','escape'=>false))?></td>
				<td>
					<?php 
						echo $this->Html->link($this->Text->truncate($item_maker['name'],100,array('extact'=>false)),array('controller'=>'products','action'=>'index','?'=>array('maker_id'=>$item_maker['id'])),array('title'=>$item_maker['name'],'class'=>'tooltip'));
						
						$a_counter = unserialize($item_maker['counter']);
						echo ' <span class="counter">[ '.$this->Html->link(number_format($a_counter['item']),array('controller'=>'products','action'=>'index','?'=>array('maker_id'=>$item_maker['id'])),array('title'=>number_format($a_counter['item']).' '.__('sản phẩm',true),'class'=>'tooltip'))
								 .' ]</span>';
					?>
				</td>
				<td class="center"><?php echo $this->Form->input('sort',array('class'=>'small','value'=>$item_maker['sort'],'onchange'=>"changeSort(this.value,{$item_maker['id']})"));?></td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus({$item_maker['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_maker['status']==1)?'active':'unactive')));?></td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',$url_view,array('title'=>__('Xem',true),'class'=>'act '.(empty($item_maker['link'])?'view':'view_link'),'escape'=>false,'target'=>'_blank'));
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:trashItem({$item_maker['id']});",array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->