<!-- start informations/view.ctp -->
<?php $item_information = $a_information_c['Information']?>
<article class="box_content read">
	<header class="title">
		<h1><span><?php echo $item_information['name']?></span></h1>
	</header>

	<div class="des">
		<?php echo $item_information['description']?>

		<?php if(!empty($a_related_info_c)){?>
		<section class="related">
			<header><span class="title"><?php echo __('Liên quan',true)?></span></header>
			<ul>
				<?php foreach($a_related_info_c as $val){
					$item_related = $val['Information'];
					if(!empty($item_related['link'])) $url_related = $item_related['link'];
					else{
						$url_related = array('action'=>'view','lang'=>$lang,'position'=>$item_related['position'],'slug'=>$item_related['slug']);
						if(!empty($item_related['parent_id'])) $url_related = array_merge($url_related,array('ext'=>'html'));
					}

					$attr = array('title'=>$item_related['meta_title'],'target'=>$item_related['target']);
					if($item_related['rel']!='dofollow') $attr['rel'] = $item_related['rel'];
				?>
				<li><?php echo $this->Html->link($item_related['name'],$url_related,$attr)?></li>
				<?php }?>
			</ul>
		</section>
		<?php }?>
	</div>
</article>
<!-- end informations/view.ctp -->