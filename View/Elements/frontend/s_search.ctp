<aside class="box box_search">
	<div class="title">
		<span class="icon"></span>
		<span class="title_text"><?php echo __('Tìm kiếm sản phẩm',true)?></span>
	</div>
	
	<?php 
		$controller = $this->params['controller'];
		$action = $this->params['action'];
		echo $this->Form->create('Filter',array('url'=>array('controller'=>'filters','action'=>'search','lang'=>$lang),'inputDefaults'=>array('div'=>false,'label'=>false)));
	?>
	<?php if(!empty($oneweb_search['product']['enable'])){?>
	<ul id="search_product">
		<?php if(!empty($a_product_categories_2_s)){?>
		<li><?php echo $this->Form->input('product_category',array('type'=>'select','options'=>$a_product_categories_2_s,'empty'=>'-- Danh mục --','value'=>((!empty($_GET['cate_id']) && $action=='product')?$_GET['cate_id']:''),'class'=>'form-control'))?></li>
		<?php }if(!empty($a_product_makers_2_c)){?>
		<li><?php echo $this->Form->input('product_maker',array('type'=>'select','options'=>$a_product_makers_2_c,'empty'=>__('-- Hãng sản xuất --',true),'class'=>'form-control','value'=>((!empty($_GET['product_maker']))?$_GET['product_maker']:'')))?></li>
		<?php }?>
		
		<li><?php echo $this->Form->input('product_color',array('type'=>'select','options'=>$a_product_color,'empty'=>'-- Màu sắc --','value'=>((!empty($_GET['product_color']))?$_GET['product_color']:''),'class'=>'form-control'))?></li>
		<li><?php echo $this->Form->input('product_diameter',array('type'=>'select','options'=>$a_product_diameter,'empty'=>'-- Đường kính --','value'=>((!empty($_GET['product_diameter']) && $action=='product')?$_GET['product_diameter']:''),'class'=>'form-control'))?></li>
		<li id="sighted">
		<?php 
		$sighted = array('Có độ cận','Không độ cận');
		echo $this->Form->radio('sighted',$sighted,array('legend'=>false,'value'=>!empty($_GET['sighted'])?$_GET['sighted']:'0','class'=>'sighted'));
		?>
		</li>
	</ul>
	<?php }?>
	<?php 
		echo $this->Form->submit(__('Tìm kiếm',true),array('class'=>'icon btn_search right'));
	?>
	
	<?php
	echo $this->Form->end();
	?>		
</aside> <!--  end .box -->