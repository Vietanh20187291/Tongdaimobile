<aside class="box s_post box_post_run <?php if(!empty($class)) echo $class?>">
	<p class="title"><span><?php echo __($oneweb_post['display'][$position],true)?></span></p>
	<div id="post_lastest_news" class="carousel slide" data-interval="false">
		<div class="carousel-inner">
			<?php 
			//Kich thước ảnh thumbnail
			$full_size = $oneweb_post['size']['post'];
			$w = 158;
			$h = intval($w*$full_size[1]/$full_size[0]);
			$count_item = count($data);
			$item1 = round($count_item/4,0,PHP_ROUND_HALF_DOWN);
			$item = $count_item/4;
			if($item>$item1) $item1 += 1;
			for($k=0;$k<$item1;$k++){
				$max = ($k+1)*4;
				if($max>$count_item || $count_item <4) $max = $count_item;
			?>
			<div  class="item <?php if($k==0) echo 'active'?>">
				<div class="row">
					<?php for($j=$k*4;$j<$max;$j++){
					$item_post = $data[$j]['Post'];
					$item_cate = $data[$j]['PostCategory'];
					$url = array('controller'=>'posts','action'=>'index','lang'=>$item_post['lang']);
					
					$tmp = $item_post['slug'];
					for($i=0;$i<count($tmp);$i++){
						$url['slug'.$i]=$tmp;
					}
					$url['ext']='html';
					
					$link_attr = array('title'=>$item_post['meta_title'],'target'=>$item_post['target'],'class'=>'name');
					if($item_post['rel']!='dofollow') $link_attr['rel'] = $item_post['rel'];
					$link_img_attr = array_merge($link_attr,array('escape'=>false));
					$link_img_attr['class']='';
					?>
					<div class="col-xs-12 col-sm-3 col-md-6 col-lg-6 post">
						<div class="thumb">
								<?php echo $this->Html->link($this->OnewebVn->thumb('posts/'.$item_post['image'],array('alt'=>$item_post['meta_title'],'width'=>$w,'height'=>$h)),$url,$link_img_attr)?>
							</div> <!--  end .thumb -->
						<?php 
						echo $this->Html->link($this->Text->truncate($item_post['name'],40,array('extra'=>false)),$url,$link_attr);
						?>
					</div>
					<?php }?>
				</div>
			</div>
			<?php 
			}
			?>
		</div>
		<a class="left carousel-control" href="#post_lastest_news" role="button">&#60;</a>
		<a class="right carousel-control" href="#post_lastest_news" role="button">&#62;</a>
	</div>
</aside> <!--  end .box -->	
<script>
$(document).ready(function(){
    // Activate Carousel
    $("#post_lastest_news").carousel();
    // Enable Carousel Controls
    $("#post_lastest_news .left").click(function(){
        $("#post_lastest_news").carousel("prev");
    });
    $("#post_lastest_news .right").click(function(){
        $("#post_lastest_news").carousel("next");
    });
});
</script>