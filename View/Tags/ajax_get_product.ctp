<?php if(!empty($a_products_c)){
	echo $this->Html->script('tag/url_tag');

	$position = 'tag';
	$i = 0;

	$full_size = $oneweb_product['size']['product'];
	$w = 400;
	$h = intval($w*$full_size[1]/$full_size[0]);

	foreach ($a_products_c as $val){
		$item_product = $val['Product'];
		$item_cate = $val['ProductCategory'];

// 		$url=array('controller'=>'products','action'=>'view','lang'=>$lang,'cate'=>$item_cate['slug'],'slug'=>$item_product['slug'],'ext'=>'html');

		$url = array('controller'=>'products','action'=>'index','lang'=>$item_product['lang']);

		$tmp = explode(',', $item_cate['path']);
		for($j=0;$j<count($tmp);$j++){
			$url['slug'.$j]=$tmp[$j];
		}
		$url['slug'.count($tmp)] = $item_product['slug'];
		$url['ext']='html';

		$link_attr = array('title'=>$item_product['meta_title'],'target'=>$item_product['target']);
		if($item_product['rel']!='dofollow') $link_attr['rel'] = $item_product['rel'];

		$link_img_attr = array_merge($link_attr,array('escape'=>false));
		$link_attr['class'] = 'name';

	?>

	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 item c-product">
			<div class="thumb margin_bottom15 item_product_thumb">
				 <div class="text-center m-b-15">
					<?php
					if(!empty($item_product['hot'])) echo $this->Html->tag('span class="icon_oneweb icon-hot"','');
	// 			                if(!empty($item_product['discount'])) {
	// 			                	if($item_product['discount_unit']) echo $this->Html->tag('span','-'.$item_product['discount'].'%',array('class'=>'discount'));
	// 			                	else echo $this->Html->tag('span','-'.(round(100-($item_product['discount']*100)/$item_product['price']).'%'),array('class'=>'discount'));
	// 			                }
							echo $this->Html->link($this->OnewebVn->thumb('products/'.$item_product['image'],array('alt'=>$item_product['meta_title'],'width'=>$w,'height'=>$h, 'zc' => 2, 'class'=>'img-responsive')),$url,$link_img_attr);
							//echo $this->Html->link($item_post['name'],$url,$link_attr);
							?>
						</div>
						<?php if($oneweb_product['order']){?>

			<!-- Nút mua hàng -->
			<?php echo $this->Html->link('<button onclick="return buy('.$item_product['id'].')"><i class="icon_oneweb icon_cart"></i>'.__('Mua hàng',true).'</button>', $url, array('rel'=>'nofollow','class'=>'mask','escape'=>false))?>
		<?php }?>
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
				<?php }?>
	</div>


	<?php $i++; }?>
	<div class="clear"></div>
	<div class="paginator">
		<?php
		echo $this->Html->tag('span',$current_page_c.'/'.$all_pages_c);
		if($current_page_c!=1) echo $this->Html->tag('span',$this->Html->link('&lt;&lt;','javascript:;',array('title'=>__('Trang đầu',true),'onclick'=>"getProduct(1);backTopAtTag()",'escape'=>false)));
		for($i=0;$i<$all_pages_c;$i++){
			$page = $i+1;
			if($current_page_c == $page) echo $this->Html->tag('span',$page,array('class'=>'current'));
			else echo $this->Html->tag('span',$this->Html->link($i+1,'javascript:;',array('onclick'=>"getProduct($page);backTopAtTag()")));
		}
		if($current_page_c!=$all_pages_c) echo $this->Html->tag('span',$this->Html->link('&gt;&gt;','javascript:;',array('title'=>__('Trang cuối',true),'onclick'=>"getProduct($all_pages_c);backTopAtTag()",'escape'=>false)));
		?>
	</div>
<?php }else{ echo __('Không tim thấy sản phẩm nào',true); }?>