<div id="show_post_related" >
	<ul>
		<?php
		//Kich thước ảnh thumbnail
		$full_size = $oneweb_post['size']['post'];
		$h = intval($w*$full_size[1]/$full_size[0]);
		$count_item = count($data);
		for($j=0;$j<$count_item;$j++){
				$item_post = $data[$j]['Post'];
				$item_cate = $data[$j]['PostCategory'];
				$url = array('plugin'=>false, 'controller'=>'posts','action'=>'index','lang'=>$item_post['lang'],'position'=>$item_cate['position']);

				$tmp = explode(',', $item_cate['path']);
				for($i=0;$i<count($tmp);$i++){
					$url['slug'.$i]=$tmp[$i];
				}
				$url['slug'.count($tmp)] = $item_post['slug'];
				$url['ext']='html';

				$link_attr = array('title'=>$item_post['meta_title'],'target'=>$item_post['target'],'class'=>'name');
				if($item_post['rel']!='dofollow') $link_attr['rel'] = $item_post['rel'];
				$link_img_attr = array_merge($link_attr,array('escape'=>false));
				$link_img_attr['class']='';
				$link_more_attr['title'] = __('Read more',true);
				$link_more_attr['class'] = 'readmore float_right';
			?>
			<li class="<?php if(!empty($class)) echo $class; ?>">
				<i class="dot"></i>
				<?php
					echo $this->Html->link($item_post['name'],$url,$link_attr);
				?>
			</li>
		<?php }?>
	</ul>
</div>
