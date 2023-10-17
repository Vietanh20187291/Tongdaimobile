<?php
echo $this->Html->script('link_hover');
if(!empty($a_comments_c)){?>
<!-- <div class="title2">
	<span><?php //echo __('Có',true).' '.number_format($total_comment_c).' '.__('ý kiến',true)?></span>
	<a href="#write_comment" class="write" rel="nofollow"><?php //echo __('Viết nhận xét',true)?></a>
</div>
 --><ul class="comment">
	<?php foreach($a_comments_c as $val){
		$item_comment = $val['Comment'];
	?>
	<li>
        <div class="comment_info">
            <p class="pull-left"><span class="name"><?php echo $item_comment['name']?></span> <span class="time"><?php echo date('H:i, d/m/Y',$item_comment['created'])?></span></p>
            <?php if(!empty($item_comment['star'])){ ?>
                <div class="pull-right bg_star_rate_small">
                    <div class="star_rate_small">
                        <p class="star_rate_small_2" style="width: <?php echo $item_comment['star']*100/5 ?>%">&nbsp;</p>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="clear"></div>
		<div class="less_<?php echo $item_comment['id']?>">
			<?php
				echo strip_tags($item_comment['description']);
				if(strlen($item_comment['description'])>200) echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act add','title'=>__('Xem thêm',true),'onclick'=>"more_description({$item_comment['id']})",'escape'=>false));
			?>
		</div>
		<div class="more_<?php echo $item_comment['id']?>" style="display:none">
			<?php
				echo $item_comment['description'];
				echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act sub','title'=>__('Rút gọn',true),'onclick'=>"less_description({$item_comment['id']})",'escape'=>false));
			?>
		</div>

		<?php
			$flag_comment = false;		//Được phép comment hay ko
			if(!$this->Session->check('Comment.'.$item_comment['model'].'.id') || ($this->Session->check('Comment.'.$item_comment['model'].'.id') && !in_array($item_comment['id'], $this->Session->read('Comment.'.$item_comment['model'].'.id')))) $flag_comment = true;

			$flag_like = false;		//Được phép like hay ko
			if(!$this->Session->check('Comment.'.$item_comment['model'].'.like') || ($this->Session->check('Comment.'.$item_comment['model'].'.like') && !in_array($item_comment['id'], $this->Session->read('Comment.'.$item_comment['model'].'.like')))) $flag_like = true;
			if(empty($val['ChildComment'])) echo $this->Html->link('<i class="fa fa-reply"></i>'.__('Trả lời',true),'javascript:;',array('title'=>__('Trả lời',true),'onclick'=>"answer({$item_comment['id']})",'rel'=>'nofollow','class'=>'answer text-success','escape'=>false));
		?>
		<!-- <p class="button" id="button_<?php //echo $item_comment['id']?>">
			<?php
				//if($flag_comment) echo $this->Html->link(__('Trả lời',true),'javascript:;',array('title'=>__('Trả lời',true),'onclick'=>"answer({$item_comment['id']})",'rel'=>'nofollow','class'=>'answer'));
				//echo $this->Html->link(__('Thích',true).' | '.$this->Html->tag('span','&nbsp;',array('class'=>'like')).$this->Html->tag('span',$item_comment['like'],array('class'=>'like_number')),'javascript:;',array('title'=>__('Thích',true),'onclick'=>($flag_like)?"like({$item_comment['id']})":'nolike()','rel'=>'nofollow','class'=>'like'.((!$flag_like)?' no_like':''),'escape'=>false));
			?>
		</p> -->

		<!-- <div id="answer_<?php echo $item_comment['id']?>" class="form_answer"></div> -->

		<?php if(!empty($val['ChildComment'])){?>
		<ul class="child">
			<div class="arrow-top"></div>
			<?php foreach ($val['ChildComment'] as $key=>$val_child){?>
			<li class="<?php if(empty($val_child['user_id'])) echo 'bg-white' ?>">
				<?php if(!empty($val_child['user_id'])){ ?>
				<p><span class="name">Memart</span><span class="qtv">QTV</span>
				<?php } else { ?>
				<p><span class="name"><?php echo $val_child['name'] ?></span>
				<?php } ?>
					<span class="time"><?php echo date('H:i, d/m/Y',$val_child['created'])?></span></p>
				<div class="less_<?php echo $val_child['id']?>">
					<?php
						echo strip_tags($val_child['description']);
						if(strlen($val_child['description'])>200) echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act add','title'=>__('Xem thêm',true),'onclick'=>"more_description({$val_child['id']})",'escape'=>false));
					?>
				</div>
				<div class="more_<?php echo $val_child['id']?>" style="display:none">
					<?php
						echo $val_child['description'];
						echo $this->Html->link('&nbsp;','javascript:;',array('class'=>'act sub','title'=>__('Rút gọn',true),'onclick'=>"less_description({$val_child['id']})",'escape'=>false));
					?>
				</div>

				<?php
				if($key+1 == count($val['ChildComment']) ) echo $this->Html->link('<i class="fa fa-reply"></i>'.__('Trả lời',true),'javascript:;',array('title'=>__('Trả lời',true),'onclick'=>"answer({$item_comment['id']})",'rel'=>'nofollow','class'=>'answer text-success','escape'=>false));
					$flag_like2 = false;
					if(!$this->Session->check('Comment.'.$val_child['model'].'.like') || ($this->Session->check('Comment.'.$val_child['model'].'.like') && !in_array($val_child['id'], $this->Session->read('Comment.'.$val_child['model'].'.like')))) $flag_like2 = true;
				?>
<!-- 				<p class="button" id="button_<?php //echo $val_child['id']?>">
					<?php //echo $this->Html->link(__('Thích',true).' | '.$this->Html->tag('span','&nbsp;',array('class'=>'like')).$this->Html->tag('span',$val_child['like'],array('class'=>'like_number')),'javascript:;',array('title'=>__('Thích',true),'onclick'=>($flag_like2)?"like({$val_child['id']})":'nolike()','rel'=>'nofollow','class'=>'like'.((!$flag_like2)?' no_like':''),'escape'=>false));?>
				</p> -->
			</li>
			<?php }?>
		</ul>
		<?php }?>
	</li>
	<?php }?>
</ul> <!--  end .comment -->

<div class="paginator" id="backTop">
	<span class="page"><?php echo $a_page_c['current'].'/'.$a_page_c['total']?></span>
	<?php
		$module = 7;		//Số trang hiển thị
		$limit = (ceil($module/2)>$a_page_c['total'])?$a_page_c['total']:ceil($module/2);
		$page_start = (($limit-($module-ceil($module/2)))>0)?($limit-($module-ceil($module/2))):1;
		if($a_page_c['current']!=1)
			echo $this->Html->tag('span',$this->Html->link('&lt;&lt;','javascript:;',array('rel'=>'nofollow','onclick'=>"getComment(1)",'escape'=>false)),array('class'=>'number'));
		for($i=$page_start;$i<=$limit;$i++){
			if($a_page_c['current']==$i)
				echo $this->Html->tag('span',$i,array('class'=>'number current'));
			else echo $this->Html->tag('span',$this->Html->link($i,'javascript:;',array('rel'=>'nofollow','onclick'=>"getComment($i)")),array('class'=>'number'));
		}
		if($a_page_c['current']!=$a_page_c['total'])
			echo $this->Html->tag('span',$this->Html->link('&gt;&gt;','javascript:;',array('rel'=>'nofollow','onclick'=>"getComment({$a_page_c['total']})",'escape'=>false)),array('class'=>'number'));
	?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#backTop a").click(function(){
			var pos = $("#loading_comment").offset();
			$('body,html').animate({
				scrollTop: pos.top
			}, 500);
			return false;
		});

		$("a.write").click(function(){
			id = $(this).attr('href');

			var pos = $(id).offset();
			$('body,html').animate({
				scrollTop: pos.top
			}, 500);
			return false;
		});
	});

	//Thich
	function like(id){
		$.ajax({
			type: 'post',
			url: '<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxLike','lang'=>$lang))?>',
			data:'id='+id,
			beforeSend: function(){
				$("#message_top").show();
			},
			success: function(result){
				$("#message_top").hide();
				$('#button_'+id+' span.like_number').text(result);
				$('#button_'+id+' a.like').attr('onclick','nolike()');
				$('#button_'+id+' a.like').addClass('no_like');
			}
		});
	};

	function nolike(){
		alert('<?php echo __('Bạn đã sử dụng chức năng này.',true).'\n\r'.__('Bạn phải đợi 24h nữa mới được sử dụng tiếp.',true)?>');
	};

	//Trả lời comment
	function answer(id){
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('controller'=>'comments','action'=>'ajaxAnswerComment','lang'=>$lang));?>',
			data:'id='+id,
			beforeSend: function(){
				$("#message_top").show();
			},
			success: function(result){
				$("#message_top").hide();
				$("#popup_modal").html(result);
				$("#popup_modal").modal();
			}
		});
	}
</script>
<?php }?>
