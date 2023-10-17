<!-- start documents/index.ctp -->
<article class="box_content">
	<header class="title">
		<h1><span><?php echo __('Tài liệu',true)?></span></h1>
	</header>

	<div class="des">
		<?php if(!empty($a_document_configs_c['document_description'])){?>
		<div class="box_info_page">
			<div class="des">
				<?php echo $a_document_configs_c['document_description'] ?>
			</div> <!--  end .des -->

			<div class="top"></div>
			<div class="bottom"></div>
		</div>
		<?php }?>

		<?php if(!empty($a_document_categories_c)){?>
		<ul class="list_category2">
			<?php
			foreach($a_document_categories_c as $val){
				$item_cate = $val['DocumentCategory'];
				$url = array('action'=>'view','lang'=>$lang,'slug_cate'=>$item_cate['slug']);

				$link_attr = array('title'=>$item_cate['meta_title'],'target'=>$item_cate['target'],'class'=>'name tooltip');
				if($item_cate['rel']!='dofollow') $link_attr['rel'] = $item_cate['rel'];

				$link_img_attr = array_merge($link_attr,array('escape'=>false));
				$link_img_attr['class'] = 'thumb tooltip';
			?>
			<li>
				<?php
					echo $this->Html->link($this->Html->image('folder.png',array('alt'=>$item_cate['meta_title'])),$url,$link_img_attr);
					echo $this->Html->link($this->Text->truncate($item_cate['name'],28,array('exact'=>false)),$url,$link_attr);
				?>
			</li>
			<?php }?>
		</ul>

		<div class="clear"></div>
		<div class="paginator">
			<?php
				$this->Paginator->options(array(
					'url'=>array('controller'=>'documents','action' => 'index','lang'=>$lang)
				));

				echo $this->Paginator->counter(array('format'=>'<span class="page">%page%/%pages%</span>'));
				echo $this->Paginator->first('<<',array('separator'=>false,'title'=>__('Trang đầu',true)));
				echo $this->Paginator->numbers(array('separator'=>false,'modulus'=>7,'class'=>'number'));
				echo $this->Paginator->last('>>',array('separator'=>false,'title'=>__('Trang cuối',true)));
			?>
		</div>
		<?php }else echo __('Thông tin đang được cập nhật. Bạn vui lòng trở lại sau.',true)?>
	</div>

	<div class="top"></div>
	<div class="bottom"></div>
</article>
<!-- end documents/view.ctp -->