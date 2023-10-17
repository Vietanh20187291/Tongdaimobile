<!-- start s_banner.ctp -->
<?php if(is_array($data)){?>
<aside <?php if(isset($id_fixed)) echo 'id="'.$id_fixed.'"';?> class="box adv">
	<?php
		if ( ! empty($title))
		{
	?>
		<div class="title"><?php echo $title?></div>
	<?php
		}
	?>
	<ul>
		<?php foreach($data as $val){
			$item = $val['Banner'];

			echo $this->Html->tag('li',
					$this->element('banner',array(
															'data'		=>	$item,
															'size'		=>	$oneweb_banner['size'][$position],
														))
				);
		}?>
	</ul>
</aside>
<?php }?>
<!-- end s_banner.ctp -->