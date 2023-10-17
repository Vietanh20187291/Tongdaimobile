<div id="column_right">
	<div id="action_top">
		<h1><?php echo __('THỐNG KÊ') ?></h1>
	</div>
	<div id="content">
		<table>
			<tr>
				<td>
					<h2>
						<?php echo $this->Html->link(__('Quản lý Credit đã nạp'),array('controller'=>'statistics','action'=>'credit_buy')) ?>
					</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>
						<?php echo $this->Html->link(__('Quản lý Credit đã tiêu'),array('controller'=>'statistics','action'=>'credit_spent')) ?>
					</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>
						<?php echo $this->Html->link(__('Quản lý Credit khuyến mãi'),array('controller'=>'statistics','action'=>'credit_promotion')) ?>
					</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>
						<?php echo $this->Html->link(__('Số lượng thành viên đã đăng ký'),array('controller'=>'statistics','action'=>'member_registration')) ?>
					</h2>
					</td>
			</tr>
			<tr>
				<td>
					<h2>
						<?php echo $this->Html->link(__('Số lượng tin dự án/tuyển dụng đã đăng'),array('controller'=>'statistics','action'=>'posted')) ?>
					</h2>
				</td>
			</tr>
		</table>
	</div>
</div>
