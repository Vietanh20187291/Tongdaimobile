<aside class="box gallery  hidden-xs">
	<div class="title">
		<span class="icon"></span>
		<span class="title_text"><?php echo $this->Html->link($this->Html->tag('span',__('Hình ảnh',true)),array('controller'=>'galleries','action'=>'index','lang'=>$lang),array('title'=>__('Hình ảnh',true),'escape'=>false))?></span>
	</div>
	
	<div id="show_gallery" class="carousel slide" data-interval="false">
		<div class="carousel-inner">
				<?php 
			//Kich thước ảnh thumbnail
			$full_size = $oneweb_media['size']['gallery'];
			$h = intval($w*$full_size[1]/$full_size[0]);
			foreach($data['GalleryImage'] as $key=>$val){
			?>
				<div class="item item_gallery <?php echo $class; if($key==0) echo 'active'?>">
					<div class="thumb">
						<?php echo $this->OnewebVn->thumb('galleries/'.$val['image'],array('alt'=>$val['name'],'width'=>$w,'height'=>$h))?>
					</div> <!--  end .thumb -->
				</div>
			<?php 
			}
			?>
		</div>
		<a class="right carousel-control" href="javascript:;" role="button">&#62;</a>
		<a class="left carousel-control" href="javascript:;" role="button">&#60;</a>
	</div>
</aside> <!--  end .box -->	
<script>
$(document).ready(function(){
    // Activate Carousel
    $("#show_gallery").carousel({
    	interval: 2000
       });
    // Enable Carousel Controls
    $("#show_gallery .left").click(function(){
        $("#show_gallery").carousel("prev");
    });
    $("#show_gallery .right").click(function(){
        $("#show_gallery").carousel("next");
    });
});
</script>