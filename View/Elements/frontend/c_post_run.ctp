<?php if (!empty($data)){ ?>
	<div class="owl-post owl-carousel owl-theme">
			<?php
			$full_size = $oneweb_post['size']['post'];
			$h = intval($w*$full_size[1]/$full_size[0]);

			foreach ($data as $val) {
				$item_post = $val['Post'];
				$item_cate = $val['PostCategory'];

				$url = array('controller'=>'posts','action'=>'index','lang'=>$item_post['lang'],'position'=>$item_cate['position']);

				$tmp = explode(',', $item_cate['path']);
				for($i=0;$i<count($tmp);$i++){
					$url['slug'.$i]=$tmp[$i];
				}
				$url['slug'.count($tmp)] = $item_post['slug'];
				$url['ext']='html';

				$link_attr = array('title'=>$item_post['meta_title'],'target'=>$item_post['target'],'class'=>'name font-weight-bold');
				if($item_post['rel']!='dofollow') $link_attr['rel'] = $item_post['rel'];

				$link_img_attr = array_merge($link_attr,array('escape'=>false));
				$link_img_attr['class']='';
				$link_more_attr['title'] = __('Read more',true);
				$link_more_attr['class'] = 'readmore float_right';
			 ?>
			<div class="item c-post run col-xs-12">
				<div class="thumb">
					<?php echo $this->Html->link($this->OnewebVn->thumb('posts/'.$item_post['image'],array('alt'=>$item_post['meta_title'],'width'=>$w,'height'=>$h, 'zc' => '1', 'class'=>'img-responsive')),$url,$link_img_attr)?>
				</div>
				<div class="w-100 mt-2">
					<?php echo $this->Html->link($item_post['name'],$url,$link_attr);?>
				</div>

			</div>
			<!--  end .box_post -->
			<?php }?>
	</div>
<!-- end .box-other -->
<?php }?>
<script type="text/javascript">
	$(document).ready(function() {
		var owl = $(".owl-post");
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
