<?php $controller = $this->params['controller'];
?>
<aside class="box post">
	<div class="title">
		<i class="fa fa-list-ul" aria-hidden="true"></i>
		<?php echo __('Danh mục bài viết',true)?>
	</div>
	<div class="nav-v-post" id="tree">
		<ul class="nav">
		<?php foreach ($a_post_categories_s as $val){
			$item_cate = $val['PostCategory'];
			$item_post = $val['Post'];
			if(empty($item_cate['link'])){
				$url = array('plugin'=>false,'controller'=>'posts','action' => 'index','lang'=>$item_cate['lang'],'position'=>$item_cate['position']);
				$tmp = explode(',', $item_cate['path']);
				for($i=0;$i<count($tmp);$i++){
					$url = array_merge($url,array('slug'.$i=>$tmp[$i]));
				}

			}else $url = $item_cate['link'];
			$link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target'],'class'=>'');
			if($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel'];
			?>
			<li>
				<?php echo $this->Html->link($item_cate['name'], $url,$link_attr);?>
				<?php
					if(!empty($item_post)){
				?>
					<ul>
						<?php foreach($item_post as $val1){
						$url = array('plugin'=>false,'controller'=>'posts','action'=>'index','lang'=>$val1['lang'],'position'=>$item_cate['position']);

							$tmp = explode(',', $item_cate['path']);
							for($i=0;$i<count($tmp);$i++){
								$url['slug'.$i]=$tmp[$i];
							}
							$url['slug'.count($tmp)] = $val1['slug'];
							$url['ext']='html';
							?>
							<li class="m-b-15">
								<?php echo $this->Html->link($this->Text->truncate($val1['name'],60,array('exact'=>false)),$url,$link_attr);?>
							</li>
						<?php }?>
					</ul>
				<?php }?>
			</li>
		<?php }?>
			</ul>
	</div>
</aside>
