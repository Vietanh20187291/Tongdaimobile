<?php 
	if(!empty($data)){
?>
<aside class="box tag">
	<span class="title">Tag</span>
	
	<p>
		<?php 
			foreach($data as $val){
				$item = $val['Tag'];
				echo $this->Html->link($item['name'],array('controller'=>'tags','action'=>'index','lang'=>$lang,'slug'=>$item['name']),array('title'=>$item['meta_title'],'class'=>$item['class'])).' ';
			}
		?>
	</p>
</aside> <!--  end .box -->
<?php }?>