<!-- start products/maker.ctp -->
<article>
	<?php if(!empty($oneweb_product['maker_banner']) && !empty($a_maker_c['banner'])){
		$banner_size = $oneweb_product['size']['maker_banner'];
	?>
	<div class="banner">
		<?php
			$banner = $this->Html->image('images/product_makers/'.$a_maker_c['banner'],array('alt'=>$a_maker_c['name'],'width'=>$banner_size[0],'height'=>$banner_size[1]));
			if(!empty($a_maker_c['banner_link'])) $banner = $this->Html->link($banner,$a_maker_c['banner_link'],array('title'=>$a_maker_c['name'],'target'=>'_blank','rel'=>'nofollow','escape'=>false));
			echo $banner;
		?>
	</div> <!-- end .banner -->
	<?php }?>

	<?php
		$flag = true;		//Hiển thị title ở vị trí sắp xếp
		if(!empty($a_maker_c['description'])) $flag = false;
		if(!$flag){
	?>

	<div class="box_info_page">
		<header class="title">
			<h1><?php echo $a_maker_c['name']?></h1>
		</header>

		<div class="des">
			<?php echo $a_maker_c['description']?>
		</div> <!--  end .des -->

		<div class="top"></div>
		<div class="bottom"></div>
	</div> <!--  end .box_info_page -->
	<?php }?>

	<div class="box_content">
		<?php if($flag){?>
		<header class="title">
			<h1><?php echo $a_maker_c['name']?></h1>
		</header>
		<?php }?>
		<?php
			$url = array('controller'=>'products','action' => 'maker','lang'=>$lang,'slug'=>$a_maker_c['slug']);
			$a_params = $this->params;
			if(!empty($a_params['sort']) && !empty($a_params['direction'])) $url = array_merge($url,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
			$this->Paginator->options(array(
				'url'=>$url
			));
		?>
		<div class="des row auto-clear">
			<?php
			echo $this->element('frontend/c_product',array(
															'data'		=> $a_products_c,
															'position'	=> '',
															'limit'		=> '',
															'cart'		=> true,
															'class'		=> 'col-xs-6 col-sm-6 col-md-4 col-lg-3',
															'w'			=> 400,
															'zc'		=> 2
														))
			?>

			<div class="clear"></div>
			<div class="paginator">
				<?php
					echo $this->Paginator->counter(array('format'=>'<span class="page">%page%/%pages%</span>'));
					echo $this->Paginator->first('<<',array('separator'=>false,'title'=>__('Trang đầu',true)));
					echo $this->Paginator->numbers(array('separator'=>false,'modulus'=>7,'class'=>'number'));
					echo $this->Paginator->last('>>',array('separator'=>false,'title'=>__('Trang cuối',true)));
				?>
			</div> <!-- end .paginator -->

		</div> <!--  end .des -->

		<div class="top"></div>
		<div class="bottom"></div>
	</div> <!--  end .box_content -->
</article>
<!-- end products/maker.ctp -->