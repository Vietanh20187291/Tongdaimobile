<aside class="box document">
	<span class="title"><?php echo __('Tài liệu',true)?></span>
	
	<ul>
		<?php foreach($data as $val){
			$item_document = $val['Document'];
			$item_cate = $val['DocumentCategory'];
			
			$class = 'link';
			if(empty($val['link'])){
				$tmp = explode('.', $item_document['file']);
				if(!empty($tmp[1])) $class = strtolower($tmp[1]);
				$link = '/img/files/documents/'.$item_document['file'];
			}else $link = $item_document['link'];
		?>
		<li>
		<?php 
			echo $this->Html->tag('span','&nbsp;',array('class'=>"act {$class}"));
			echo $this->Html->link($item_document['name'],array('controller'=>'documents','action'=>'download','lang'=>$lang,'slug_cate'=>$item_cate['slug'],'slug'=>$this->OnewebVn->getSlug($item_document['name']),'id'=>$item_document['id'],'ext'=>'html'),array('title'=>$item_document['name'],'class'=>'name tooltip','target'=>'_blank','rel'=>'nofollow'));
		?>
		</li>
		<?php }?>
	</ul>
			
	<div class="top"></div>
	<div class="bottom"></div>
</aside> <!--  end .box -->