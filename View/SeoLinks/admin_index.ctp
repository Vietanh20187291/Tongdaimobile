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
				if(field!='status') $("#item_"+id+" a.display").text(result.count);
				$("#loading").hide();
			}
		});
	}

	//Xóa banner
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
					alert('<?php echo __('Có lỗi, vui lòng thử lại',true) ?>');
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
		echo $this->Form->create('SeoLink',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)));
		?>
		<ul class="search">
			<li>
				<?php echo $this->Form->input('name', array('type'=>'select','options'=>array('Dự án'=>'Dự án','Tìm thiết kế'=>'Tìm thiết kế', 'Mẫu nhà đẹp'=>'Mẫu nhà đẹp', 'Nội thất đẹp'=>'Nội thất đẹp', 'Chi phí xây nhà'=>'Chi phí xây nhà'),'value'=>(!empty($_GET['name'])?$_GET['name']:''),'empty'=>'Lựa chọn')) ?>
			</li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tìm kiếm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tìm kiếm',true).'";}','onfocus'=>'if (this.value=="'.__('Tìm kiếm',true).'") { this.value=""; }', 'id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('links',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	<?php
		echo $this->Form->create('SeoLink',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th class="larger center"><?php echo $this->Paginator->sort('name',__('Name',true))?></th>
				<th class="larger center"><?php echo $this->Paginator->sort('visit',__('Visit',true))?></th>
				<th class="larger center"><?php echo $this->Paginator->sort('link',__('Link',true))?></th>
				<th class="center"><?php echo $this->Paginator->sort('meta_title',__('Title',true))?></th>
				<th class="center"><?php echo $this->Paginator->sort('meta_keyword',__('Keyword',true))?></th>
				<th class="center"><?php echo $this->Paginator->sort('modified',__('Last visit',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Status',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_seo_links_c as $val){
				$item = $val['SeoLink'];
				$url_edit = array('controller'=>'seo_links','action'=>'edit',$item['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item['id']?>" /></td>
				<td class="small center"><?php echo $item['name']; ?></td>
				<td class="small center"><?php echo $item['visit']; ?></td>
				<td class=""><?php echo $this->Html->link(str_replace('https://xaydungso.vn:443/', '/', $item['link']),$url_edit,array('title'=>'Chỉnh sửa'))?></td>
				<td class=""><?php echo $this->Html->link($item['meta_title'],$url_edit,array('title'=>'Chỉnh sửa')); ?></td>
				<td class=""><?php echo $this->Html->link($item['meta_keyword'],$url_edit,array('title'=>'Chỉnh sửa')); ?></td>
				<td class="center">
					<?php
						echo $this->Html->tag('p',date('d/m/Y',$item['modified']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item['modified']),array('class'=>'time'));
					?>
				</td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item['id']})",'escape'=>false,'class'=>'act  '.(($item['status']==1)?'active':'unactive')));?></td>
				<td class="center action">
					<?php
					echo $this->Html->link('&nbsp;',$item['link'],array('title'=>__('Xem',true),'class'=>'act view','escape'=>false,'target'=>'_blank'));
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>

</div> <!--  end #column_right -->