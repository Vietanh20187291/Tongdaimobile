<!-- start c_product.ctp -->
<?php
	if ( empty($class)) $class = 'col-xs-6 col-sm-6 col-md-3 col-lg-3';
	$full_size = $oneweb_product['size']['product'];
	$h = intval($w*$full_size[1]/$full_size[0]);
	$start = ($page - 1) * $page_limit;
	foreach ($data as $k => $val)
	{
	if($k >= $start && $k <($start + $page_limit)){
		$item_product = $val['Product'];

		$url = array('controller' => 'products', 'action' => 'index', 'lang' => $item_product['lang']);

		$url['slug0'] = $item_product['slug'];
		$url['ext'] = 'html';

		$link_attr = array('title' => $item_product['meta_title'],'target' => $item_product['target'],'class' => 'name');
		if ($item_product['rel'] != 'dofollow') $link_attr['rel'] = $item_product['rel'];

		$link_img_attr = array_merge($link_attr, array('escape' => false));
		$link_img_attr['class'] = '';
?>
	<div class="<?php echo $class; ?> item c-product">
		<div class="thumb margin_bottom15 item_product_thumb">
			<div class="text-center">
				<?php
					if ( ! empty($item_product['hot'])) echo $this->Html->tag('span class="icon_oneweb icon-hot"','');
					echo $this->Html->link($this->OnewebVn->thumb('products/'.$item_product['image'],array('alt'=>$item_product['meta_title'],'width'=>$w,'height'=>$h, 'zc' => 2, 'class'=>'img-responsive')),$url,$link_img_attr);
				?>
			</div>
			<?php
				if ( $oneweb_product['order'])
				{
			?>

			<!-- Nút mua hàng -->
			<?php echo $this->Html->link('<button onclick="return buy('.$item_product['id'].')"><i class="icon_oneweb icon_cart"></i>'.__('Mua hàng',true).'</button>', $url, array('rel'=>'nofollow','class'=>'mask','escape'=>false))?>

			<?php
				}
			?>
		</div>
		<div class="title-name">
			<?php  echo $this->Html->link($this->OnewebVn->capitalFirstLetterVietnamese($item_product['name']),$url,$link_attr)?>
		</div>
		<?php if(!empty($oneweb_product['price'])){?>
		<div class="price text-center">
			<?php
			if($item_product['price']>0){
				if(!empty($item_product['price_new'])){
					echo $this->Html->tag('del',number_format($item_product['price']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'font-weight-bold price_old'));
					echo $this->Html->tag('span',number_format($item_product['price_new']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'float_right new color_red font-weight-bold'));
				}else echo $this->Html->tag('span',number_format($item_product['price']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'float_right new color_red font-weight-bold'));

			}else echo $this->Html->tag('span class="color_red"',__('Liên hệ',true));
			?>
		</div>
		<?php
			}
		?>
	</div>
<?php
	}}
?>
<!-- end c_product.ctp -->