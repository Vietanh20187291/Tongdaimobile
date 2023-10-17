<article class="">
	<?php if($a_promotions_c){?>
	<div class="box_content">
		<header class="title">
			<h1><?php echo __('Sản phẩm bán chạy',true)?></h1>
		</header>
		<div class="des row auto-clear">
			<?php
			echo $this->element('frontend/c_product',array(
															'data'		=> $a_promotions_c,
															'position'	=> '',
															'limit'		=> '',
															'cart'		=> true,
															'class'	=> 'col-xs-12 col-sm-6 col-md-4 col-lg-3',
															'w'			=> 400,
															'zc'		=> 2
														))
			?>
			<?php
				$url = array('controller'=>'products','action' => 'index','lang'=>$lang,'slug0'=>'san-pham-khuyen-mai');
				$a_params = $this->params;
				if(!empty($a_params['sort']) && !empty($a_params['direction'])) $url = array_merge($url,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
				$this->Paginator->options(array(
					'url'=>$url
				));

			?>
			<div class="clear"></div>
			<div class="paginator">
				<?php
					if(substr($this->Paginator->counter(),-1)>1) echo '<span class="page">Trang</span>';
					echo $this->Paginator->first('<<',array('separator'=>false,'title'=>__('Trang đầu',true)));
					echo $this->Paginator->numbers(array('separator'=>false,'modulus'=>7,'class'=>'number'));
					echo $this->Paginator->last('>>',array('separator'=>false,'title'=>__('Trang cuối',true)));
				?>
			</div> <!-- end .paginator -->

		</div> <!--  end .des -->
	</div> <!--  end .box_content -->

	<?php }else{?>
	<div class="col-xs-12 wait">
		<p><?php echo __('Hiện tại chưa có sản phẩm khuyến mại',true)?></p>
	</div>
	<?php }?>
</article>