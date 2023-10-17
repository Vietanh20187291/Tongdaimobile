<article class="box_content history_payment">
        <div class="title">
			<div class="title_right">
				<div class="title_center">
				<header><h1><span><?php echo __('Thông tin đơn hàng', true);?></span></h1></header>
				</div> <!--  end .title_center -->
			</div> <!--  end .title_right -->
		</div>
		<div class="des">
     			<table class="list">
				<tr>
				<th class="small"><?php echo __('STT', true);?></th>
				<th class="small"><?php echo __('Mã đơn hàng', true);?></th>
				<th class="small"><?php echo __('Ngày tạo', true);?></th>
				<th><?php echo __('Số tiền', true); ?></th>
			</tr>
			<?php 
			if(!empty($a_order_c)){
			$i=1;
			if(!empty($a_order_c)){
				foreach($a_order_c as $val){
					$item_order = $val['Order'];
					$sep1 = ',';
					$sep2 = '.';
					$decimal = 0;
					if(!in_array($item_order['unit_payment'], array('đ','d','vnđ','vnd','Đ','D','VNĐ','VND'))){
						$sep1 = '.';
						$sep2 = ',';
						$decimal = 2;
					}
			?>
			<tr>
				<td align="center"><?php echo $i;?></td>
				<td align="center" class="bold_blue">
					<?php echo $this->Html->link($item_order['transaction_code'], array('controller'=>'members','action'=>'detailHistoryPayment', 'lang'=>$lang,$item_order['id']), array('title'=>__('Chi tiết giao dịch', true)));?>
				</td>
				<td>
				<?php 
					echo date('d/m/Y',$item_order['created']);
					echo '<span class="time">'.date('H:i:s',$item_order['created']).'</span>';
				?>
				</td>
				<td class="small">
					<?php echo number_format($item_order['total']/$item_order['rate'],$decimal,$sep1,$sep2).' '.$item_order['unit_payment'];?>
				</td>
			</tr>
			<?php }  $i++; }
				}else{
			?>
			<tr>
				<td align="center" class="notice" colspan="4"><?php echo __('Bạn chưa có đơn hàng nào', true);?></td>
				
			</tr>
			<?php }?>
				</table>
		</div><!-- End .des -->
	
</article> <!--  end .box_content -->