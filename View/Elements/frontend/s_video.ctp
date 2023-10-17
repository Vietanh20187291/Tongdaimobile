<aside class="box video">
	<span class="title"><?php echo __('Video',true)?></span>
	<ul>
		<?php foreach ($data as $val){
			$item = $val['Video']	;
		?>
		<li>
			<iframe width="180" height="135" src="http://www.youtube.com/embed/<?php echo $item['youtube']?>?rel=0" allowfullscreen></iframe>
		</li>
		<?php }?>
	</ul>
	<p class="more"><?php echo $this->Html->link(__('Xem thêm',true),array('controller'=>'videos','action'=>'index','lang'=>$lang),array('title'=>__('Xem thêm',true),'class'=>'tooltip'))?></p>
			
</aside> <!--  end .box -->