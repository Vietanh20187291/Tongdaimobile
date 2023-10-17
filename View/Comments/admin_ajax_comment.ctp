<div class="comment_2">
	<div class="comment_3">
		<ul>
			<?php 
			foreach($a_comments_c as $val){
				$item_comment = $val['Comment'];
				$item_user = $val['Member'];
			?>
			<li id="<?php echo 'comment_'.$item_comment['id']?>">
				<p class="title">
					<a href="javascript:;" class="act status <?php echo ($item_comment['status'])?'active':'unactive';?>" onclick="changeStatusComment(<?php echo $item_comment['id']?>,'<?php echo $item_comment['model']?>')" title="Thay đổi">&nbsp;</a>
					<span class="title"><?php echo $item_comment['name'].' - '.$item_comment['email']?></span>
					<span class="like"><?php echo __('Thích',true)?> (<?php echo $item_comment['like']?>)</span>
					<span class="datetime">(<?php echo date('d/m/Y - H:i:s',$item_comment['created'])?>)</span>
					<a href="javascript:;" class="act edit" onclick="editComment(<?php echo $item_comment['id']?>)" title="<?php echo __('Sửa',true) ?>">&nbsp;</a>
					<a href="javascript:;" class="act delete" onclick="delComment(<?php echo $item_comment['id']?>)" title="<?php echo __('Xóa',true) ?>">&nbsp;</a>
				</p>
				<p class="des"><?php echo $item_comment['description']?></p>
				<p class="answer"><?php echo $this->Html->link(__('Trả lời',true),'javascript:;',array('title'=>__('Trả lời',true),'onclick'=>"answer({$item_comment['id']})"))?></p>
				
				<div class="form answer_<?php echo $item_comment['id']?>">
					<?php 
						echo $this->Form->create('Comment',array('inputDefaults'=>array('div'=>false),'id'=>'form_comment_answer_'.$item_comment['id']));
						echo $this->Form->input('item_id',array('type'=>'hidden','value'=>$item_id_c));
						echo $this->Form->input('model',array('type'=>'hidden','value'=>$model_c));
						echo $this->Form->input('parent_id',array('type'=>'hidden','value'=>$item_comment['id']));
					?>
					<p><?php echo $this->Form->input('name',array('class'=>'larger','label'=>__('Họ tên',true).' <span class="im">*</span>','value'=>$admin['name']))?></p>
					<p><?php echo $this->Form->input('email',array('class'=>'larger','label'=>'Email <span class="im">*</span>','value'=>'admin@'.$_SERVER['HTTP_HOST']))?></p>
					<p><?php echo $this->Form->input('description', array('type'=> 'textarea','label'=>__('Trả lời',true).' <span class="im">*</span>'));?></p>
					<p>
						<?php 
							echo $this->Form->button(__('Đồng ý',true),array('class'=>'submit','onclick'=>"addCommentAnswer({$item_comment['id']}); return false"));
						?>
					</p>
					<?php echo $this->Form->end();?>
				</div> <!--  end .form -->
				
				<?php if(!empty($val['ChildComment'])){?>
				<ul class="child">
					<?php foreach ($val['ChildComment'] as $val2){?>
					<li id="<?php echo 'comment_'.$val2['id']?>">
						<p class="title">
							<a href="javascript:;" class="act status <?php echo ($val2['status'])?'active':'unactive';?>" onclick="changeStatusComment(<?php echo $val2['id']?>,'<?php echo $val2['model']?>')" title="<?php echo __('Thay đổi',true)?>">&nbsp;</a>
							<span class="title"><?php echo $val2['name'].' - '.$val2['email']?></span>
							<span class="like"><?php echo __('Thích',true)?> (<?php echo $val2['like']?>)</span>
							<span class="datetime">(<?php echo date('d/m/Y - H:i:s',$val2['created'])?>)</span>
							<a href="javascript:;" class="act edit" onclick="editComment(<?php echo $val2['id']?>)" title="<?php echo __('Sửa',true)?>">&nbsp;</a>
							<a href="javascript:;" class="act delete" onclick="delComment(<?php echo $val2['id']?>)" title="<?php echo __('Xóa',true)?>">&nbsp;</a>
						</p>
						<p class="des"><?php echo $val2['description']?></p>
					</li>
					<?php }?>
				</ul>
				<?php }?>
			</li>
			<?php }?>
		</ul>
		
		<div class="form" id="write_comment">
			<h3><?php echo __('Viết bình luận',true)?></h3>
			<?php 
				echo $this->Form->create('Comment',array('inputDefaults'=>array('div'=>false),'id'=>'form_comment'));
				echo $this->Form->input('item_id',array('type'=>'hidden','value'=>$item_id_c));
				echo $this->Form->input('model',array('type'=>'hidden','value'=>$model_c));
			?>
			<p><?php echo $this->Form->input('name',array('class'=>'larger','label'=>__('Họ tên',true).' <span class="im">*</span>','value'=>$admin['name']))?></p>
			<p><?php echo $this->Form->input('email',array('class'=>'larger','label'=>'Email <span class="im">*</span>','value'=>'admin@'.$_SERVER['HTTP_HOST']))?></p>
			<p><?php echo $this->Form->input('description', array('type'=> 'textarea','label'=>__('Bình luận',true).' <span class="im">*</span>'));?></p>
			<p>
				<?php 
					echo $this->Form->button(__('Đồng ý',true),array('class'=>'submit','onclick'=>'addComment(); return false'));
					echo $this->Form->button(__('Thoát',true),array('class'=>'submit close','onclick'=>'closeComment(); return false'))
				?>
			</p>
			<?php echo $this->Form->end();?>
		</div> <!--  end .form -->
		
		<a class="write_comment" href="#write_comment" title="<?php echo __('Viết bình luận',true)?>"><?php echo __('Viết bình luận',true)?></a>
	</div> <!--  end .comment_3 -->
