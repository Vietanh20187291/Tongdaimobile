<?php if(!empty($a_order_c)){
	$a_order = $a_order_c['Order'];
	$a_order['content'] = @unserialize($a_order['content']);
?>

<article class="box_content history_payment">
        <div class="title">
			<div class="title_right">
				<div class="title_center">
				<header><h1><span><?php echo __('Chi tiết đơn hàng', true);?></span></h1></header>
				</div> <!--  end .title_center -->
			</div> <!--  end .title_right -->
		</div>
		<div class="des">
			<?php 
				
				
			?>
			<h3>Thông tin đặt hàng</h3>
			<table class="info">
				<tr>
					<th nowrap="nowrap"><?php echo __('Họ tên',true);?>:</th>
					<td><?php echo $a_order['name']; ?></td>
				</tr>
				<tr>
					<th><?php echo __('Email', true); ?>:</th>
					<td><?php echo $a_order['email']; ?></td>
				</tr>
				<tr>
					<th><?php echo __('Điện thoại', true);?>:</th>
					<td><?php echo $a_order['phone']; ?></td>
				</tr>
				<tr>
					<th><?php echo __('Địa chỉ', true);?>:</th>
					<td><?php echo $a_order['address'];?></td>
				</tr>
				<tr>
					<th><?php echo __('Ngày đặt', true);?>:</th>
					<td><?php echo date('d/m/Y',$a_order['created']); ?></td>
				</tr>
				<tr>
					<th><?php echo __('Tin nhắn', true);?>:</th>
					<td><?php echo $a_order['message']; ?></td>
				</tr>
			 </table>
			<h3>Nội dung đơn hàng</h3>
			<table class="detail_order">
			 	<tr>
					<th><?php echo __('Tên sản phẩm', true);?></th>
					
					<th><?php echo __('Giá', true);?></th>
					<th><?php echo __('Số lượng', true);?></th>
					<th><?php echo __('Số tiền', true);?></th>	
			 	</tr>
			 	<?php 
			 	$sep1 = ',';
			 	$sep2 = '.';
			 	$decimal = 0;
			 	if(!in_array($a_order['unit_payment'], array('đ','d','vnđ','vnd','Đ','D','VNĐ','VND'))){
			 		$sep1 = '.';
			 		$sep2 = ',';
			 		$decimal = 2;
			 	}
			 	foreach($a_order['content'] as $val){
			 		$item_product = $val['Product'];
			 		
			 	?>
			 	<tr>
				 	<td align="left">
				 		<?php
							echo $this->Html->tag('p',$item_product['name'],array('class'=>'name'));
							echo $this->Html->tag('p',($item_product['quantity']>0)?__('Còn hàng',true):__('Hết hàng',true),array('class'=>'status'));
							if(!empty($item_product['promotion'])) echo $this->Html->tag('p',__('Khuyến mãi',true).': '.$item_product['promotion'],array('class'=>'promotion'));
						?>
				 	</td>	
				 	
				 	<td align="center">
			 		<?php 
						$price = $item_product['price'];
						$discount = 0;			
						if(!empty($item_product['discount'])){
							if($item_product['discount_unit']){
								$price = $price-($price*$item_product['discount']/100);				//Giảm giá theo %
								$discount = $item_product['discount'];
							}else{
								$price = $price - $item_product['discount'];												//Giảm số tiền nhập vao
								$discount = $item_product['discount']*100/$price;
							}
						}
						echo $this->Html->tag('span',number_format($price/$a_order['rate'],$decimal,$sep1,$sep2),array('class'=>'new')).' '.$a_order['unit_payment'];
						
						if(!empty($item_product['discount'])){
							echo $this->Html->tag('p',number_format($item_product['price']/$a_order['rate'],$decimal,$sep1,$sep2),array('class'=>'old')).' ';
							echo $this->Html->tag('p',__('Giảm giá',true).': '.round($discount).'%',array('class'=>'discount'));
						}
					?>
				 	</td>
				 	<td align="center"><?php echo $item_product['qty'];?></td>
				 	<td align="center">
				 		<?php 
							echo number_format($price*$item_product['qty']/$a_order['rate'],$decimal,$sep1,$sep2).' '.$a_order['unit_payment'];
						?>
				 	</td>
			 	</tr>
			 	<?php }?>
			 	<tr class="total">
					<th colspan="3"><?php echo __('Tổng',true).': '?></th>
					<td><?php echo number_format($a_order['total']/$a_order['rate'],$decimal,$sep1,$sep2).' '.$a_order['unit_payment'];?></td>
				</tr>
			 </table>
			
		</div><!-- End .des -->
	
</article> <!--  end .box_content -->
<?php } ?>