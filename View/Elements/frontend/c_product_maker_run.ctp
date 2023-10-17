<!-- start c_product_run.ctp -->
<?php if (!empty($data)){ ?>
	<div class="owl-product-marker owl-carousel owl-theme">
			<?php
			foreach ($data as $val) {
				$item_maker = $val['ProductMaker'];
	                if (!empty($item_maker)){
	                    $link_maker_attr = array('title'=>$item_maker['meta_title'],'target'=>$item_maker['target']);
	                    $link_img_attr = array_merge($link_maker_attr, array('escape' => false));
					    if($item_maker['rel']!='dofollow') $link_maker_attr['rel'] = $item_maker['rel'];
			 ?>
			<div class="item c-product col-xs-12">
					<div class="thumb">
						<?php
						if(!empty($item_maker['image'])){
							echo $this->Html->link($this->OnewebVn->thumb('product_makers/'.$item_maker['image'],array('width'=>100,'height'=>100,'zc'=>1,'class'=>'img-responsive img-center')),array('controller'=>'products','action'=>'maker','lang'=>$lang,'slug'=>$item_maker['slug']),$link_img_attr);
						}else{
							// echo $this->Html->link($this->Html->image('no_maker.jpg',array('class'=>'img-responsive img-center')),array('controller'=>'products','action'=>'maker','lang'=>$lang,'slug'=>$item_maker['slug']),$link_img_attr);
						}
						?>
					</div>
					<div class="name text-center">
						<?php echo $this->Html->link($item_maker['name'],array('controller'=>'products','action'=>'maker','lang'=>$lang,'slug'=>$item_maker['slug']),$link_maker_attr)?>
					</div>
				</div>
			<!--  end .box_post -->
			<?php }}?>
	</div>
<!-- end .box-other -->
<?php }?>
<script type="text/javascript">
	$(document).ready(function() {
		var owl = $(".owl-product-marker");
		owl.owlCarousel({
	    autoplay : false,
	    autoplayTimeout : 8000,
	    autoplayHoverPause : true,
			loop : true,
			nav : true,
			dots : false,
			responsiveClass : true,
			responsive : {
				// breakpoint from 0 up
				0 : {
						items : 2
				},
				// breakpoint from 375 up
				375 : {
						items : 4
				},
				// breakpoint from 768 up
				768 : {
						items : 6
				},
				// breakpoint from 992 up
				992 : {
						items : 9
				}
			},
			navText : ["<i class='glyphicon glyphicon-chevron-left'></i>","<i class='glyphicon glyphicon-chevron-right'></i>"]
		});
	});
</script>
<!-- end c_product_run.ctp -->
