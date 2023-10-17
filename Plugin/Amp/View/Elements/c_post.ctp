<!-- start c_post.ctp -->
<?php
//Kich thước ảnh thumbnail
$full_size = $oneweb_post['size']['post'];

foreach($data as $key=>$val){
	$item_post = $val['Post'];
	$item_cate = $val['PostCategory'];

	$url = array('plugin'=>false, 'controller'=>'posts','action'=>'index','lang'=>$item_post['lang'],'position'=>$item_cate['position']);

	$tmp = explode(',', $item_cate['path']);
	for($i=0;$i<count($tmp);$i++){
		$url['slug'.$i]=$tmp[$i];
	}
	$url['slug'.count($tmp)] = $item_post['slug'];
	$url['ext']='html';

	$link_attr = array('title'=>$item_post['meta_title'],'target'=>$item_post['target'],'class'=>'name font-weight-bold');
	if($item_post['rel']!='dofollow') $link_attr['rel'] = $item_post['rel'];

	$link_img_attr = array_merge($link_attr,array('escape'=>false));
	$link_img_attr['class']='';
	$link_more_attr['title'] = __('Read more',true);
	$link_more_attr['class'] = 'readmore float_right';
	$h = intval($w*$full_size[1]/$full_size[0]);
?>
<div class="<?php if(!empty($class)) echo $class;?> c_post">
	<div class="row m-b-15">
	<div class="col-xs-12 m-b-15 line_height30">
		<?php echo $this->Html->link($item_post['name'],$url,$link_attr);?>
	</div>
	<div class="thumb col-lg-3 col-xs-12 col-sm-3 col-md-3">
		<?php echo $this->Html->link($this->HtmlAmp->thumbAmp('images/posts/'.$item_post['image'],array('alt'=>$item_post['meta_title'],'width'=>$w,'height'=>$h, 'layout'=>'responsive')),$url,$link_img_attr)?>
	</div> <!--  end .thumb -->
	<div class="col-lg-9 col-xs-12 col-sm-9 col-md-9">
		<?php
		if(!empty($datetime)) echo $this->Html->tag('p',date('d/m/Y',$item_post['created']),array('class'=>'datetime'));
		if(!empty($limit)) echo $this->Html->tag('p',$this->Text->truncate(trim(strip_tags($item_post['summary'])),$limit,array('exact'=>false)),array('class'=>'sumary'));
		// echo $this->Html->link('Chi tiết',$url,$link_more_attr);
	?>
	</div>
	</div>
</div> <!--  end .box_post -->
<?php }?>
<!-- end c_post.ctp -->