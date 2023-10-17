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

		<?php echo $this->Form->create('ProductSize',array('url'=>array('plugin'=>'AdvancedProductAttributes','action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tìm kiếm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tìm kiếm',true).'";}','onfocus'=>'if (this.value=="'.__('Tìm kiếm',true).'") { this.value=""; }','id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('kích cỡ',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>

		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->

	<?php
		echo $this->Form->create('ProductSize',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('size',__('Kích cỡ',true))?></th>
				<th class="center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($product_sizes as $val){
				$item_product_color = $val['ProductSize'];

				$url_edit = array('controller'=>'product_sizes','action'=>'edit',$item_product_color['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_product_color['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_product_color['id']?>" /></td>
				<td>
					<?php echo $item_product_color['size'];
					?>
				</td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus({$item_product_color['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_product_color['status']==1)?'active':'unactive')));?></td>
				<td class="center action">
					<?php
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_product_color['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>

</div> <!--  end #column_right -->