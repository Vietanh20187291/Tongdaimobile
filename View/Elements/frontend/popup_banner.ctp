<?php if(is_array($a_popups)){?>
<div id="popup">
	<div id="dialog" class="window" style="<?php echo 'width: '.$oneweb_banner['size'][8][0].'px;' ?>">
		<a href="javascript:;" class="close">&nbsp;</a>
		<div class="content_popup">
			<?php
				$item = $data[rand(0,count($data)-1)]['Banner'];

				$img = 'images/banners/'.$item['image'];
				if(!empty($item['link'])){
					echo $this->Html->link($this->Html->image($img,array('width'=>'100%','alt'=>$item['name'])),$item['link'],array('title'=>$item['name'],'rel'=>$item['rel'],'target'=>'_blank','escape'=>false));
				}else{
					echo $this->Html->image($img,array('width'=>'100%','alt'=>$item['name']));
				}
			?>
		</div>
	</div>

	<!-- vùng div id mask . lúc đầu nó sẽ ẩn -->
	<div id="mask"></div>
</div> <!--  end #popup -->

<script type="text/javascript">
	$(document).ready(function(){
		popup('dialog');
	})
</script>
<?php }?>