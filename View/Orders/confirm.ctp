<!-- start orders/confirm.ctp -->
<article class="box_content order confirm col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<header class="title">
		<h1><?php echo __('Xác nhận',true)?></h1>
	</header>

	<div class="des row">
		<div class="form col-xs-12 col-sm-6 col-md-6">
			<?php
				echo $this->Form->create('Order',array('inputDefaults'=>array('div'=>false,'label'=>false)));
				echo $this->Form->input('unit_payment',array('type'=>'hidden','value'=>$a_currency_c['name']));
				echo $this->Form->input('unit_default',array('type'=>'hidden','value'=>$a_currency_default_c['name']));
				echo $this->Form->input('rate',array('type'=>'hidden','value'=>$a_currency_c['value']));
			?>

			<div class="form-group">
				<div class="row">
					<?php
						echo $this->Html->tag('span',__('Tên người nhận',true),array('class'=>'col-xs-4'));
					?>
					<div class="col-xs-8">
						<?php echo $a_customer_c['name']; ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<?php
						echo $this->Html->tag('span',__('Điện thoại',true),array('class'=>'col-xs-4'));
					?>
					<div class="col-xs-8">
						<?php echo $a_customer_c['phone']; ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<?php
						echo $this->Html->tag('span',__('Email',true),array('class'=>'col-xs-4'));
					?>
					<div class="col-xs-8">
						<?php echo $a_customer_c['email']; ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<?php
						echo $this->Html->tag('span',__('Địa chỉ nhận hàng',true),array('class'=>'col-xs-4'));
					?>
					<div class="col-xs-8">
						<?php echo $a_customer_c['address']; ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<?php
						echo $this->Html->tag('span',__('Yêu cầu khác nếu bạn muốn',true),array('class'=>'col-xs-4'));
					?>
					<div class="col-xs-8">
						<?php echo $a_customer_c['message']; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="order_info  col-xs-12 col-sm-6 col-md-6">
			<span class="title font-weight-bold"><?php echo __('Thông tin đơn hàng',true)?></span>
			<?php if(!empty($order_info_c)){?>
				<table class="table">
					<tr>
						<th><?php echo __('Sản phẩm',true)?></th>
						<th class="small center"><?php echo __('Giá',true)?></th>
						<th class="small center"><?php echo __('Màu sắc',true)?></th>
						<th class="small center"><?php echo __('Kích cỡ',true)?></th>
						<th class="small center"><?php echo __('SL',true)?></th>
						<th class="small center"><?php echo __('Giá',true)?></th>
					</tr>
					<?php foreach($order_info_c['detail'] as $val){
					$item = $val['Product'];
					?>
					<tr>
						<td>
						<?php
							echo $this->Html->tag('p',$item['name'],array('class'=>'name'));
							echo $this->Html->tag('p',($item['quantity']>0)?__('Còn hàng',true):__('Hết hàng',true),array('class'=>'status'));
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
					<tr class="total">
						<td colspan="6"></td>
						<td align="center"><?php echo __('Tổng',true).': '?></td>
						<td>
							<strong class="color-red">
								<?php
							if($a_currency_c['location']=='first') echo $a_currency_c['name'].' ';
							echo number_format($order_info_c['total']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']);
							if($a_currency_c['location']=='last') echo ' '.$a_currency_c['name'];
							?>
                                (chưa kể tiền ship)
							</strong>
						</td>
					</tr>
					<tr>
						<td colspan="6"><p class="text_nofi"><?php echo __('Sau khi nhận được đơn hàng của quý khách, nhân viên của hàng sẽ liên lạc lại với quý khách để xác nhận lại đơn hàng. Cảm ơn quý khách đã quan tâm đến sản phẩm của chúng tôi !',true)?></p></td>
					</tr>
				</table>
			<?php }else echo __('Giỏ hàng trống',true)?>
		</div>
		<div class="submit col-xs-12 text-center m-b-15">
				<?php echo $this->Form->submit(__('Gửi đơn hàng',true),array('div'=>false,'class'=>'btn btn-default btn-oneweb'))?>
			</div>
			<?php echo $this->Form->end();?>
	</div>

	<div class="top"></div>
	<div class="bottom"></div>
</article>
<!-- end orders/confirm.ctp -->
