
<aside class="box poll">
	<span class="title"><?php echo $title;?></span>
	
	<?php echo $this->Form->create('Poll',array('id'=>'formPoll'))?>
	
	<ul>
		<?php 
		foreach ($data as $val){

			$item_poll_ques = $val['PollQuestion'];
			$a_poll_ac = $val['Poll'];
			if(!empty($item_poll_ques)){
		?>
		<li><?php echo $item_poll_ques['name'];?>
			<?php 
				if(!empty($a_poll_ac)){
			?>
			<ul>
				<?php foreach ($a_poll_ac as $val2){
					?>
				<li><input type="radio" name="<?php echo $item_poll_ques['id'];?>" value="<?php echo $val2['id'];?>" id="ratdep"> <label for="ratdep"><?php echo $val2['description'];?></label></li>
				<?php } ?>
			</ul>
			<?php } ?>
		</li>
		<?php } }?>
		
	</ul>
	<div class="submit">
		<input type="button" id="send_poll" value="Bình chọn">
		<a href="javascript:;" onclick="resultPoll()"  class="poll_result">Xem kết quả</a>
	</div>
	<?php echo $this->Form->end();?>
	
	<div id="poll_result"></div> <!--  end #poll_result -->
	
	<div class="top"></div>
	<div class="bottom"></div>
</aside> <!--  end .box -->