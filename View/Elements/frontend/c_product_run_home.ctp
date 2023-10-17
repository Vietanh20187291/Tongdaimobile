<!-- start c_prduct_run_home.ctp -->
<?php $oneweb_product['discount']= false ?>
<article class="box_content col-xs-12 product_run_<?php echo $position_p; if(!empty($class)) echo ' '.$class ?>">
	<div id="product_content_p<?php echo $position_p ?>" class="owl-carousel owl-theme box-content bg_white">
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
		<div class="item border_right p-t-15 p-b-15 ">
			<div class="thumb item_product_thumb">
				<div class="text-center">
					<?php
					if(!empty($item_product['hot'])) echo $this->Html->tag('span class="hot"','HOT');
					if(!empty($item_product['promotion'])) echo $this->Html->tag('span',' ',array('class'=>'promotion'));
					echo $this->Html->link($this->OnewebVn->thumb('products/'.$item_product['image'],array('alt'=>$item_product['meta_title'],'width'=>$w,'height'=>$h,'class'=>'img-responsive')),$url,$link_img_attr);
					echo $this->Html->link('',$url,array('class'=>'item_hover'));
				?>
				</div>
				<div class="col-xs-12 text-center button_cart">
					<?php echo $this->Html->link('<span class="icon_oneweb icon_shopping_cart"></span>'.__('Mua hàng',true),'javascript:;',array('title'=>__('Mua hàng',true),'rel'=>'nofollow','onclick'=>"addToCart({$item_product['id']},1,true)",'class'=>'btn btn-default btn-cart',"data-toggle"=>"modal" ,"data-target"=>"#popup_modal",'escape'=>false))?>
				</div>
			</div>
			<h3 class="name text-center min_height45 col-xs-12"><?php echo $this->Html->link($item_product['name'],$url,$link_attr)?></h3>
			<?php if(!empty($oneweb_product['price'])){?>
			<div class="price text-center min_height45">
				<?php
				if($item_product['price']>0){
					if(!empty($item_product['price_new'])){
						echo $this->Html->tag('del',number_format($item_product['price']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'font-weight-bold price_old'));
						echo $this->Html->tag('span',number_format($item_product['price_new']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'float_right new color_red font-weight-bold'));
					}else echo $this->Html->tag('span',number_format($item_product['price']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'float_right new color_red font-weight-bold'));
				} else echo $this->Html->tag('span class="color_red"',__('Liên hệ',true));
				?>
			</div>
			<?php
				}
			?>
		</div>
		<?php }?>
	</div>
	<div class="customNavigation">
		<a class="btn prev glyphicon glyphicon-menu-left">&nbsp;</a>
		<a class="btn next glyphicon glyphicon-menu-right">&nbsp;</a>
	</div>
</article>
<script type="text/javascript">
	$(document).ready(function() {
		var owl = $("#product_content_p<?php echo $position_p ?>");
		owl.owlCarousel({
			items : 5, //10 items above 1000px browser width
			itemsDesktop : [1000,5], //5 items between 1000px and 901px
			itemsDesktopSmall : [900,3], // betweem 900px and 601px
			itemsTablet: [600,2], //2 items between 600 and 349
			//itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
			itemsMobile : [348,1], //1 item betwen 348 and 0
			//autoPlay : 5000,
			loop:true,
			//paginationSpeed : 400,
			//rewindNav : true,
		});

		// Custom Navigation Events
		$(".product_run_<?php echo $position_p ?> .next").click(function(){
			owl.trigger('owl.next');
		})
		$(".product_run_<?php echo $position_p ?> .prev").click(function(){
			owl.trigger('owl.prev');
		})
	});
</script>
<!-- end c_prduct_run_home.ctp -->