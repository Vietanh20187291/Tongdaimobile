
<?php
	$controller = $this->params['controller'];
	$action = $this->params['action'];
	$flag = 1;

	switch ($controller)
	{
		case 'products':
			$flag = 0;
			break;
		case 'filters':
			$flag = 0;
			break;
		case 'orders':
			$flag = 0;
			break;
		case 'sitemaps':
			$flag = 0;
			break;
		case 'members':
			$flag = 0;
			break;
	}

	$flag = true;
	if (isset($active_slideshow)) $flag = $active_slideshow;
	if ( ! empty($a_slideshows_c) && $flag && is_array($a_slideshows_c))
	{

		$a_size = $oneweb_banner['size'][2];
		// Kích thước ảnh thumbnail
		$size_thumb[] = 1400;
		$size_thumb[] = intval($size_thumb[0]*$a_size[1]/$a_size[0]);
?>
<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 product-nav-tree-slideshow-wrapper">
	<ul class="nav navbar-nav">
		<li id="product-nav-tree-slideshow" class="dropdown banner open">
			<?php echo $this->OnewebVn->productCategoryNavBanner($a_product_categories_s,0)?>
		</li>
	</ul>
	<div class="col-xs-12 request_support">
		<?php echo $this->Form->create('Contact', array('url' => array('controller' => 'contacts', 'action' => 'request_support', 'lang' => $lang),'id'=>'form_request', 'inputDefaults' => array('div' => false, 'label' => false))) ?>
		<div class="content">
			<header class="title">
				<?php echo __('Yêu cầu tư vấn') ?>
			</header>
			<div class="description">
				<div class="text-center p-b-15"><?php echo __('Điền số điện thoại vào form bên dưới') ?><br>
					<?php echo __('Nhân viên tư vấn sẽ gọi cho bạn') ?>
				</div>
				<div class="form-group clearfix">
					<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 p-b-10">
						<?php
						echo $this->Form->input('phone', ['class' => 'form-control', 'id' => 'phoneContact']);
					?>
					</div>
					<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-center">
						<?php echo $this->Form->submit(__('Gửi'),array('class'=>'btn btn-default', 'div' => false))?>
					</div>
				</div>
			</div>
		</div>
		<?php echo $this->Form->end();?>
	</div>
</div>

<div id="slideshow-right" class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
	<div id="slideshow-wrapper">
		<div id="slideshow" class="full_width <?php echo $controller?>">
			<?php
				echo $this->Html->css($oneweb_web['layout'].'/slideshow/css/lightslider.css');
				echo $this->Html->script('slideshow/js/lightslider.js');
			?>
			<ul id="lightSlider" style="display: none;">
				<?php
					foreach ($a_slideshows_c as $key => $val)
					{
						$item = $val['Banner'];

						$link_attr = array('title' => $item['name'], 'target' => $item['target'], 'escape' => false);
						if ($item['rel'] != 'dofollow') $link_attr['rel'] = $item['rel']
					?>
						<li>
							<?php
								if ( ! empty($item['link'])) echo $this->Html->link($this->OnewebVn->thumb('banners/'.$item['image'], array('width' => $size_thumb[0], 'height' => $size_thumb[1], 'alt' => $item['name'], 'id'=>"wows1_{$key}",'class' => 'img-responsive')),$item['link'],$link_attr);
								else echo $this->OnewebVn->thumb('banners/'.$item['image'], array('width' => $size_thumb[0], 'height' => $size_thumb[1], 'alt' => $item['name'], 'id'=>"wows1_{$key}",'class' => 'img-responsive'))
// 	                            if ( ! empty($item['link'])) echo $this->Html->link(null,$item['link'],$link_attr);
							?>

							<?php
								if ( ! empty($item['description']))
								{
							?>
								<div style="left:5px; top:94px; width:336px;">
									<span class='title'><?php echo $item['name']; ?></span>
										<?php echo $item['description']; ?>
								</div>
							<?php
								}
							?>
						</li>
					<?php
						}
					?>
			</ul>
		</div>
	</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 banner-under-slideshow owl-carousel owl-theme">
	<?php
		if(!empty($a_partner_11)) {
			foreach($a_partner_11 as $val)
			{
			$item = $val['Banner'];
			$attr = array('alt'=>$item['name'],'class'=>'img-responsive');
			if($oneweb_banner['size']['11'][0]!='n') $attr = array_merge($attr,array('width'=>$oneweb_banner['size']['11'][0]));
			if($oneweb_banner['size']['11'][1]!='n') $attr = array_merge($attr,array('height'=>$oneweb_banner['size']['11'][1]));

			$str_banner = $this->Html->image('images/banners/'.$item['image'],$attr);

			$link_attr = array('title'=>$item['name'],'target'=>$item['target'],'class'=>'','escape'=>false);
			if($item['rel']!='dofollow') $link_attr['rel'] = $item['rel'];

			if(!empty($item['link'])) $str_banner = $this->Html->link($str_banner,$item['link'],$link_attr);
	?>
		<?php echo $str_banner; ?>
	<?php
			}
		}
	?>
</div>

<script>
	$(document).ready(function() {
		$('#lightSlider').css('display', 'block');

		$("#lightSlider").lightSlider({
			item: 1,
			easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
			speed: 1200,
			pause: 10000,
			auto: true,
			loop: true,
			pager: true,
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		var owl = $(".banner-under-slideshow");
		owl.owlCarousel({
			autoplay : true,
			autoplayTimeout : 8000,
			autoplayHoverPause : true,
			loop : true,
			nav : false,
			margin : 8,
			dots : true,
			responsiveClass : true,
			responsive : {
				// breakpoint from 0 up
				0 : {
						items : 1
				},
				// breakpoint from 348 up
				348 : {
						items : 1
				},
				// breakpoint from 600 up
				600 : {
						items : 3
				},
				// breakpoint from 900 up
				900 : {
						items : 3
				},
				// breakpoint from 1000 up
				1000 : {
						items : 3
				}
			},
			navText : ["<i class='glyphicon glyphicon-chevron-left'></i>","<i class='glyphicon glyphicon-chevron-right'></i>"]
		});
	});
</script>

<?php } ?>
<!-- end c_slideshow.ctp