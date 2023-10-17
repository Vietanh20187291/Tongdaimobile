<?php
	$item_order = $a_order_c['Order'];
    $item_order['content'] = @unserialize($item_order['content']);
	$item_cate = $a_order_c['OrderCategory'];
?>

<div id="column_right">
	<!-- tab -->
	<div id="action_top">
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo __('Mã đơn hàng',true).': '.$item_order['transaction_code']?></a></li>
   		</ul> <!-- end .tabs -->

    	<ul class="action_top_2">
    		<li><?php echo $this->Html->link('&nbsp;',(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit','escape'=>false))?></li>
   		</ul> <!-- end .action_top_2 -->
	</div> <!--  end #action_top -->

	<div id="content">
		<?php
			echo $this->Form->create('Order',array('type'=>'file','id'=>'form','url'=>array('action'=>'view',$item_order['id'],'?'=>array('url'=>(!empty($_GET['url']))?urldecode($_GET['url']):'')),'inputDefaults'=>array('label'=>false,'div'=>false)));
			echo $this->Form->input('id',array('value'=>$item_order['id']));
			echo $this->Form->input('submit',array('type'=>'hidden','id'=>'submit','value'=>'save'));
		?>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="add column1">
					<tr>
						<th><?php echo __('Mã đơn hàng',true)?></th>
						<td><?php echo $item_order['transaction_code']?></td>
					</tr>
					<tr>
						<th>IP/Proxy</th>
						<td>
							<?php
								echo $item_order['ip'];
								if(!empty($item_order['proxy'])) echo '/'.$item_order['proxy'];
								echo '&nbsp;&nbsp;&nbsp;'.$this->Html->link('Kiểm tra IP','http://whois.domaintools.com/'.$item_order['ip'],array('title'=>'Kiểm tra IP','class'=>'tooltip','target'=>'_blank'))
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo __('Ngày đặt',true)?></th>
						<td><?php echo date('d-m-Y / H:m:s',$item_order['created'])?></td>
					</tr>

					<tr><th class="title" colspan="2"><h3><?php echo __('Thông tin khách hàng',true)?></h3></th></tr>
					<tr>
						<th><?php echo __('Họ tên',true)?></th>
						<td><?php echo $item_order['name']?></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><?php echo $item_order['email']?></td>
					</tr>
					<tr>
						<th><?php echo __('Điện thoại',true)?></th>
						<td><?php echo $item_order['phone']?></td>
					</tr>
					<tr>
						<th><?php echo __('Địa chỉ',true)?></th>
						<td><?php echo $item_order['address']?></td>
					</tr>
					<tr>
						<th><?php echo __('Tin nhắn',true)?></th>
						<td><?php echo $item_order['message']?></td>
					</tr>

<!--					<tr><th class="title" colspan="2"><h3>--><?php //echo __('Thông tin thanh toán',true)?><!--</h3></th></tr>-->
<!--					<tr>-->
<!--						<th>--><?php //echo __('Hình thức thanh toán',true)?><!--</th>-->
<!--						<td>--><?php //echo $item_order['method_payment']?><!--</td>-->
<!--					</tr>-->
					<?php if($item_order['bank_info']) {?>
					<tr>
						<th><?php echo __('Thông tin tài khoản',true)?></th>
						<td><?php echo html_entity_decode($item_order['bank_info']) ?></td>
					</tr>
					<?php } ?>

<!--					<tr><th class="title" colspan="2"><h3>--><?php //echo __('Thông tin đơn hàng',true)?><!--</h3></th></tr>-->
					<tr>
						<td colspan="2">
							<table class="order_detail">
								<tr>
									<th colspan="2" class=" center"><?php echo __('Sản phẩm',true)?></th>
									<th class="small center"><?php echo __('Giá',true)?></th>
									<th class="small center"><?php echo __('Màu sắc',true)?></th>
									<th class="small center"><?php echo __('Kích thước',true)?></th>
									<th class="small center"><?php echo __('Số lượng',true)?></th>
									<th class="small center"><?php echo __('Tổng tiền',true)?></th>
								</tr>
								<?php
									$sep1 = ',';
									$sep2 = '.';
									$decimal = 0;
									if(!in_array($item_order['unit_payment'], array('đ','d','vnđ','vnd','Đ','D','VNĐ','VND'))){
										$sep1 = '.';
										$sep2 = ',';
										$decimal = 2;
									}

									foreach ($item_order['content'] as $key=>$val){
										$item_product = $val['Product'];
								?>
								<tr>
									<td class="small thumb">
									<?php
										$w = 80;
										$full_size = $oneweb_product['size']['product'];
										$h = intval($w*$full_size[1]/$full_size[0]);
										echo $this->OnewebVn->thumb('products/'.$item_product['image'],array('alt'=>$item_product['name'],'width'=>$w,'height'=>$h));
									?>
									</td>
									<td class="name">
									<?php
										echo $this->Html->tag('p',$item_product['name'],array('class'=>'name'));
										echo $this->Html->tag('p',($item_product['quantity']>0)?__('Còn hàng',true):__('Hết hàng',true),array('class'=>'status'));
										if(!empty($item_product['promotion'])) echo $this->Html->tag('p',__('Khuyến mãi',true).': '.$item_product['promotion'],array('class'=>'promotion'));
									?>
									</td>
									<td class="small">
									<?php
										if(!empty($item_product['price_new'])) {
											$price = $item_product['price_new'];
										}else{
											$price = $item_product['price'];
										}
										echo $this->Html->tag('span',number_format($price/$item_order['rate'],$decimal,$sep1,$sep2).' '.$item_order['unit_payment'],array('class'=>'new font-weight-bold color-red'));
//										echo '</br>';
//										if(!empty($item_product['price_new'])){
//											echo $this->Html->tag('del',number_format($item_product['price']/$item_order['rate'],$decimal,$sep1,$sep2).' '.$item_order['unit_payment'],array('class'=>'font-weight-bold price_old'));
//											if(!empty($item_product['discount'])) echo $this->Html->tag('p class="font-style-italic"',__('Giảm giá',true).': '.$item_product['discount'].'%',array('class'=>'discount'));
//										}
									?>
									</td>
									<td class="center"><?php if(!empty($item_product['color'])) echo $item_product['color']?></td>
									<td class="center"><?php if(!empty($item_product['size'])) echo $item_product['size']?></td>
									<td class="center"><?php echo $item_product['qty']?></td>
									<td class="small price">
									<?php
										echo number_format($price*$item_product['qty']/$item_order['rate'],$decimal,$sep1,$sep2).' '.$item_order['unit_payment'];
									?>
									</td>
								</tr>
								<?php }?>
								<tr class="total_fee">
									<td colspan="6"><?php echo __('Phí vận chuyển',true).': '?></td>
									<td>
										<strong class="color-red">
											<?php
										echo number_format($item_order['surcharge']/$item_order['rate'],$decimal,$sep1,$sep2).' '.$item_order['unit_payment'];
										?>
										</strong>
									</td>
								</tr>
								<tr class="total">
									<th colspan="6"><?php echo __('Tổng',true).': '?></th>
									<td><?php echo number_format($item_order['total']/$item_order['rate'],$decimal,$sep1,$sep2).' '.$item_order['unit_payment'];?></td>
								</tr>
							</table> <!-- end .order_detail -->
						</td>
					</tr>

					<tr>
						<th><?php echo $this->Form->label('order_category_id',__('Nhóm',true))?></th>
						<td><?php echo $this->Form->input('order_category_id',array('type'=>'select','options'=>$a_list_categories_c,'value'=>$item_order['order_category_id'],'class'=>'medium'))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('status',__('Trạng thái',true))?></th>
						<td><?php echo $this->Form->input('status',array('class'=>'medium','value'=>$item_order['status']))?></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('method_payment',__('Thanh toán',true))?></th>
						<td><?php echo $this->Form->input('method_payment',array('class'=>'medium','value'=>$item_order['method_payment']))?></td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->

			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?></li>
				<li><?php echo $this->Form->submit(__('Lưu & Thoát',true),array('name'=>'save_exit','div'=>false))?></li>
				<li><?php echo $this->Html->link(__('Thoát',true),(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit'))?></li>
			</ul> <!-- end .submit -->

		</div> <!-- end .tab_container -->

		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->
