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


	//Xóa newsletter
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
				<li class="delete"><?php echo __('Xóa',true)?></li>
			</ul>
		</div> <!--  end .box_select -->
		
		<?php echo $this->Form->create('Newsletter',array('url'=>array('action'=>'index'),'type'=>'get','name'=>'search','inputDefaults'=>array('div'=>false,'label'=>false)))?>
		<ul class="search">
			<li><?php echo $this->Form->input('keyword',array('value'=>(!empty($_GET['keyword']))?$_GET['keyword']:__('Email',true),'class'=>'larger','onblur'=>'if (this.value==""){ this.value="'.__('Email',true).'";}','onfocus'=>'if (this.value=="'.__('Email',true).'") { this.value=""; }','id'=>'keyword'))?></li>
			<li><?php echo $this->Form->submit('',array('class'=>'submit','div'=>false))?></li>
			<li class="counter"><?php echo __('Tìm thấy',true).' '.$counter_c.' '.__('email đăng ký',true)?></li>
		</ul> <!--  end .search_name -->
		<?php echo $this->Form->end();?>
		
		<?php echo $this->element('backend/paginate')?>
	</div> <!--  end #action_top -->
	
	<?php 
		echo $this->Form->create('Newsletter',array('type'=>'newsletter','name'=>'form','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('action',array('type'=>'hidden','name'=>'action','id'=>'action'));
	?>
	<div id="content">
		<table class="list">
			<tr class="first">
				<th class="small center"><input type="checkbox" name="chkall" value="yes" onclick="docheck(document.form.chkall.checked);"/></th>
				<th><?php echo $this->Paginator->sort('email','Email')?></th>
				<th class="small center"><?php echo $this->Paginator->sort('ip','Ip/Proxy')?></th>
				<th class="small center"><?php echo $this->Paginator->sort('created',__('Ngày tạo',true))?></th>
				<th class="small center"><?php echo __('Action',true)?></th>
			</tr>
			<?php foreach($a_newsletters_c as $val){
				$item_newsletter = $val['Newsletter'];
			?>
			<tr id="<?php echo 'item_'.$item_newsletter['id']?>">
				<td class="center"><input type="checkbox" name="chkid[]" value="<?php echo $item_newsletter['id']?>" /></td>
				<td><p class="name"><?php echo $item_newsletter['email']?></p></td>
				<td><?php echo $item_newsletter['ip']; if(!empty($item_newsletter['proxy'])) echo '/'.$item_newsletter['proxy']?></td>
				<td class="center">
					<?php 
						echo $this->Html->tag('p',date('d/m/Y',$item_newsletter['created']),array('class'=>'date'));
						echo $this->Html->tag('p',date('H:i:s',$item_newsletter['created']),array('class'=>'time'));
					?>
				</td>
				<td class="center">
					<?php 
						echo $this->Html->link('&nbsp;',"javascript:deleteItem({$item_newsletter['id']});",array('title'=>__('Xóa',true),'class'=>'act delete','escape'=>false))
					?>
				</td>
			</tr>
			<?php }?>
		</table> <!-- end .list -->
		<?php echo $this->element('backend/paginate',array('type'=>2))?>
	</div> <!--  end #content -->
	<?php echo $this->Form->end();?>
	
</div> <!--  end #column_right -->