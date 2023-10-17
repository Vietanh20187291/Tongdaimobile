<!-- start c_product_run.ctp -->
<?php if (!empty($data)){ ?>
	<div id="owl-product<?php echo $position ?>" class="owl-product owl-carousel owl-theme">
			<?php
			$full_size = $oneweb_product['size']['product'];
			$h = intval($w*$full_size[1]/$full_size[0]);
			$limit = 0;

			foreach ($data as $val) {
				$item_product = $val['Product'];
				$item_cate = $val['ProductCategory'];

				$url = array('controller' => 'products', 'action' => 'index', 'lang' => $item_product['lang']);
				if ($limit++ == 10) break;

				$url['slug0'] = $item_product['slug'];
				$url['ext'] = 'html';

				$link_attr = array('title' => $item_product['meta_title'],'target' => $item_product['target'],'class' => 'name');
				if ($item_product['rel'] != 'dofollow') $link_attr['rel'] = $item_product['rel'];

				$link_img_attr = array_merge($link_attr, array('escape' => false));
				$link_img_attr['class'] = '';
			 ?>
			<div class="item c-product run">
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
					<?php echo $this->Html->link('<button ><i class="icon_oneweb icon_cart"></i>'.__('Mua hàng',true).'</button>', $url, array('rel'=>'nofollow','class'=>'mask','escape'=>false))?>
					<?php //echo $this->Html->link('<button onclick="return buy('.$item_product['id'].')"><i class="icon_oneweb icon_cart"></i>'.__('Mua hàng',true).'</button>', $url, array('rel'=>'nofollow','class'=>'mask','escape'=>false))?>
						<?php }?>
					</div>
          <?php if(!empty($oneweb_product['price'])){?>
							<div class="price col-xs-12 text-center mt-2">
								<?php
								if($item_product['price']>0){
									if(!empty($item_product['price_new'])){
										echo $this->Html->tag('span',number_format($item_product['price_new']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'mr-3 new color_red font-weight-bold'));
										echo $this->Html->tag('del',number_format($item_product['price']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'mr-3 font-weight-bold price_old'));
									}else echo $this->Html->tag('span',number_format($item_product['price']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'new color_red font-weight-bold'));

								}else echo $this->Html->tag('span class="color_red"',__('Liên hệ',true));
								?>
							</div>
							<?php }?>

							<div class="title-name">
								<div><?php  echo $this->Html->link(ucwords($item_product['name']),$url,$link_attr)?></div>
								<?php if(!empty($item_product['name_en'])){ ?>
								<div><?php  echo $this->Html->link(ucwords($item_product['name_en']),$url,$link_attr)?></div>
								<?php } ?>
							</div>
							<?php if(!empty($item_product['count_buyed'])){ ?>
							<div class="count_buyed">
								<span><?php echo $item_product['count_buyed'] ?></span> người đã mua
							</div>
							<?php } ?>
						</div>
			<!--  end .box_post -->
			<?php }?>
	</div>
<!-- end .box-other -->
<?php }?>
<script type="text/javascript">
	$(document).ready(function() {
		var owl = $("#owl-product<?php echo $position ?>");
		owl.owlCarousel({
	    autoplay : false,
	    autoplayTimeout : 8000,
	    autoplayHoverPause : true,
			loop : <?php if(!empty($loop)) echo $loop; else echo 'true'; ?>,
			nav : true,
			dots : false,
			responsiveClass : true,
			responsive : {
				// breakpoint from 0 up
				0 : {
						items : 1
				},
				// breakpoint from 348 up
				348 : {
						items : 2
				},
				// breakpoint from 600 up
				600 : {
						items : 3
				},
				// breakpoint from 900 up
				900 : {
						items : 5
				},
				// breakpoint from 1000 up
				1000 : {
						items : 5
				}
			},
			navText : ["<i class='glyphicon glyphicon-chevron-left'></i>","<i class='glyphicon glyphicon-chevron-right'></i>"]
		});
	});
</script>
<!-- end c_product_run.ctp -->
