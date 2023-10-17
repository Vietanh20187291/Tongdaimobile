<div class="bg_star_rate">
	<div class="star_rate">
		<p class="star_rate_2" style="width: <?php echo $star_rate*100/5 ?>%">&nbsp;</p>
		<?php
			if(!$this->Session->check('Rate.'.$model) || ($this->Session->check('Rate.'.$model) && !in_array($item_id, $this->Session->read('Rate.'.$model)))){
		?>
		<p class="star_rate_3">
			<?php for($i=1;$i<=10;$i++){?>
			<a href="#" class="<?php echo 'star'.$i?>" title="<?php echo $i.' '.__('sao',true)?>">

			</a>
			<?php }?>
		</p>
		<?php }?>
	</div>
	<p class="c"><?php echo (empty($star_rate)?'0':$star_rate).'/5 ('.number_format($star_rate_count).' '.__('bình chọn',true).')'?></p>
</div>
<!-- end c_rate.ctp -->