<!-- start header.ctp -->
<div id="header" class="navbar-oneweb">
	<div class="container">
		<div class="row">
        <h1 class="text-center" style="color: gray; font-weight: bold;">Tổng đài Mobile - Hỗ trợ đăng ký dịch vụ 3G/4G Online</h1>

			<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-md-push-6 col-lg-push-6">
				<button type="button" class="navbar-toggle" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="menu-text">Menu</span>
				</button>
			</div>

		</div>
	</div>

	<div id="nav-menu">
		<div class="container">
			<div class="row">
			<?php
				$cache_h_nav = Cache::read("cache_h_nav_$lang",'oneweb_view');
				if(!$cache_h_nav) {
					$cache_h_nav = $this->element('frontend/h_nav');
					Cache::write("cache_h_nav_$lang",$cache_h_nav,'oneweb_view');
				}
				echo $cache_h_nav;
			?>
			</div>
		</div>
	</div>
	<div id="nav-mask"></div>
</div>

<?php /* ?>
<div id="nav-menu-mobile">
	<div class="container">
		<div class="row">
			<?php echo $this->element('frontend/h_widget_mobile');?>
		</div>
	</div>
</div>
<?php */ ?>
<div class="clear"></div>
