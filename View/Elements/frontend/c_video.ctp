<?php foreach($data as $val){
	$item_video = $val['Video'];
	$item_cate = $val['VideoCategory'];
	$url = array('controller'=>'videos','action'=>'index','lang'=>$lang,'slug0'=>$item_cate['slug'],'slug1'=>$item_video['slug'],'ext'=>'html');
	
	$link_attr = array('title'=>$item_video['meta_title'],'target'=>$item_video['target'],'class'=>'name tooltip');
	if($item_video['rel']!='dofollow') $link_attr['rel'] = $item_video['rel']; 
	
	$link_img_attr = array_merge($link_attr,array('escape'=>false));
	$link_img_attr['class'] = 'tooltip';
?>
<div class="box_video">
	<div class="thumb">
		<?php echo $this->Html->link($this->Html->image('http://i.ytimg.com/vi/'.$item_video['youtube'].'/1.jpg',array('alt'=>$item_video['meta_title'])),$url,$link_img_attr)?>
	</div> <!--  end .thumb -->
	<?php echo $this->Html->link($this->Text->truncate($item_video['name'],40,array('exact'=>false)),$url,$link_attr)?>
</div> <!--  end .box_video -->
<?php }?>