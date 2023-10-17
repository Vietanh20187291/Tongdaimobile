<!-- start cart.ctp -->
<script type="text/javascript">
	$(document).ready(function(){
		$("#cart a.title").click(function(){
			if($("#cart div").css('display')=='none') $("#back-top").animate({'bottom':'80px'},50);
			else $("#back-top").animate({'bottom':'25px'},50);
			$("#cart div").toggle(50);
		})
	})
</script>

<div id="cart">
	<a href="javascript:;" class="title"><?php echo __('Giỏ hàng',true)?></a>
	<div>
		<p class="h"><?php echo __('Giỏ hàng có',true)?> <span id="number_product">1</span> <?php echo __('sản phẩm',true)?></p>
		<p class="h link">
			<?php
				echo $this->Html->link(__('Giỏ hàng',true),'',array('title'=>__('Giỏ hàng',true),'rel'=>'nofollow')). ' | ';
				echo $this->Html->link(__('Thanh toán',true),'',array('title'=>__('Thanh toán',true),'rel'=>'nofollow'));
			?>
		</p>
	</div>
</div>
<!-- end cart.ctp -->