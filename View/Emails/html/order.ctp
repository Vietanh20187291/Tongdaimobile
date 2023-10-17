<div style="width: 600px; font-size: 1em; margin: 0 auto;">
	<?php echo $this->Html->tag('p',__('Mã đơn hàng',true).': '.$data['transaction_code'],array('style'=>'font-weight:bold'))?>

	<?php
	if(!$admin) echo $config['product_description'];
	else{
	?>
	<h3 style="margin: 15px 0 5px;color:#fff;background: #333;padding: 2px 3px;font-size:16px;"><?php echo __('Thông tin khách hàng',true)?></h3>
	<table style="border: 1px solid #ff000; color: #333; width: 100%; border-collapse: collapse;margin-top: 6px;">
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px; width: 120px"><?php echo __('Họ tên',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 2px 0 2px 5px;"><?php if (isset($data['name'])) echo $data['name']?></td>
		</tr>
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px; width: 120px"><?php echo __('Email',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 2px 0 2px 5px;"><?php if (isset($data['email'])) echo $data['email']?></td>
		</tr>
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px; width: 120px"><?php echo __('Điện thoại',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 2px 0 2px 5px;"><?php if (isset($data['phone'])) echo $data['phone']?></td>
		</tr>
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px; width: 120px"><?php echo __('Địa chỉ',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 2px 0 2px 5px;"><?php if (isset($data['address'])) echo $data['address']?></td>
		</tr>
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px; width: 120px"><?php echo __('Tin nhắn',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 2px 0 2px 5px;"><?php if (isset($data['message'])) echo $data['message']?></td>
		</tr>
	</table>

	<?php }?>

	<h3 style="margin: 15px 0 5px;color:#fff;background: #333;padding: 2px 3px;font-size:16px;"><?php echo __('Thông tin thanh toán',true)?></h3>
	<table style="border: 1px solid #ff000; color: #333; width: 100%; border-collapse: collapse;">
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px; width: 120px"><?php echo __('Hình thức thanh toán',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 2px 0 2px 5px;"><?php if (isset($data['method_payment'])) echo $data['method_payment']?></td>
		</tr>
		<?php if (isset($data['bank_info'])) { ?>
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px; width: 120px"><?php echo __('Thông tin chuyển khoản',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 5px;"><?php echo html_entity_decode($data['bank_info']) ?></td>
		</tr>
		<?php } ?>
	</table>

	<h3 style="margin: 15px 0 5px;color:#fff;background: #333;padding: 2px 3px;font-size:16px;"><?php echo __('Thông tin đơn hàng',true)?></h3>
	<table style="border: 1px solid #ff000; color: #333; width: 100%; border-collapse: collapse;">
		<tr>
			<th style="background:#eee;border:1px solid #ddd;line-height:22px;padding:5px;text-align:left"><?php echo __('Sản phẩm',true)?></th>
			<th style="background:#eee;border:1px solid #ddd;line-height:22px;padding:5px; width: 1px; white-space:nowrap;text-align:left"><?php echo __('Giá',true)?></th>
			<th style="background:#eee;border:1px solid #ddd;line-height:22px;padding:5px; white-space:nowrap"><?php echo __('Màu sắc',true)?></th>
			<th style="background:#eee;border:1px solid #ddd;line-height:22px;padding:5px; white-space:nowrap"><?php echo __('Kích cỡ',true)?></th>
			<th style="background:#eee;border:1px solid #ddd;line-height:22px;padding:5px; white-space:nowrap"><?php echo __('SL',true)?></th>
			<th style="background:#eee;border:1px solid #ddd;line-height:22px;padding:5px; white-space:nowrap;text-align:left"><?php echo __('Giá',true)?></th>
		</tr>
		<?php
			$sep1 = ',';
			$sep2 = '.';
			$decimal = 0;
			if(!in_array($data['unit_payment'], array('đ','d','vnđ','vnd','Đ','D','VNĐ','VND'))){
				$sep1 = '.';
				$sep2 = ',';
				$decimal = 2;
			}

			foreach (unserialize($data['content']) as $key=>$val){
				$item_product = $val['Product'];
		?>
		<tr>
			<td style="border: 1px solid #eee;padding: 3px 5px;">
			<?php
				echo $this->Html->tag('p',$item_product['name'],array('style'=>'font-weight:bold;margin:0;line-height:25px'));
				echo $this->Html->tag('p',($item_product['quantity']>0)?__('Còn hàng',true):__('Hết hàng',true),array('style'=>'font-size:0.9em; margin:0;color:green'));
				if(!empty($item_product['promotion'])) echo $this->Html->tag('p',__('Khuyến mãi',true).': '.$item_product['promotion'],array('style'=>'font-size:0.9em; margin:0;color:#ff0000'));
			?>
			</td>
			<td style="border: 1px solid #eee;padding: 3px 5px; white-space:nowrap">
			<?php
// 				$price = $item_product['price'];
// 				$discount = 0;
// 				if(!empty($item_product['discount'])){
// 					if($item_product['discount_unit']){
// 						$price = $price-($price*$item_product['discount']/100);				//Giảm giá theo %
// 						$discount = $item_product['discount'];
// 					}else{
// 						$price = $price - $item_product['discount'];												//Giảm số tiền nhập vao
// 						$discount = $item_product['discount']*100/$price;
// 					}
// 				}
// 				echo $this->Html->tag('span',number_format($price/$data['rate'],$decimal,$sep1,$sep2),array('style'=>'font-weight:bold;margin:0;')).' '.$data['unit_payment'];

// 				if(!empty($item_product['discount'])){
// 					echo $this->Html->tag('p',number_format($item_product['price']/$data['rate'],$decimal,$sep1,$sep2),array('style'=>'font-size:0.9em; margin:0;color:#555;text-decoration:line-through')).' ';
// 					echo $this->Html->tag('p',__('Giảm giá',true).': '.round($discount).'%',array('style'=>'font-size:0.9em; margin:0;color:#ff0000'));
// 				}
				if(!empty($item_product['price_new'])) {
					$price = $item_product['price_new'];
				}else{
					$price = $item_product['price'];
				}
				echo $this->Html->tag('b',number_format($price/$data['rate'],$decimal,$sep1,$sep2).' '.$data['unit_payment'],array('class'=>'new font-weight-bold color-red'));
				echo '</br>';
				if(!empty($item_product['price_new'])){
					echo $this->Html->tag('del',number_format($item_product['price']/$data['rate'],$decimal,$sep1,$sep2).' '.$data['unit_payment'],array('class'=>'font-weight-bold price_old'));
					if(!empty($item_product['discount'])) echo $this->Html->tag('p class="font-style-italic"',__('Giảm giá',true).': '.$item_product['discount'].'%',array('class'=>'discount'));
				}
			?>
			</td>
			<td style="border: 1px solid #eee;padding: 3px 5px;text-align:center"><?php echo $item_product['color']?></td>
			<td style="border: 1px solid #eee;padding: 3px 5px;text-align:center"><?php echo $item_product['size']?></td>
			<td style="border: 1px solid #eee;padding: 3px 5px;text-align:center"><?php echo $item_product['qty']?></td>
			<td style="border: 1px solid #eee;padding: 3px 5px;">
			<b><?php
				echo number_format($price*$item_product['qty']/$data['rate'],$decimal,$sep1,$sep2).' '.$data['unit_payment'];
			?></b>
			</td>
		</tr>
		<?php }?>
		<tr class="total_fee">
			<td colspan="4"><?php echo __('Phí vận chuyển',true).': '?></td>
			<td  colspan="2">
				<strong class="color-red">
					<?php
				echo number_format($data['surcharge']/$data['rate'],$decimal,$sep1,$sep2). ' '.$data['unit_payment'];
				?>
				</strong>
			</td>
		</tr>
		<tr>
			<td colspan="4" style="color:#ff0000;text-align:right;padding-right:10px;line-height:25px"><?php echo __('Tổng',true).': '?></td>
			<td colspan="2" style="color:#ff0000; font-weight:bold;line-height:25px"><?php echo number_format($data['total']/$data['rate'],$decimal,$sep1,$sep2).' '.$data['unit_payment'];?></td>
		</tr>
		<tr>
			<td colspan="9"></br></td>
		</tr>
		<tr>
			<td colspan="9"><p style="font-style: italic;"><?php echo __('- Sau khi nhận được đơn hàng của quý khách, nhân viên của hàng sẽ liên lạc lại với quý khách để xác nhận lại đơn hàng. Cảm ơn quý khách đã quan tâm đến sản phẩm của chúng tôi !',true)?></p></td>
		</tr>
	</table>
</div>