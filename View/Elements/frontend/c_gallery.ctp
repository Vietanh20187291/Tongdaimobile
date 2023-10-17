<!-- start c_gallery.ctp -->
<?php
//Kich thước ảnh thumbnail
$full_size = $oneweb_media['size']['gallery'];
$w = 249;
$h = intval($w*$full_size[1]/$full_size[0]);

foreach($data as $val){
	$item_gallery = $val['Gallery'];
	$item_cate = $val['GalleryCategory'];

	$url = array('controller'=>'galleries','action'=>'index','lang'=>$lang,'slug0'=>$item_cate['slug'],'slug1'=>$item_gallery['slug'],'ext'=>'html');

	$link_attr = array('title'=>$item_gallery['meta_title'],'target'=>$item_gallery['target'],'class'=>'');
	if($item_gallery['rel']!='dofollow') $link_attr['rel'] = $item_gallery['rel'];

	$link_img_attr = array_merge($link_attr,array('escape'=>false));
?>
<div class="box_gallery col-xs-12 col-sm-4 col-md-4">
	<div class="box_gallery_bottom">
		<div class="box_gallery_middle">
			<div class="thumb">
				<?php echo $this->Html->link($this->OnewebVn->thumb('galleries/'.$item_gallery['image'],array('alt'=>$item_gallery['meta_title'],'width'=>$w,'height'=>$h)),$url,$link_img_attr)?>
			</div> <!--  end .thumb -->
			<p class="name"><?php echo $this->Html->link($this->Text->truncate($item_gallery['name'],50,array('exact'=>false)),$url,$link_attr)?></p>
		</div>
	</div>
</div>
<?php }?>
<!-- end c_gallery.ctp -->