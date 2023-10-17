<script type="text/javascript">
function comment(id,model){
	$.ajax({
		type: 'post',
		url : '<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxComment'))?>',
		data: 'item_id='+id+'&model='+model,
		beforeSend:function(){
			$("#loading").show();
		},
		success:function(result){
			$("#loading").hide();
			$("#comment").show();
			$("#comment").html(result);
		}
	});
}

//Thoat comment
function closeComment(){
	$("#comment").hide();
}

//Sửa nhận xét
function editComment(id){
	$.ajax({
		type: 'post',
		url : '<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxEditComment'))?>',
		data: 'id='+id,
		beforeSend:function(){
			$("#loading").show();
		},
		success:function(result){
			$("#loading").hide();
			$("#comment").show();
			$("#comment").html(result);
		}
	})
}
</script>

<div id="comment"></div> <!--  end #comment -->