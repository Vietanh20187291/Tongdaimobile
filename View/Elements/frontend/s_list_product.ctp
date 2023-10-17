<aside class="box post m-t-15 bg_white p-b-15">
	<div class="block-title">
		<div class="title"><span class="icon"></span>
			<span class="title_text"><?php echo $title['name']?></span>
		</div>
	</div>
	<ul class="nav">
			<?php 
			
			foreach($data as $val){
				$item_product = $val['Product'];
				$item_cate = $val['ProductCategory'];
				
				$url = array('controller'=>'products','action'=>'index','lang'=>$item_product['lang']);
				$tmp = explode(',', $item_cate['path']);
				for($i=0;$i<count($tmp);$i++){
					$url['slug'.$i]=$tmp[$i];
				}
				$url['slug'.count($tmp)] = $item_product['slug'];
				$url['ext']='html';
				
				$link_attr = array('title'=>$item_product['meta_title'],'target'=>$item_product['target'],'class'=>'');
				if($item_product['rel']!='dofollow') $link_attr['rel'] = $item_product['rel']; 
				
				$link_img_attr = array_merge($link_attr,array('escape'=>false));
			?>
			<li>
				<span class="icon_oneweb icon_li"></span>
				<?php echo $this->Html->link($item_product['name'],$url,$link_attr)?>
			</li>
			<?php }?>
		</ul>
</aside>