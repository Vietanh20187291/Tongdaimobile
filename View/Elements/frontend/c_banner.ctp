<?php 
if(is_array($data)){
?>
<div class="box_banner text-center">
		<?php 
		$count_data = count($data);
		foreach($data as $key=>$val){ 
				$item = $val['Banner'];
		?>
		<div class="col-xs-6 col-sm-4 col-md-4 <?php echo $class?>">
			<?php 															
			$attr = array('alt'=>$item['name'],'class'=>'img-responsive');
			if($oneweb_banner['size']['3'][0]!='n') $attr = array_merge($attr,array('width'=>$oneweb_banner['size']['3'][0]));
			if($oneweb_banner['size']['3'][1]!='n') $attr = array_merge($attr,array('height'=>$oneweb_banner['size']['3'][1]));
			
			$str_banner = $this->Html->image('images/banners/'.$item['image'],$attr);
			
			$link_attr = array('title'=>$item['name'],'target'=>$item['target'],'class'=>'','escape'=>false);
			if($item['rel']!='dofollow') $link_attr['rel'] = $item['rel'];
			
			if(!empty($$item['link'])) $str_banner = $this->Html->link($str_banner,$item['link'],$link_attr);
			echo $str_banner;
			?>
	</div>
	<?php 				
		}?>
</div> <!-- end .box_banner -->
<?php }?>