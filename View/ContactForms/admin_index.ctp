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

	//Xóa đơn hàng
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

	//Thay đổi danh mục
	function setCategory(contact_id,cate_id){
		$.ajax({
			type: 'post',
			url : '<?php echo $this->Html->url(array('action'=>'ajaxSetCategory'))?>',
			data: 'contact_id='+contact_id+'&cate_id='+cate_id,
			beforeSend:function(){
				$("#loading").show();
			},
			success:function(result){
				$("#loading").hide();
				if(result==false) alert('<?php echo __('Có lỗi, bạn vui lòng thử lại',true)?>');
			}
		});
	}
</script>

<div id="column_right">
	<div id="action_top">
		<div id="action_1" class="box_select">
			<ul>
				<li class="view"><?php echo __('Đã đọc',true)?></li>
				<li class="unview"><?php echo __('Chưa đọc',true)?></li>
				<li class="del"><?php echo __('Xóa',true)?></li>
			</ul>
		</div> <!--  end .box_select -->

		<?php echo $this->Form->create('ContactForm',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:'','class'=>'larger','placeholder'=>__('Họ tên, Email, Phone',true)))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('liên hệ',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->

	<?php
		echo $this->Form->create('ContactForm',array('type'=>'contact','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th class="small"><?php echo $this->Paginator->sort('name',__('Họ tên',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('phone',__('Điện thoại',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_contacts_c as $val){
				$item_contact = $val['ContactForm'];

				$url_view = array('controller'=>'contact_forms','action'=>'view',$item_contact['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_contact['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_contact['id']?>" /></td>
				<td class="">
					<?php echo $this->Html->link($item_contact['name'],$url_view,array('title'=>$item_contact['name'],'escape'=>false))?>
					<?php
						// echo $this->Html->tag('p',$this->Html->link($this->Text->truncate($item_contact['name'],50,array('extact'=>false)),array('action'=>'index','?'=>array('keyword'=>$item_contact['name'])),array('title'=>$item_contact['name'])));
						// echo $this->Html->tag('p',
						// $this->Html->link($item_contact['email'],array('action'=>'index','?'=>array('keyword'=>$item_contact['email'])),array('title'=>$item_contact['email']))
						// ,array('class'=>'email'))
					?>
				</td>
				<td>
					<?php echo $this->Html->link($item_contact['phone'],array('action'=>'index','?'=>array('keyword'=>$item_contact['phone'])),array('title'=>$item_contact['phone'])) ?>
				</td>
				<td class="center">
					<?php
						echo $this->Html->tag('p',date('d/m/Y',$item_contact['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_contact['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center action">
					<?php echo $this->Html->link('&nbsp;',$url_view,array('title'=>$item_contact['name'],'class'=>'act view','escape'=>false))?>
					<?php echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_contact['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>

</div> <!--  end #column_right -->