<?php //Lấy banner
if(is_array($data)){
	$extend = substr(strrchr($data['image'], "."), 1);
	if($extend=='swf'){
?>
<?php }else{
	$attr = array('alt'=>$data['name'],'class'=>'img-responsive', 'zc' => 1);
	if($size[0]!='n') $attr = array_merge($attr,array('width'=>$size[0]));
	if($size[1]!='n') $attr = array_merge($attr,array('height'=>$size[1]));

	$str_banner = $this->OnewebVn->thumb('banners/'.$data['image'],$attr);

	$link_attr = array('title'=>$data['name'],'target'=>$data['target'],'class'=>'','escape'=>false);
	if($data['rel']!='dofollow') $link_attr['rel'] = $data['rel'];

	if(!empty($data['link'])) $str_banner = $this->Html->link($str_banner,$data['link'],$link_attr);
	echo $str_banner;
	?>
	<?php
}}?>