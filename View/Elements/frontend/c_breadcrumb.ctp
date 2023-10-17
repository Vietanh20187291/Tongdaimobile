<!-- start frontend/c_breadcrumb -->
<ol class="breadcrumb <?php if($this->params['controller'] == 'products' && $this->params['ext'] == 'html') echo '' ?>">
	<?php if(!empty($a_breadcrumb_c)){?>
	<li><?php echo $this->Html->link(__('Trang chủ',true),array('controller'=>'pages','action'=>'home','lang'=>$lang),array('title'=>__('Trang chủ',true),'rel'=>'nofollow','escape'=>false))?></li>
	<?php
	$count = count($a_breadcrumb_c);
	for($i=0;$i<($count-1);$i++){
		$item = $a_breadcrumb_c[$i];
	?>
	<li>
		<?php
		if($i<($count-1)) echo $this->Html->link($item['name'],$item['url'],array('title'=>$item['meta_title'],'rel'=>'nofollow'));
		else echo $item['name'];

		/*if(!empty($item['child'])){?>
		<ul>
			<?php
			foreach ($item['child'] as $item2)
				echo $this->Html->tag('li',
						$this->Html->link($item2['name'],$item2['url'],array('title'=>$item2['meta_title'],'rel'=>'nofollow'))
					);
			?>
		</ul>
		<?php }*/?>
	</li>
	<?php }}else{?>
	<li><?php echo __('Home',true)?></li>
	<?php }?>
</ol>
<!-- end frontend/c_breadcrumb -->
