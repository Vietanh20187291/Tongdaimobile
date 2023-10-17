<aside class="box product_run<?php if(!empty($class)) echo ' '.$class ?>">
	<span class="title"><?php if(!empty($oneweb_product['display'][$position])) echo __($oneweb_product['display'][$position],true)?></span>
	
	<?php if(!empty($run)){?>
	<?php }?>
	
	<ul id="show_<?php echo $position ?>" class="show nav">
		<?php 
		//Kich thước ảnh thumbnail
		$full_size = $oneweb_product['size']['product'];
		$h = intval($w*$full_size[1]/$full_size[0]);
		
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
			<div class="thumb">
				<?php 
					if(!empty($item_product['discount'])) echo $this->Html->tag('span',(($item_product['discount_unit'])?$item_product['discount'].'%':''),array('class'=>'discount'));
					if(!empty($item_product['promotion'])) echo $this->Html->tag('span',' ',array('class'=>'promotion'));
					echo $this->Html->link($this->OnewebVn->thumb('products/'.$item_product['image'],array('alt'=>$item_product['meta_title'],'width'=>$w,'height'=>$h)),$url,$link_img_attr);
					
					if(!empty($oneweb_product['cart_button']) && !empty($oneweb_product['order'])){
				?>
				<p class="cart"><?php echo $this->Html->link(__('Mua',true),'javascript:;',array('title'=>__('Mua',true),'rel'=>'nofollow','onclick'=>"addToCart({$item_product['id']},1,true)"))?></p>
				<?php }?>
			</div> <!--  end .thumb -->
			<p class="name"><?php echo $this->Html->link($item_product['name'],$url,$link_attr)?></p>
			<p class="price">
				<?php 
					if($a_currency_c['location']=='first') echo $a_currency_c['name'].' ';
					
					$price = $item_product['price'];
					if(!empty($item_product['discount'])){
						echo $this->Html->tag('span',number_format($item_product['price']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']),array('class'=>'old')).' ';
						if($item_product['discount_unit'])	$price = $price-($price*$item_product['discount']/100);		//Giảm giá theo %
						else $price = $price - $item_product['discount'];												//Giảm số tiền nhập vao
					}
					echo $this->Html->tag('span',number_format($price/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']),array('class'=>'new'));
					
					if($a_currency_c['location']=='last') echo ' '.$a_currency_c['name'];
				?>
			</p>
		</li>
		<?php }?>
	</ul>

</aside> <!--  end .box -->