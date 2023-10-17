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
	}

	//Xóa comment
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
					$(".child_"+id).fadeOut(110);
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
				<li class="view"><?php echo __('Đã đọc',true)?></li>
				<li class="unview"><?php echo __('Chưa đọc',true)?></li>
				<li class="active"><?php echo __('Kích hoạt',true)?></li>
				<li class="unactive"><?php echo __('Bỏ kích hoạt',true)?></li>
				<li class="del"><?php echo __('Xóa',true)?></li>
			</ul>
		</div> <!--  end .box_select -->

		<?php echo $this->Form->create('Comment',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Họ tên, Email',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Họ tên, Email',true).'";}','onfocus'=>'if (this.value=="'.__('Họ tên, Email',true).'") { this.value=""; }','id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('bình luận',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>

		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->

	<?php
		echo $this->Form->create('Comment',array('type'=>'comment','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name',__('Họ tên',true))?></th>
				<th><?php echo __('Nội dung',true)?></th>
				<!-- <th class="small center"><?php //echo $this->Paginator->sort('phone',__('Điện thoại',true))?></th> -->
				<th class="small center"><?php echo $this->Paginator->sort('model',__('Module',true))?></th>
                <th class="small center"><?php echo $this->Paginator->sort('star',__('Đánh giá',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('status',__('Trạng thái',true))?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_comments_c as $val){
				$item_comment = $val['Comment'];

				$url_edit = array('action'=>'edit',$item_comment['id'],'?'=>array('url'=>$current_url_c));

				$module = '';
				switch ($item_comment['model']){
					case 'Product':
						$module = __('Sản phẩm',true);
						break;
					case 'Post':
						$module = __('Bài viết',true);
						break;
					case 'Gallery':
						$module = __('Hình ảnh',true);
						break;
					case 'Video':
						$module = __('Video',true);
						break;
				}
				if(empty($item_comment['parent_id'])) {
			?>
			<tr id="<?php echo 'item_'.$item_comment['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_comment['id']?>" /></td>
				<td class="small<?php if(!$item_comment['view']) echo ' new'?>">
					<?php
						echo $this->Html->link($this->Text->truncate($item_comment['name'],40,array('extact'=>false)),array('action'=>'add','?'=>array('parent_id'=>$item_comment['id'])),array('title'=>$item_comment['name'],'class'=>'tooltip name'));
						echo $this->Html->tag('p',$this->Html->link($item_comment['email'],array('action'=>'index','?'=>array('keyword'=>$item_comment['email'])),array('title'=>$item_comment['email'])),array('class'=>'email name'));
					?>
				</td>
				<td>
					<div class="less_<?php echo $item_comment['id']?>">
						<?php
							echo $this->Text->truncate(strip_tags($item_comment['description']),150);
							if(strlen($item_comment['description'])>150) echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act more','title'=>__('Xem thêm',true),'onclick'=>"more_description({$item_comment['id']})",'escape'=>false));
						?>
					</div>
					<div class="more_<?php echo $item_comment['id']?>" style="display:none">
						<?php
							echo $item_comment['description'];
							echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act sub','title'=>__('Rút gọn',true),'onclick'=>"less_description({$item_comment['id']})",'escape'=>false));
						?>
					</div>
					<?php if(empty($val['ChildComment'])) echo $this->Html->tag('p',$this->Html->link(__('Trả lời',true),array('action'=>'add','?'=>array('parent_id'=>$item_comment['id'])),array('title'=>__('Trả lời',true))),array('class'=>'answer'));?>
				</td>
				<!-- <td class="center small"><?php //echo $item_comment['phone'] ?></td> -->
				<td class="center small"><?php echo $module ?></td>
                <td class="center"><?php echo $item_comment['star'].' ✯' ?></td>
				<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$item_comment['id']})",'escape'=>false,'class'=>'act tooltip '.(($item_comment['status']==1)?'active':'unactive')));?></td>
				<td class="center">
					<?php
						echo $this->Html->tag('p',date('d/m/Y',$item_comment['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_comment['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center">
					<?php
						echo $this->Html->link('&nbsp;',array('action'=>'view','?'=>array('model'=>$item_comment['model'],'id'=>$item_comment['item_id'])),array('title'=>__('Xem',true),'class'=>'act view','target'=>'_blank','escape'=>false));
						echo $this->Html->link('&nbsp;',$url_edit,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_comment['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))
					?>
				</td>
			</tr>

				<?php if(!empty($val['ChildComment'])){		//Trả lời
					foreach($val['ChildComment'] as $key=>$val2){

						$url_edit2 = array('action'=>'edit',$val2['id'],'?'=>array('url'=>$current_url_c));

						$module2 = '';
						switch ($val2['model']){
							case 'Product':
								$module2 = __('Sản phẩm',true);
								break;
							case 'Post':
								$module2 = __('Bài viết',true);
								break;
							case 'Gallery':
								$module2 = __('Hình ảnh',true);
								break;
							case 'Video':
								$module2 = __('Video',true);
								break;
						}
						if($item_comment['id'] == $val2['parent_id']) {
				?>
				<tr id="<?php echo 'item_'.$val2['id']?>" class="child <?php echo 'child_'.$item_comment['id']?>">
					<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $val2['id']?>" /></td>
					<td class="small<?php if(!$val2['view']) echo ' new'?>">
						<?php
							echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act child','escape'=>false));
							echo $this->Html->link($this->Text->truncate($val2['name'],30,array('extact'=>false)),$url_edit2,array('title'=>$val2['name'],'class'=>'tooltip'));
							echo $this->Html->tag('p',$this->Html->link($val2['email'],array('action'=>'index','?'=>array('keyword'=>$val2['email'])),array('title'=>$val2['email'])),array('class'=>'email'));
						?>
					</td>
					<td>
						<div class="less_<?php echo $val2['id']?>">
							<?php
								echo $this->Text->truncate(strip_tags($val2['description']),150);
								if(strlen($val2['description'])>150) echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act more','title'=>__('Xem thêm',true),'onclick'=>"more_description({$val2['id']})",'escape'=>false));
							?>
						</div>
						<div class="more_<?php echo $val2['id']?>" style="display:none">
							<?php
								echo $val2['description'];
								echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act sub','title'=>__('Rút gọn',true),'onclick'=>"less_description({$val2['id']})",'escape'=>false));
							?>
						</div>
						<?php if($key == 0) echo $this->Html->tag('p',$this->Html->link(__('Trả lời',true),array('action'=>'add','?'=>array('parent_id'=>$item_comment['id'])),array('title'=>__('Trả lời',true))),array('class'=>'answer'));?>
					</td>
					<!-- <td class="center"></td> -->
					<td class="center small"><?php echo $module2 ?></td>
                    <td class="center"><?php echo '\'\'' ?></td>
					<td class="center status"><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Thay đổi',true),'onclick'=>"changeStatus('status',{$val2['id']})",'escape'=>false,'class'=>'act tooltip '.(($val2['status']==1)?'active':'unactive')));?></td>
					<td class="center">
						<?php
							echo $this->Html->tag('p',date('d/m/Y',$val2['created']),array('class'=>'date'));
							echo $this->Html->tag('p',date('H:i:s',$val2['created']),array('class'=>'time'));
						?>
					</td>
					<td class="center action">
						<?php
							echo $this->Html->link('&nbsp;',array('action'=>'view','?'=>array('model'=>$val2['model'],'id'=>$val2['item_id'])),array('title'=>__('Xem',true),'class'=>'act view','target'=>'_blank','escape'=>false));
							echo $this->Html->link('&nbsp;',$url_edit2,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
							echo $this->Html->link('&nbsp;',"javascript:deleteItem({$val2['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))
						?>
					</td>
				</tr>
			<?php }}}}}?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>

</div> <!--  end #column_right -->