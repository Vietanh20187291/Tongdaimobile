<?php if(is_array($data)){?>
<ul id="<?php echo $id?>" class="adv_out">
	<?php foreach($data as $val){
		$item = $val['Banner'];
		
		echo $this->Html->tag('li',
				$this->element('frontend/banner',array(
														'data'	=>	$item,
														'size'	=>	$oneweb_banner['size'][$position]
													))
			);
	}?>
</ul>
<?php }?>