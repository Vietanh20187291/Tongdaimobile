<?php if(!empty($sum_rate_comment)){ ?>
<div id="statistic_coment">
	<div class="pull-left s_comemnt_info">
		<div class="bg_star_rate_big">
			<div class="star_rate_big">
				<p class="star_rate_big_2" style="width: <?php echo $total_rate_point*100/(5*$sum_rate_comment) ?>%">&nbsp;</p>
			</div>
		</div>
		<div class="s_comemnt_review">
			<span><?php echo $sum_rate_comment.' đánh giá' ?></span>
		</div>
	</div>
	<div class="pull-left">
		<div class="table table-responsive table-rate-comment">
			<table>
				<?php for($i = 5 ; $i > 0 ; $i--){ ?>
					<tr class="<?php echo $i ?>-star">
						<td>
							<div class="bg_star_rate_small">
								<div class="star_rate_small">
									<p class="star_rate_small_2" style="width: <?php echo $i*100/5 ?>%">&nbsp;</p>
								</div>
							</div>
						</td>
						<td>
							<span><?php echo '('.$rate[$i].')' ?></span>
						</td>
						<td>
							<div class="per_bar_cover">
								<div id="color_bar_5" class="per_bar" style="width: <?php echo $rate[$i]*100/($sum_rate_comment) ?>%"></div>
							</div>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>
<?php } ?>