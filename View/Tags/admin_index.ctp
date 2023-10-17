<?php if(!empty($_GET['keyword'])) echo $this->Html->script('admin/highlight');?>


<div id="column_right">
	<?php
		echo $this->Form->create('Product',array('type'=>'post','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><?php echo __('STT',true)?></th>
				<th><?php echo $this->Paginator->sort('name',__('Tag',true))?></th>
				<th class="center"><?php echo __('Mô tả',true)?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php
				foreach($a_tags_c as $k=>$val){
					$item = $val['Tag'];
					$url = array('controller'=>'tags','action'=>'edit',$item['id']);
				?>
			<tr id="">
				<td class="center">
					<?php echo $k+1;?>
				</td>
				<td>
					<?php echo $this->Html->link($item['name'].' ('.$item['number'].')',$url,array('title'=>$item['name']))?>
				</td>
				<td class="center">
					<?php echo $this->Text->truncate($item['description'],200,array('exact'=>false))?>
				</td>
				<td class="center action">
					<?php
						echo $this->Html->link('&nbsp;',array('controller'=>'tags','action'=>'index','lang'=>$item['lang'],'id'=>$item['id'],'slug'=>$item['slug'],'admin'=>false),array('title'=>__('Xem',true),'class'=>'act view','escape'=>false,'target'=>'_blank'));
						echo $this->Html->link('&nbsp;',$url,array('title'=>__('Sửa',true),'class'=>'act edit','escape'=>false));
						echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item['id']});",array('title'=>__('Xoá',true),'class'=>'act delete','escape'=>false));
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->

</div> <!--  end #column_right -->
<script>
	//Xóa vĩnh viễn
	function deleteItem(id) {
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('controller'=>'tags', 'action'=>'ajaxDeleteItem'))?>',
			data:'id='+id,
			beforeSend:function() {
				$("#loading").show();
			},
			success: function(result){
				if(result){
					location.reload();
				}else{
					$(".question").fadeOut();
					alert('<?php echo __('Có lỗi, vui lòng thử lại',true)?>');
				}
				$("#loading").hide();
			}
		});
	};
</script>