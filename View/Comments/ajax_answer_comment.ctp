<div class="modal-dialog dialog-comment">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button id="close-btn" type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-uppercase"><?php echo __('Trả lời',true)?></h4>
      </div>
      <div class="modal-body">
      	<?php
      		$item_comment = $a_comment_c['Comment'];
	      	echo $this->Form->create('Comment',array('id'=>'form_comment_'.$item_comment['id'],'inputDefaults'=>array('label'=>false,'div'=>false)));
      		echo $this->Form->input('parent_id',array('type'=>'hidden','value'=>$item_comment['id']));
      		echo $this->Form->input('item_id',array('type'=>'hidden','value'=>$item_comment['item_id']));
      		echo $this->Form->input('model',array('type'=>'hidden','value'=>$item_comment['model']));
      	?>
      	<div class="form-group">
      		<?php echo $this->Form->label('name',__('Họ và tên',true));?> <span class="im">*</span>
      		<?php echo $this->Form->input('name',array('class'=>'form-control'));?>
      	</div>
      	<div class="form-group">
      		<?php echo $this->Form->label('description',__('Ý kiến của bạn',true));?> <span class="im">*</span>
      		<?php echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control'));?>
      	</div>
      	<div class="form-group text-center">
      		<?php echo $this->Form->button(__('Gửi',true),array('class'=>'submit btn btn-primary','onclick'=>"addAnswerComment({$item_comment['id']}); return false"));?>
      	</div>
				<?php echo $this->Form->end();?>
      </div>
    </div>
  </div>
<script type="text/javascript">
	//Trả lời comment
	function addAnswerComment(id){
		data = $("#form_comment_"+id).serialize();
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxAddComment','lang'=>$lang))?>',
			data:data,
			dataType:'json',
			beforeSend: function(){
				$("#message_top").show();
			},
			success:function(result){
				$("#message_top").hide();
				if(result.error){
					if(result.empty!=''){
						alert(result.empty);
					}else alert('<?php echo __('Có lỗi, bạn vui lòng thử lại',true)?>');
				}else{
					alert('<?php echo __('Cảm ơn bạn đã cho ý kiến',true)?>');
					$('.form_answer').text('');
					$('#popup_modal').modal('hide');
					$('#button_'+id+' a.answer').hide();

				};
			}
		});
	};

</script>