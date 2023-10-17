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

	//Xóa thuế
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
		
		<?php 
		echo $this->Form->create('Product',array('type'=>'get','url'=>array('controller'=>'products','action'=>'index'),'inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('position',array('type'=>'hidden','value'=>(!empty($_GET['position']))?$_GET['position']:''));
		?>
		<ul class="search">
			<li><?php echo $this->Form->input('category_id',array('type'=>'select','options'=>$a_product_categories_tree_c,'empty'=>__('Chọn danh mục',true),'class'=>'medium'))?></li>
			<li<?php if(empty($oneweb_product['maker'])) echo ' class="hidden"'?>><?php echo $this->Form->input('maker_id',array('type'=>'select','options'=>$a_product_makers_c,'empty'=>__('Chọn hãng sản xuất',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tên sản phẩm, Mã sản phẩm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tên sản phẩm, Mã sản phẩm',true).'";}','onfocus'=>'if (this.value=="'.__('Tên sản phẩm, Mã sản phẩm',true).'") { this.value=""; }'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('loại thuế',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('ProductTax',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<th><?php echo __('Mô tả',true)?></th>
				<th class="small center"><?php echo $this->Paginator->sort('value',__('Thuế',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_taxes_c as $val){
				$item_tax = $val['ProductTax'];
				$url_edit = array('controller'=>'product_taxes','action'=>'edit',$item_tax['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_tax['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_tax['id']?>" /></td>
				<td><?php echo $this->Html->link($this->Text->truncate($item_tax['name'],100,array('extact'=>false)),$url_edit,array('title'=>$item_tax['name'],'class'=>'tooltip'));?></td>
				<td><?php echo $this->Text->truncate($item_tax['description'],300,array('exact'=>false))?></td>
				<td class="center"><?php echo $item_tax['value']?>%</td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus({$item_tax['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_tax['status']==1)?'active':'unactive')));?></td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_tax['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->