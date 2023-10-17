<!-- start c_product_run.ctp -->
<?php if (!empty($data)){ ?>
	<div class="owl-product-marker owl-carousel owl-theme">
			<?php
			foreach ($data as $val) {
				$item = $val['Banner'];
	                if (!empty($item)){
	                    $link_attr = array('title'=>$item['name'],'target'=>$item['target']);
	                    $link_img_attr = array_merge($link_attr, array('escape' => false));
					    if($item['rel']!='dofollow') $link_attr['rel'] = $item['rel'];
					    if(!empty($item['link']))
					    	$link = $item['link'];
					    else
					    	$link = 'javascript:;';
			 ?>
			<div class="item c-product col-xs-12">
					<div class="thumb">
						<?php
						if(!empty($item['image'])){
							echo $this->Html->link($this->OnewebVn->thumb('banners/'.$item['image'],array('width'=>$oneweb_banner['size'][$position][0],'height'=>$oneweb_banner['size'][$position][1],'zc'=>2,'class'=>'img-responsive img-center')),$link,$link_img_attr);
						}else{
							// echo $this->Html->link($this->Html->image('no_maker.jpg',array('class'=>'img-responsive img-center')),$link,$link_img_attr);
						}
						?>
					</div>
					<div class="name text-center">
						<?php echo $this->Html->link($item['name'],$link,$link_attr)?>
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
						items : 4
				},
				// breakpoint from 375 up
				375 : {
						items : 4
				},
				// breakpoint from 768 up
				768 : {
						items : 4
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
