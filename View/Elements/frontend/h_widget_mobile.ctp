<nav class="navbar" role="navigation">
	<ul class="nav navbar-nav <?php if($this->params['controller'] == 'products' && $this->params['ext'] == 'html') echo 'widget_btn_cart' ?>">
		<li class="col">
			<?php
			foreach ($a_support_s as $val)
			{
				$item_support = $val['Support'];
				if (strtolower($item_support['name']) == 'hotline') {
					echo $this->Html->link('<div><span class="fa fa-phone"></span> <span class="lbl">Gọi mua hàng</span></div>', 'tel:'.$this->OnewebVn->rawPhone($item_support['phone']),array('escape'=>false, 'class' => 'tel', "onclick"=>"gtag_report_conversion('tel:".preg_replace('/\s|\-/', '', $item_support['phone'])."')"));
					break;
				}
			}
			?>
		</li>
		<li class="col">
			<a onclick="requestSupport()" class="request-support">
				<div>
					<span class="fa fa-send"></span>
					<span class="lbl"><?php echo __('Yêu cầu gọi lại'); ?></span>
				</div>
			</a>
		</li>
		<?php if($this->params['controller'] == 'products' && $this->params['ext'] == 'html') { ?>
		<li class="col">
			 <a href="javascript:;" onclick="addToCartF()" class="btn-cart">
          <div>
          	<i class="fa fa-cart-plus" aria-hidden="true"></i>
            <span class="lbl"><?php echo __('Thêm vào giỏ hàng'); ?></span>
          </div>
        </a>
		</li>
		<?php } ?>
	</ul>
</nav>