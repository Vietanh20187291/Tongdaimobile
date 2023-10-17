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
					alert('<?php echo __('Có lỗi, vui lòng thử lại',true) ?>');
				}
				$("#loading").hide();
			}
		});
	}

	//Sắp xếp lại banner
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
		echo $this->Form->create('Banner',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('position',array('type'=>'hidden','value'=>(!empty($_GET['position']))?$_GET['position']:''))?>
		<ul class="search">
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tìm kiếm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tìm kiếm',true).'";}','onfocus'=>'if (this.value=="'.__('Tìm kiếm',true).'") { this.value=""; }', 'id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('banner',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('Banner',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th class="small center"><?php echo __('Ảnh',true)?></th>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('link',__('Link',true))?></th>
				<th class="small center"><?php echo __('Trang/Vị trí',true)?></th>
				<th class="small center"><?php echo __('Sắp xếp',true)?></th>
				<?php if(!empty($oneweb_banner['display'])){?>
				<th class="small center"><?php echo __('Hiển thị',true)?></th>
				<?php }?>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_banners_c as $val){
				$item_banner = $val['Banner'];
				
				$url_edit = array('controller'=>'banners','action'=>'edit',$item_banner['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_banner['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_banner['id']?>" /></td>
				<td class="center"><?php echo $this->Html->link($this->Html->image('/img/images/banners/'.$item_banner['image'],array('alt'=>$item_banner['name'],'width'=>30)),'/img/images/banners/'.$item_banner['image'],array('title'=>$item_banner['name'],'class'=>'preview','target'=>'_blank','escape'=>false))?></td>
				<td><?php echo $this->Html->link($this->Text->truncate($item_banner['name'],100,array('extact'=>false)),$url_edit,array('title'=>$item_banner['name'],'class'=>'tooltip name'))?></td>
				<td class="small"><?php echo $this->Html->link($item_banner['link'],$item_banner['link'],array('title'=>$item_banner['name'],'target'=>'_blank'))?></td>
				<td class="small">
					<?php 
						$a_pages = array_filter(explode('-', $item_banner['page']));
						if(!in_array(1, $a_pages)){
							for($i=1;$i<=count($a_pages);$i++){
								echo $this->Html->tag('p',$i.'. '.@$oneweb_banner['page'][$a_pages[$i]]);
							}
						}else{
							echo $oneweb_banner['page'][1];
						}
					?>
					<hr>
					<?php 
						foreach($oneweb_banner['display'] as $key2=>$val2){
					?>
					<p class="s"><?php if($item_banner['pos_'.$key2]) echo $val2.', '?></p>
					<?php }?>
				</td>
				<td class="center">
					<?php 
						if(!empty($_GET['position'])){
							$sort = $item_banner['pos_'.$_GET['position']];
							$field_sort = 'pos_'.$_GET['position'];
						}else{
							$sort = $item_banner['sort'];
							$field_sort = 'sort';
						}
						echo $this->Form->input('sort',array('class'=>'small','value'=>$sort,'onchange'=>"changeSort(this.value,'$field_sort',{$item_banner['id']})"));
					?>
				</td>
				<?php if(!empty($oneweb_banner['display'])){?>
				<td class="center">
					<div class="display">
						
						<ul>
							<?php 
								$count_pos = 0;
								foreach($oneweb_banner['display'] as $key2=>$val2){
									if(!empty($item_banner['pos_'.$key2])) $count_pos++;
									$size2 = $oneweb_banner['size'][$key2];
							?>
							<li class="pos_<?php echo $key2?>">
								<?php 
									echo $this->Html->link('&nbsp;','javascript:;',array('onclick'=>"changeStatus('pos_$key2',{$item_banner['id']})",'class'=>'act '.((empty($item_banner['pos_'.$key2]))?'unactive':'active'),'escape'=>false));
									echo $this->Html->link(__($val2,true).' '.$size2[0].' x '.$size2[1].'(px)',array('controller'=>'banners','action'=>'index','?'=>array('position'=>$key2)),array('title'=>__($val2,true),'class'=>'tooltip'));
								?>
							</li>
							<?php }?>
						</ul>
						<a href="javascript:;" class="act display"><?php echo $count_pos ?></a>
					</div> <!-- end .display -->
				</td>
				<?php }?>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item_banner['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_banner['status']==1)?'active':'unactive')));?></td>
				<td class="center">
					<?php 
						echo $this->Html->tag('p',date('d/m/Y',$item_banner['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_banner['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:trashItem({$item_banner['id']});",array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->