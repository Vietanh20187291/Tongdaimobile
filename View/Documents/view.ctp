<!-- start documents/view.ctp -->
<?php $item_cate = $a_category_c['DocumentCategory'] ?>
<article class="box_content">
	<header class="title">
		<h1><span><?php echo $item_cate['name']?></span></h1>
	</header>

	<div class="des">
		<?php if(!empty($item_cate['description'])){?>
		<div class="box_info_page">
			<div class="des">
				<?php echo $item_cate['description'] ?>
			</div> <!--  end .des -->

			<div class="top"></div>
			<div class="bottom"></div>
		</div> <!--  end .box_info_page -->
		<?php }?>

		<?php if(!empty($a_documents_c)){?>
		<ul class="list_document">
			<?php
			foreach($a_documents_c as $key=>$val){
				$item_document = $val['Document'];
				$class = 'link';
				if(empty($val['link'])){
					$tmp = explode('.', $item_document['file']);
					if(!empty($tmp[1])) $class = strtolower($tmp[1]);
					$link = '/img/files/documents/'.$item_document['file'];
				}else $link = $item_document['link'];
			?>
			<li<?php if($key%2==0) echo ' class="bold"'?>>
				<div class="summary">
					<div class="left">
						<?php
							echo $this->Html->tag('span','&nbsp;',array('class'=>"act {$class}"));
							echo $this->Html->link($item_document['name'],'javascript:;',array('title'=>$item_document['name'],'onclick'=>"detail({$item_document['id']})",'class'=>'name tooltip'));
						?>
					</div>
					<div class="right">
						<?php
							echo $this->Html->link('&nbsp;',array('action'=>'download','lang'=>$lang,'slug_cate'=>$item_cate['slug'],'slug'=>$this->OnewebVn->getSlug($item_document['name']),'id'=>$item_document['id'],'ext'=>'html'),array('title'=>__('Tải về',true),'class'=>'act download tooltip','target'=>'_blank','rel'=>'nofollow','escape'=>false));
							echo $this->Html->link(__('Chi tiết',true),'javascript:',array('title'=>__('Chi tiết',true),'class'=>'more tooltip','onclick'=>"detail({$item_document['id']})",'rel'=>'nofollow'));
						?>
					</div>
				</div>
				<div class="detail" id="doc_<?php echo $item_document['id']?>"><?php echo $item_document['description']?></div>
			</li>
			<?php }?>
		</ul> <!--  end .list_category -->

		<div class="clear"></div>
		<div class="paginator">
			<?php
				$this->Paginator->options(array(
					'url'=>array('controller'=>'documents','action' => 'view','lang'=>$lang,'slug_cate'=>$item_cate['slug'])
				));

				echo $this->Paginator->counter(array('format'=>'<span class="page">%page%/%pages%</span>'));
				echo $this->Paginator->first('<<',array('separator'=>false,'title'=>__('Trang đầu',true)));
				echo $this->Paginator->numbers(array('separator'=>false,'modulus'=>7,'class'=>'number'));
				echo $this->Paginator->last('>>',array('separator'=>false,'title'=>__('Trang cuối',true)));
			?>
		</div> <!-- end .paginator -->
		<?php }else echo __('Thông tin đang được cập nhật. Bạn vui lòng trở lại sau.',true)?>
	</div> <!--  end .des -->

	<div class="top"></div>
	<div class="bottom"></div>
</article>
<!-- end documents/view.ctp -->