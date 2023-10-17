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
	};

	//Xóa faq
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

	//Sắp xếp lại faq
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
		
		<?php echo $this->Form->create('Faq',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('category_id',array('type'=>'select','options'=>$a_list_categories_c,'empty'=>__('Chọn danh mục',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tìm kiếm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tìm kiếm',true).'";}','onfocus'=>'if (this.value=="'.__('Tìm kiếm',true).'") { this.value=""; }','id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('FAQ',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('Faq',array('type'=>'faq','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('question',__('Câu hỏi',true))?></th>
				<th><?php echo $this->Paginator->sort('answer',__('Câu trả lời',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('FaqCategory.name',__('Nhóm',true))?></th>
				<th class="small center"><?php echo __('Sắp xếp',true)?></th>
				<th class="small center"><?php echo $this->Paginator->sort('most',__('TG',true),array('title'=>__('Những câu hỏi thường gặp',true)))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center">Action</th>
			</tr>
			<?php foreach($a_faqs_c as $val){
				$item_faq = $val['Faq'];
				$item_category = $val['FaqCategory'];
				
				$url_edit = array('controller'=>'faqs','action'=>'edit',$item_faq['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_faq['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_faq['id']?>" /></td>
				<td><?php echo $this->Html->link($this->Text->truncate($item_faq['question'],50,array('extact'=>false)),$url_edit,array('title'=>$item_faq['question'],'class'=>'tooltip name'))?></td>
				<td><?php echo $this->Text->truncate($item_faq['answer'],80,array('extact'=>false))?></td>
				<td class="small cate"><?php echo (!empty($item_category['name']) && !$item_category['trash'])?$this->Html->link($this->Text->truncate($item_category['name'],20,array('exact'=>false)),array('controller'=>'faqs','action'=>'index','?'=>array('category_id'=>$item_category['id'])),array('title'=>$item_category['name'],'class'=>'tooltip'.(!$item_category['status']?' unactive':''))):'<span class="error">**&nbsp;ERROR&nbsp;**</span>'?></td>
				<td class="center"><?php echo $this->Form->input('sort',array('class'=>'small','value'=>$item_faq['sort'],'onchange'=>"changeSort(this.value,{$item_faq['id']})"));?></td>
				<td class="center most"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('most',{$item_faq['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_faq['most']==1)?'active':'unactive')));?></td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item_faq['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_faq['status']==1)?'active':'unactive')));?></td>
				<td class="center">
					<?php 
						echo $this->Html->tag('p',date('d/m/Y',$item_faq['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_faq['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:trashItem({$item_faq['id']});",array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->