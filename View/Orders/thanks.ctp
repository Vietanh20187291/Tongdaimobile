<!-- start orders/thanks.ctp -->
<!-- Event snippet for WEB_Đặt hàng conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-663378581/qTpYCNe2jcsBEJW1qbwC',
      'transaction_id': ''
  });
</script>
<article class="box_content col-xs-12 m-b-15 order thank">
	<header class="title">
		<h1><?php echo __('Gửi đơn hàng thành công',true)?></h1>
	</header>

	<div class="des">
		<?php echo $a_product_configs_c['thank']?>

		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<h3><b><?php echo __('Thông tin đơn hàng đã đặt'); ?></b></h3>
				<p><b><?php echo __('Họ tên: ');?></b><?php echo $order_detail['name']; ?></p>
				<p><b><?php echo __('Số điện thoại: ');?></b><?php echo $order_detail['phone']; ?></p>
				<p><b><?php echo __('Địa chỉ: ');?></b><?php echo $order_detail['address']; ?></p>
				<p><b><?php echo __('Hình thức thanh toán: ');?></b><?php echo $order_detail['method_payment']; ?></p>
				<?php if(!empty($order_detail['bank_info'])) { ?>
				<p><?php echo html_entity_decode($order_detail['bank_info']); ?></p>
				<?php } ?>
			</div>
			<div class="col-xs-12 col-sm-6">
				<?php if(!empty($order_info_c)){?>
					<table class="table">
						<tr>
							<th><?php echo __('Sản phẩm',true)?></th>
							<th class="small center"><?php echo __('Giá',true)?></th>
							<th class="small center"><?php echo __('Màu sắc',true)?></th>
							<th class="small center"><?php echo __('Kích thước',true)?></th>
							<th class="small center"><?php echo __('Số lượng',true)?></th>
							<th class="small center"><?php echo __('Thành tiền',true)?></th>
						</tr>
						<?php foreach($order_info_c['detail'] as $val){
						$item = $val['Product'];
						?>
						<tr>
							<td>
							<?php
								echo $this->Html->tag('p',$item['name'],array('class'=>'name'));
								if($item['quantity']>0) echo $this->Html->tag('p',__('Còn hàng',true),array('class'=>'text-success'));
								else echo $this->Html->tag('p',__('Hết hàng',true),array('class'=>'text-danger'));
								if(!empty($item['promotion'])) echo $this->Html->tag('p',__('Khuyến mãi',true).': '.$item['promotion'],array('class'=>'promotion'));
							?>
							</td>
							<td class="small">
							<?php
								if(!empty($item['price_new'])) {
									$price = $item['price_new'];
								}else{
									$price = $item['price'];
								}
								echo $this->Html->tag('span',number_format($price/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'new font-weight-bold color-red'));
								echo '</br>';
								if(!empty($item['price_new'])){
									echo $this->Html->tag('del',number_format($item['price']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'font-weight-bold price_old'));
									if(!empty($item['discount'])) echo $this->Html->tag('p class="font-style-italic"',__('Giảm giá',true).': '.$item['discount'].'%',array('class'=>'discount'));
								}
							?>
							</td>
							<td class="center"><?php echo $item['color']?></td>
							<td class="center"><?php echo $item['size']?></td>
							<td class="center"><?php echo $item['qty']?></td>
							<td class="small price">
							<?php
								if($a_currency_c['location']=='first') echo $a_currency_c['name'].' ';
								echo number_format($price*$item['qty']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']);
								if($a_currency_c['location']=='last') echo ' '.$a_currency_c['name'];
							?>
							</td>
						</tr>
						<?php }?>
						<tr class="total_fee">
<!--							<td colspan="4">--><?php //echo __('Phí vận chuyển',true).': '?><!--</td>-->
<!--							<td colspan="2">-->
<!--								<strong class="color-red">-->
<!--									--><?php
//								if($a_currency_c['location']=='first') echo $a_currency_c['name'].' ';
//								echo number_format($order_info_c['surcharge']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']);
//								if($a_currency_c['location']=='last') echo ' '.$a_currency_c['name'];
//								?>
<!--								</strong>-->
<!--							</td>-->
						</tr>
						<tr class="total">
							<td colspan="2"></td>
							<td align="right"><?php echo __('Tổng',true).' (chưa tính phí vận chuyển): '?></td>
							<td colspan="2">
								<strong class="color-red">
									<?php
								if($a_currency_c['location']=='first') echo $a_currency_c['name'].' ';
								echo number_format($order_info_c['total']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']);
								if($a_currency_c['location']=='last') echo ' '.$a_currency_c['name'];
								?>
								</strong>
							</td>
						</tr>
					</table>
				<?php }?>

			</div>
		</div>

		<?php echo $this->Html->link($this->Html->tag('span',__('Trở về trang chủ',true)),array('controller'=>'pages','action'=>'home','lang'=>$lang),array('title'=>__('Trang chủ',true),'rel'=>'nofollow','escape'=>false))?>
	</div>
</article>
<!-- end orders/thanks.ctp -->
