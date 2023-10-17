<div id="popup">
	<div id="popup_cart" class="window">
		<a href="javascript:;" class="close">&nbsp;</a>	
		<div class="content_popup">
			<span class="title">Giỏ hàng</span>
			<div class="list_product">
				<table>
					<tr>
						<th><?php echo __('Sản phẩm',true)?></th>
						<th><?php echo __('Giá',true)?></th>
						<th><?php echo __('Số lượng',true)?></th>
						<th><?php echo __('Giá',true)?></th>
					</tr>
					<tr>
						<td>Canon PowerShot A2400 IS – 16MP / Bạc</td>
						<td>2500000đ</td>
						<td>1</td>
						<td>2500000đ</td>
					</tr>
					<tr>
						<td>Canon PowerShot A2400 IS – 16MP / Bạc</td>
						<td>2500000đ</td>
						<td>1</td>
						<td>2500000đ</td>
					</tr>
					<tr>
						<td>Canon PowerShot A2400 IS – 16MP / Bạc</td>
						<td>2500000đ</td>
						<td>1</td>
						<td>2500000đ</td>
					</tr>
					<tr>
						<td>Canon PowerShot A2400 IS – 16MP / Bạc</td>
						<td>2500000đ</td>
						<td>1</td>
						<td>2500000đ</td>
					</tr>
				</table>
			</div> <!-- end .list_product -->
			
			<div class="payment">
				<p class="total"><span><?php echo __('Tổng',true)?></span>15.000.000đ</p>
				<p class="payment2"><?php echo $this->Html->link(__('Tiến hành thanh toán',true),array(),array('title'=>__('Tiến hành thanh toán',true)))?></p>
				<p class="close"><?php echo $this->Html->link(__('Tiếp tục mua hàng',true),array(),array('title'=>__('Tiếp tục mua hàng',true),'class'=>'close'))?></p>
			</div>
			
		</div>	<!-- end .content_popup -->	
	</div> <!-- end .window -->
	
	<!-- vùng div id mask . lúc đầu nó sẽ ẩn -->
	<div id="mask"></div>
</div> <!--  end #popup -->