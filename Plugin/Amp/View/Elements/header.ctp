<!-- start header.ctp -->
<div id="header">
	<div class="container">
		<div class="row">
			<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
				<!-- Logo -->
				<?php
				if($this->params['controller'] == 'pages'){
				?>
				<h1><?php if(!empty($oneweb_web['logo'])) echo $this->Html->link($this->HtmlAmp->amp_image($oneweb_web['logo'],array('alt'=>$_SERVER['HTTP_HOST'],'layout'=>'fixed','width'=>200,'height'=>70)),array('plugin'=>false, 'controller'=>'pages','action'=>'home','lang'=>$lang),array('title'=>$a_configs_h['name'],'class'=>'logo','escape'=>false));?></h1>
				<?php }else{
					if(!empty($oneweb_web['logo'])) echo $this->Html->link($this->HtmlAmp->amp_image($oneweb_web['logo'],array('alt'=>$_SERVER['HTTP_HOST'],'layout'=>'fixed','width'=>200,'height'=>70)),array('plugin'=>false, 'controller'=>'pages','action'=>'home','lang'=>$lang),array('title'=>$a_configs_h['name'],'class'=>'logo','escape'=>false));
				}?>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 box-menu">
				<nav class="navbar-toggle collapsed" role="navigation">
					<div id="menu">
						<div class="navbar-header">
							<button on="tap:sidebar-left.toggle"
					class="ampstart-btn caps m2 navbar-toggle">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="menu-text">Menu</span>
							</button>
				<!-- 			<a class="navbar-brand" href="#">MAIN menu</a> -->
						</div>

					</div>
				</nav>
				
				<div class="input-group cart">
					<div class="input-group-btn">
						<i class="icon_oneweb icon_cart"></i>
					</div>
					<?php
						$qty_cart = 0;
						if($this->Session->check("Order_$lang")){
							$cart = $this->Session->read("Order_$lang");
							for($i=0;$i<count($cart);$i++) $qty_cart+=$cart[$i]['qty'];
						}
						echo $this->Html->link('<span class="sp">'.__('Giỏ hàng ',true).'</span>'.$this->Html->tag('span',"$qty_cart",array('class'=>'number_product_cart')).'<span class="sp"> sản phẩm</span>','#',array('title'=>__('Giỏ hàng',true), 'class'=>'link_cart','data-toggle'=>'modal', 'data-target'=>'#popup_modal','escape'=>false));
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="nav-menu-mobile">
	<div class="container">
		<div class="row">
			<?php echo $this->element('h_nav_mobile');?>
		</div>
	</div>
</div>
<div class="clear"></div>