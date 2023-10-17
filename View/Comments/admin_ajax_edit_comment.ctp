<div class="comment_2">
	<div class="comment_3">
		<div id="edit_comment"  class="form">
			<h3><?php echo __('Sửa bình luận',true)?></h3>
			<?php 
				echo $this->Form->create('Comment',array('inputDefaults'=>array('div'=>false),'id'=>'form_comment'));
				echo $this->Form->input('id',array('type'=>'hidden'));
			?>
			<p><?php echo $this->Form->input('name',array('class'=>'larger','label'=>__('Họ tên',true).' <span class="im">*</span>'))?></p>
			<p><?php echo $this->Form->input('email',array('class'=>'larger','label'=>'Email <span class="im">*</span>'))?></p>
			<p><?php echo $this->Form->input('description', array('type'=> 'textarea','label'=>__('Bình luận',true).' <span class="im">*</span>'));?></p>
			<p>
				<?php 
					echo $this->Form->button(__('Đồng ý',true),array('class'=>'submit','onclick'=>'editCommentStep2(); return false'));
					echo $this->Form->button(__('Trở lại',true),array('class'=>'submit close','onclick'=>"comment({$this->request->data['Comment']['item_id']},'{$this->request->data['Comment']['model']}'); return false"))
				?>
			</p>
			<?php echo $this->Form->end();?>
		</div> <!--  end #edit_comment -->
		
	</div> <!--  end .comment_3 -->
</div> <!--  end .comment_2-->
<div id="mask_comment" onclick="closeComment()"></div> 


<script type="text/javascript">
	//Sửa comment
	function editCommentStep2(){
		data = $("#form_comment").serialize();
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxEditCommentStep2'))?>',
			data:data,
			success:function(result){
				if(result){
					comment(<?php echo $this->request->data['Comment']['item_id']?>,'<?php echo $this->request->data['Comment']['model']?>');
				}else{
					alert('<?php echo __('Có lỗi, bạn vui lòng thử lại',true)?>')
				}
			}
		})
	}

	$(document).ready(function(){
		$(".comment_2").animate({opacity:"1"},300);
		$(".comment_2").animate({left:"6px"},15);
		$(".comment_2").animate({left:"-6px"},15);
		$(".comment_2").animate({left:"6px"},15);
		$(".comment_2").animate({left:"-6px"},15);
		$(".comment_2").animate({left:"6px"},15);
		$(".comment_2").animate({left:"-6px"},15);
		$(".comment_2").animate({left:"6px"},15);
		$(".comment_2").animate({left:"-6px"},15);
		$(".comment_2").animate({left:"6px"},15);
		$(".comment_2").animate({left:"-6px"},15);
	})
</script>