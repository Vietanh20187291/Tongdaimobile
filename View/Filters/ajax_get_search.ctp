<?php
	if ( ! empty($results))
	{
?>
	<div class="autocomplete-search-list">
	<?php
		foreach ($results as $key => $val)
		{
			if($key < 10){
			$item_product = $val['Product'];
	?>
		<div class="autocomplete-search clearfix m-b-15" data-index="<?php echo $key?>">
				<div class="thumb">
					<?php
						$w = 100;
						$full_size = $oneweb_product['size']['product'];
						$h = intval($w*$full_size[1]/$full_size[0]);
						$url = array('controller' => 'products', 'action' => 'index', 'lang' => $item_product['lang']);

						$url['slug0'] = $item_product['slug'];
						$url['ext'] = 'html';

						$link_attr = array('title' => $item_product['meta_title'],'target' => $item_product['target'],'escape' => false);
						if ($item_product['rel'] != 'dofollow') $link_attr['rel'] = $item_product['rel'];
						echo $this->Html->link($this->OnewebVn->thumb('products/'.$item_product['image'],array('alt'=>$item_product['meta_title'],'width'=>$w,'height'=>$h, 'zc' => 1, 'class'=>'img-responsive'), array('escape' => false)),$url,$link_attr);
					?>
				</div>
				<div class="des">
					<?php
						echo $this->Html->link($item_product['name'],$url,$link_attr);?>
						<?php
							if (isset($item_product['price_new']) && $item_product['price_new'] > 0){
						?>
						<div class="price">
							<div class="old-price">
								<?php
										echo $this->Html->tag('del',number_format($item_product['price']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'')).' ';
									?>
							</div>
							<div class="new-price">
								<?php
										echo $this->Html->tag('span',number_format($item_product['price_new']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'new'));
									?>
							</div>
						</div>
						<?php
							} else {
						?>
						<div class="price">
							<div class="new-price">
								<?php
									if(!empty($item_product['price']))
										echo $this->Html->tag('span',number_format($item_product['price']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'new')).' ';
									else echo $this->Html->tag('span',__('Liên hệ'),array('class'=>'new'));
									?>
							</div>
						</div>
						<?php } ?>
				</div>
		</div>
	<?php
		}}
	?>
	</div>
<?php
	}else{
?>
	<?php echo __('Không tìm thấy sản phẩm nào') ?>
<?php } ?>