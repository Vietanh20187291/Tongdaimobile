<!-- start c_hotnew.ctp -->
<article class="box_content hotnew <?php if(!empty($class)) echo ' '.$class ?>">
	<div id="title_hotlnew" class="col-xs-1">
		<span>Hot news</span>
		<span class="icon"></span>
	</div>
	<div id="hot_new" class="show col-xs-12 col-sm-11 col-md-11">
		<marquee>
			<?php
			foreach($data as $val){
				$item_post = $val['Post'];
				$item_cate = $val['PostCategory'];

				$url = array('controller'=>'posts','action'=>'index','lang'=>$item_post['lang']);
				$tmp = explode(',', $item_cate['path']);
				for($i=0;$i<count($tmp);$i++){
					$url['slug'.$i]=$tmp[$i];
				}
				$url['slug'.count($tmp)] = $item_post['slug'];
				$url['ext']='html';

				$link_attr = array('title'=>$item_post['meta_title'],'target'=>$item_post['target']);
				if($item_post['rel']!='dofollow') $link_attr['rel'] = $item_post['rel'];

				$link_img_attr = array_merge($link_attr,array('escape'=>false));
			?>
			<p class="name"><?php echo $this->Html->link($item_post['name'],$url,$link_attr)?></p>
			<?php }?>
		</marquee>
	</div>
</article>
<!-- end c_hotnew.ctp -->