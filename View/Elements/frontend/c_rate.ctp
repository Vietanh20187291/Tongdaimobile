<!-- start c_rate.ctp -->
<script type="text/javascript">
	//Rate
	function starRate(val,id,model){
		$.ajax({
			type:'post',
			url:'<?php echo $this->Html->url(array('controller'=>'pages','action'=>'ajaxStarRate','lang'=>$lang))?>',
			data:'id='+id+'&val='+val+'&model='+model,
			beforeSend:function(){
				$("#message_top").show();
			},
			success: function(result){
				$(".star_rate p.star_rate_2").css('width',result+'%');
				$(".star_rate p.star_rate_3").hide();
				$("#message_top").hide();
				alert('<?php echo __('Cảm ơn bạn đã đánh giá',true)?>');
			}
		})
	}
</script>

<div class="bg_star_rate">
	<div class="star_rate">
		<p class="star_rate_2" style="width: <?php echo $star_rate*100/5 ?>%">&nbsp;</p>
		<?php
			if(!$this->Session->check('Rate.'.$model) || ($this->Session->check('Rate.'.$model) && !in_array($item_id, $this->Session->read('Rate.'.$model)))){
		?>
		<p class="star_rate_3">
			<?php for($i=1;$i<=10;$i++){?>
			<a href="javascript:;" onclick="starRate(<?php echo $i?>,<?php echo $item_id?>,'<?php echo $model?>')" class="<?php echo 'star'.$i?>" title="<?php echo $i.' '.__('sao',true)?>">

			</a>
			<?php }?>
		</p>
		<?php }?>
	</div>
	<p class="c"><?php echo (empty($star_rate)?'0':$star_rate).'/5 ('.number_format($star_rate_count).' '.__('bình chọn',true).')'?></p>
</div>
<!-- end c_rate.ctp -->