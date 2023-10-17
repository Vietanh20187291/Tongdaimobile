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

	//Xóa gallery
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

	//Sắp xếp lại gallery
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
		echo $this->Form->create('Gallery',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('position',array('type'=>'hidden','value'=>(!empty($_GET['position']))?$_GET['position']:''))?>
		<ul class="search">
			<li><?php echo $this->Form->input('cate_id',array('type'=>'select','options'=>$a_categories_c,'value'=>(!empty($_GET['cate_id'])?$_GET['cate_id']:''),'empty'=>__('Chọn danh mục',true),'class'=>'medium'))?></li>
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Tìm kiếm',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Tìm kiếm',true).'";}','onfocus'=>'if (this.value=="'.__('Tìm kiếm',true).'") { this.value=""; }','id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('album ảnh',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('Gallery',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th class="small center"><?php echo __('Ảnh',true)?></th>
				<th><?php echo $this->Paginator->sort('name',__('Tên',true))?></th>
				<th class="small"><?php echo $this->Paginator->sort('Gallery.name',__('Danh mục',true))?></th>
				<th class="small center"><?php echo __('Sắp xếp',true)?></th>
				<?php if(!empty($oneweb_media['gallery']['display'])){?>
				<th class="small center"><?php echo __('Hiển thị',true)?></th>
				<?php }?>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_galleries_c as $val){
				$item_gallery = $val['Gallery'];
				$item_cate = $val['GalleryCategory'];

				$comment = number_format(count($val['Comment']));
				$comment_active = 0;
				for($i=0;$i<$comment;$i++) if($val['Comment'][$i]['status']) $comment_active++;
				
				$url_edit = array('controller'=>'galleries','action'=>'edit',$item_gallery['id'],'?'=>array('url'=>$current_url_c));
				$url_view = array('controller'=>'galleries','action'=>'index','lang'=>$item_gallery['lang'],'slug0'=>$item_cate['slug'],'slug1'=>$item_gallery['slug'],'ext'=>'html','admin'=>false);
				
				if(!empty($item_gallery['image'])) $img = '/timthumb.php?src='.$this->Html->url('/webroot/img/images/galleries/').$item_gallery['image'];
				
				$img_small = $img."&h=40&w=40&zc=2";
				$img_larger = $img."&h=300&w=300&zc=2";
			?>
			<tr id="<?php echo 'item_'.$item_gallery['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_gallery['id']?>" /></td>
				<td class="center"><?php echo $this->Html->link($this->Html->image($img_small,array('alt'=>$item_gallery['name'])),$img_larger,array('title'=>$item_gallery['name'],'class'=>'preview','target'=>'_blank','escape'=>false))?></td>
				<td><?php echo $this->Html->link($this->Text->truncate($item_gallery['name'],100,array('extact'=>false)),$url_edit,array('title'=>$item_gallery['name'],'class'=>'tooltip name'))?>
					<div class="view-comment">
						<?php 
							echo '<p class="view">'.$this->Html->link(__('Lượt xem',true).': '.number_format($item_gallery['view'],0,',','.'),'javascript:;',array('title'=>__('Có',true).' '.number_format($item_gallery['view'],0,',','.').' '.__('lượt xem',true),'class'=>'tooltip')).'</p>';
							if($oneweb_media['gallery']['comment']){
								if($comment>0){
									if($comment_active<$comment) $cl_c = ' red';
									else $cl_c = '';
									
									echo $this->Html->link(__('Bình luận',true).': '.number_format($comment,0,',','.'),'javascript:;',array('title'=>__('Có',true).' '.number_format($comment_active,0,',','.').' '.__('bình luận được kích hoạt trên tổng số',true).' '.number_format($comment,0,',','.').' '.__('bình luận',true),'onclick'=>"comment({$item_gallery['id']},'Gallery');",'class'=>'tooltip'.$cl_c));	
								}else
									echo $this->Html->link(__('Bình luận',true).': '.number_format($comment,0,',','.'),'javascript:;',array('title'=>__('Chưa có bình luận nào',true),'onclick'=>"comment({$item_gallery['id']},'Gallery');",'class'=>'comment tooltip'));
							}
						?>
					</div>
				</td>
				<td class="cate">
					<?php 
						echo (!empty($item_cate['name']) && !$item_cate['trash'])?$this->Html->link($item_cate['name'],array('action'=>'index','?'=>array('cate_id'=>$item_cate['id'])),array('title'=>$item_cate['name'],'class'=>'tooltip'.(!$item_cate['status']?' unactive':''))):'<span class="error">**&nbsp;ERROR&nbsp;**</span>';
						
						//Danh mục hiển thị khác
						if(!empty($item_gallery['category_other'])){
							$a_category_other = array_filter(explode('-', $item_gallery['category_other']));
							echo '<div>';
							foreach($a_category_other as $val2){
								if($val2!=$item_cate['id'] && !empty($a_categories_c[$val2])){
									$item = array_filter(explode('_', $a_categories_c[$val2]));
									sort($item);
									echo $this->Html->link($this->Text->truncate($item[0],15,array('exact'=>false)),array('action'=>'index','?'=>array('cate_id'=>$val2)),array('title'=>$item[0],'class'=>'tooltip')).', ';
								}
							}
							echo '</div>';
						}
					?>
				</td>
				<td class="center">
					<?php 
						if(!empty($_GET['position'])){
							$sort = $item_gallery['pos_'.$_GET['position']];
							$field_sort = 'pos_'.$_GET['position'];
						}else{
							$sort = $item_gallery['sort'];
							$field_sort = 'sort';
						}
						echo $this->Form->input('sort',array('class'=>'small','value'=>$sort,'onchange'=>"changeSort(this.value,'$field_sort',{$item_gallery['id']})"));
					?>
				</td>
				<?php if(!empty($oneweb_media['gallery']['display'])){?>
				<td class="center">
					<div class="display">
						
						<ul>
							<?php 
								$count_pos = 0;
								foreach($oneweb_media['gallery']['display'] as $key2=>$val2){
									if(!empty($item_gallery['pos_'.$key2])) $count_pos++;
							?>
							<li class="pos_<?php echo $key2?>">
								<?php 
									echo $this->Html->link('&nbsp;','javascript:;',array('onclick'=>"changeStatus('pos_$key2',{$item_gallery['id']})",'class'=>'act '.((empty($item_gallery['pos_'.$key2]))?'unactive':'active'),'escape'=>false));
									echo $this->Html->link(__($val2,true),array('controller'=>'galleries','action'=>'index','?'=>array('position'=>$key2)),array('title'=>__($val2,true),'class'=>'tooltip'));
								?>
							</li>
							<?php }?>
						</ul>
						<a href="javascript:;" class="act display"><?php echo $count_pos ?></a>
					</div> <!-- end .display -->
				</td>
				<?php }?>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item_gallery['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_gallery['status']==1)?'active':'unactive')));?></td>
				<td class="center">
					<?php 
						echo $this->Html->tag('p',date('d/m/Y',$item_gallery['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_gallery['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center action">
					<?php 
						echo $this->Html->link('&nbsp;',$url_view,array('title'=>__('Xem',true),'class'=>'act view','escape'=>false,'target'=>'_blank'));
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:trashItem({$item_gallery['id']});",array('title'=>__('Thùng rác',true),'class'=>'act trash','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->