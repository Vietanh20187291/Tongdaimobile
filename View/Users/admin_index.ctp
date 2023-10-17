<script type="text/javascript">
	//Xóa user
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
					alert('Có lỗi, vui lòng thử lại');
				}
				$("#loading").hide();
			}
		})
	}
</script>
	
<div id="column_right">
	<div id="action_top">
		<?php echo $this->Form->create('User',array('type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('email',array('value'=>(!empty($_GET['email']))?$_GET['email']:'Email','class'=>'larger','onblur'=>'if (this.value==""){ this.value="Email";}','onfocus'=>'if (this.value=="Email") { this.value=""; }'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<div class="paginator">
			<?php 
				echo $this->Paginator->counter(array('format'=>'<span class="page">%page%/%pages%</span>'));
				echo $this->Paginator->first('<<',array('separator'=>false,'title'=>'Trang đầu'));
				echo $this->Paginator->prev('<', array(), null, array('class' => 'prev disabled'));
				echo $this->Paginator->next('>', array(), null, array('class' => 'next disabled'));
//				echo $this->Paginator->numbers(array('separator'=>false,'modulus'=>3,'class'=>'number'));
				echo $this->Paginator->last('>>',array('separator'=>false,'title'=>'Trang cuối'));
			?>
		</div> <!-- end .paginator -->
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('User',array('type'=>'user','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('name','Tài khoản')?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created','Ngày tạo')?></th>
				<th class="small center">Action</th>
			</tr>
			<?php foreach($a_users_c as $val){
				$item_user = $val['User'];
				$url_edit = array('action'=>'edit',$item_user['id'],'?'=>array('url'=>$current_url_c));
			?>
			<tr id="<?php echo 'item_'.$item_user['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_user['id']?>" /></td>
				<td><?php echo $this->Html->link($item_user['username'],$url_edit,array('title'=>$item_user['name']))?></td>
				<td class="center">
					<?php 
						echo $this->Html->tag('p',date('d/m/Y',$item_user['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_user['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center action">
					<?php 
					if($item_user['group_id'] != '1') echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_user['id']});",array('title'=>'Xóa','class'=>'act delete ask','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->