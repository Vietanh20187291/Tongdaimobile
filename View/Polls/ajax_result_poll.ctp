<?php 
	if(!empty($a_poll_ques_c)){
?>
<script type="text/javascript">
<!--
$("#poll_result a.close").click(function(){
	$("#poll_result").fadeOut(200);
});
//-->
</script>
<div>
		<a class="close" href="javascript:;">&nbsp;</a>
		<ul class="poll_result_ques">
		<?php 
			foreach($a_poll_ques_c as $val){
			$item_poll_ques = $val['PollQuestion'];
			$item_poll = $val['Poll'];
		?>
			<li><?php echo $item_poll_ques['name'];?>
				<ul>
					<?php foreach($item_poll as $val2){
						$item_poll2 = $val2['Poll'];
						?>
					<li>
						<p><?php echo $item_poll2['description']?> (<?php echo $item_poll2['number'];?> phiếu)</p>
						<div><p style="width: <?php echo round($item_poll2['phantram'])?>%"><?php echo round($item_poll2['phantram']);?>%</p></div>
					</li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			
		</ul>
	</div>
	<?php }?>