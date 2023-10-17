<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button id="close-btn" type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-uppercase"><?php echo __('Giỏ hàng',true)?></h4>
      </div>
      <div class="modal-body">
        <div class="shoping_cart">
				<?php if(!empty($a_products_cart)){?>
				<table class="table">
					<tr class="first">
						<th><?php echo __('Sản phẩm',true)?></th>
						<th class="small"><?php echo __('Giá',true)?></th>
						<th class="small"><?php echo __('Màu sắc',true)?></th>
						<th class="small"><?php echo __('Kích cỡ',true)?></th>
						<th class="small"><?php echo __('Số lượng',true)?></th>
						<th class="small"><?php echo __('Giá',true)?></th>
						<th class="small">Xóa</th>
					</tr>
					<?php foreach($a_products_cart as $val){
					$item = $val['Product'];
					if(!empty($val['ProductAttribute'])) $item_attribute = $val['ProductAttribute'];
					?>
					<tr class="content_cart">
						<td class="small">
						<?php
							$w = 80;
							$full_size = $oneweb_product['size']['product'];
							$h = intval($w*$full_size[1]/$full_size[0]);
							echo $this->OnewebVn->thumb('products/'.$item['image'],array('alt'=>$item['name'],'width'=>$w,'height'=>$h, 'class' => 'thumb'));
						?>
						<div class="infos">
							<?php
								echo $this->Html->tag('p',$item['name'],array('class'=>'name'));
								if($item['quantity']>0) echo $this->Html->tag('p',__('Còn hàng',true),array('class'=>'text-success'));
								else echo $this->Html->tag('p',__('Hết hàng',true),array('class'=>'text-danger'));
								if(!empty($item['promotion'])) echo $this->Html->tag('p',__('Khuyến mãi',true).': '.$this->OnewebVn->rawText($item['promotion']),array('class'=>'promotion'));
							?>
						</div>
						</td>
						<td class="small">
						<?php
							if($a_currency_c['location']=='first') echo $a_currency_c['name'].' ';

							$price = $item['price'];
// 							$discount = 0;
// 							if(!empty($item['discount'])){
// 								if($item['discount_unit']){
// 									$price = $price-($price*$item['discount']/100);				//Giảm giá theo %
// 									$discount = $item['discount'];
// 								}else{
// 									$price = $price - $item['discount'];												//Giảm số tiền nhập vao
// 									$discount = $item['discount']*100/$price;
// 								}
// 							}
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
						<td class="center">
							<?php if(!empty($item['color'])) echo $item['color'] ?>
						</td>
						<td class="center">
							<?php if(!empty($item['size'])) echo $item['size'] ?>
						</td>
						<td class="center">
							<?php
								$options = array();

								if(!empty($item_attribute)) {
									for($i=1;$i<=$item_attribute['qty'];$i++) $options[$i] = $i;
									echo $this->Form->input('qty',array('type'=>'select','options'=>$options,'value'=>$item['qty'],'onchange'=>"addToCart({$item['id']},this.value,false,'{$item['color']}','{$item['size']}')",'label'=>false,'div'=>false));
								} else {
									for($i=1;$i<=$item['quantity'];$i++) $options[$i] = $i;
									echo $this->Form->input('qty',array('type'=>'select','options'=>$options,'value'=>$item['qty'],'onchange'=>"addToCart({$item['id']},this.value,false,'','')",'label'=>false,'div'=>false));
								}
							?>
						</td>
						<td class="small price">
						<?php
							echo $this->Html->tag('span',number_format($price*$item['qty']/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'new font-weight-bold color-red'));
						?>
						</td>
						<td><?php echo $this->Html->link('&nbsp;','javascript:;',array('title'=>__('Xóa',true),'onclick'=>"delProCart({$item['id']})",'class'=>'icon_oneweb icon_delete','escape'=>false))?></td>
					</tr>
					<?php }?>
					<tr class="total_fee">
							<td colspan="4"><?php echo __('Phí vận chuyển',true).': '?></td>
							<td colspan="3">
								<strong class="color-red">
                                    Tính theo khoảng cách (sẽ báo khách sau)
<!--									--><?php
//								if($a_currency_c['location']=='first') echo $a_currency_c['name'].' ';
//								echo number_format($surcharge/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']);
//								if($a_currency_c['location']=='last') echo ' '.$a_currency_c['name'];
//								?>
								</strong>
							</td>
						</tr>
				</table>
				<div class="payment text-right">
				<p class="total"><span><?php echo __('Tổng',true)?></span>
					<?php
					echo $this->Html->tag('span',number_format($total_cart/$a_currency_c['value'],$a_currency_c['decimal'],$a_currency_c['sep1'],$a_currency_c['sep2']).' '.$a_currency_c['name'],array('class'=>'new font-weight-bold color-red'));
					?>
				</p>
				</div>
				<?php }else echo $this->Html->tag('p class="text-center alert alert-warning"',__('Giỏ hàng trống',true),array('class'=>'empty_order'))?>
			</div> <!-- end .list_product -->
      </div>
      <div class="modal-footer">
      	<div class="col-xs-6 col-sm-offset-7 col-sm-3 text-center">
        <?php if(!empty($a_products_cart))  echo $this->Html->link(__('Đặt hàng',true),array('controller'=>'orders','action'=>'info','lang'=>$lang,'ext'=>'html'),array('title'=>__('Đặt hàng',true),'class'=>'btn btn-default btn-custom'))?>
        </div>
		<div class="col-xs-6 col-sm-2 text-center">
			<button class="btn btn-default btn-custom" onclick="continueBuy()"><?php echo __('Tiếp tục mua',true); ?></button>
		</div>
      </div>
    </div>
  </div>

<script>
	function continueBuy() {
		$('#close-btn').click();
		return false;
	}
</script>
