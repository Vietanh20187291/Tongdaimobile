<ul>
	<?php 
		foreach($a_related_c as $val){
			$item_product = $val['Product'];
			$item_category = $val['ProductCategory'];
			
			$url_edit = array('controller'=>'products','action'=>'edit',$item_product['id']);
			$url_view = array('controller'=>'products','action'=>'index','lang'=>$item_product['lang']);
			$tmp = explode(',', $item_category['path']);
			for($i=0;$i<count($tmp);$i++){
				$url_view['slug'.$i]=$tmp[$i];
			}
			$url_view['slug'.count($tmp)] = $item_product['slug'];
			$url_view['ext']='html';
			$url_view['admin'] = false;
			
			if(!empty($item_product['image'])) $img = '/timthumb.php?src='.$this->Html->url('/webroot/img/images/products/').$item_product['image'];
			
			$img_small = $img."&h=40&w=50&zc=2";
	?>
	<li id="related_<?php echo $item_product['code']?>">
		<div class="thumb"><?php echo $this->Html->link($this->Html->image($img_small,array('alt'=>$item_product['name'])),$url_view,array('title'=>$item_product['name'],'target'=>'_blank','class'=>'preview','target'=>'_blank','escape'=>false))?></div>
		<div class="name"><?php echo $this->Html->link($this->Text->truncate($item_product['name'],18),$url_view,array('title'=>$item_product['name'],'target'=>'_blank'))?></div>
		<div class="act">
			<?php 
				echo $this->Html->link('&nbsp;',"javascript:;",array('title'=>__('Xóa',true),'class'=>'act delete','onclick'=>"delRelated($product_id_c,'{$item_product['code']}')",'escape'=>false))
			?>
		</div>
	</li>
	<?php }?>
</ul>