</div> <!--  end .comment_2-->
<div id="mask_comment" onclick="closeComment()"></div> 



<script type="text/javascript">
	//Thêm comment
	function addComment(){
		data = $("#form_comment").serialize();
		
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxAddComment'))?>',
			data:data,
			dataType:'json',
			success:function(result){
				if(!result.error){
					comment(<?php echo $item_id_c?>,'<?php echo $model_c?>');
				}else{
					if(result.empty!=''){
						alert('Bạn chưa nhập '+result.empty)
					}else alert('<?php echo __('Có lỗi, bạn vui lòng thử lại',true)?>')
				}
			}
		})
	}

	//Trả lời
	function addCommentAnswer(id){
		data = $("#form_comment_answer_"+id).serialize();
		
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxAddComment'))?>',
			data:data,
			dataType:'json',
			success:function(result){
				if(!result.error){
					comment(<?php echo $item_id_c?>,'<?php echo $model_c?>');
				}else{
					if(result.empty!=''){
						alert('Bạn chưa nhập '+result.empty)
					}else alert('<?php echo __('Có lỗi, bạn vui lòng thử lại',true)?>')
				}
			}
		})
	}

	function answer(id){
		$(".answer_"+id).toggle({},40);
	}

	//Xóa comment
	function delComment(id){
		var c = confirm("<?php echo __('Bạn có chắc chắn muốn xóa comment này không',true)?>?");
		if(c==true){
			$.ajax({
				type:'post',
				url:'<?php echo $this->Html->url(array('action'=>'ajaxDelComment'))?>',
				data:'id='+id,
				success:function(result){
					if(result) $("#comment_"+id).hide();
					else alert('<?php echo __('Có lỗi, bạn vui lòng thử lại',true)?>');
				}
			})
		}
	}

	//Hieu ung hien thi
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
	
	//Thay đổi trạng thái
	function changeStatusComment(id,model){
		$.ajax({
				type:'post',
				url: '<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxChangeStatus'));?>',
				data: 'id='+id+'&model='+model,
				beforeSend: function(){
					$("#loading").show();
				},
				dataType:'json',
				success: function(result){
					$("#comment_"+id+" a.status").removeClass(result.remove);
					$("#comment_"+id+" a.status").addClass(result.add);
					$("#loading").hide();
				}
			})
	}
</script>