<table>
	<tr class="total_top">
		<th colspan="2">
		<?php 
			$str = strlen($a_counters_c['total']);
			$n = 8;
			$str_total = '';
			for($i=0;$i<$n-$str;$i++){
				$str_total.='<span>0</span>';
			}
			for($i=0;$i<$str;$i++){
				$str_total.='<span>'.$a_counters_c['total'][$i].'</span>';
			}
			echo $this->Html->tag('p',$str_total);
		?>
		</th>
	</tr>
	<?php if(!empty($oneweb_counter['online'])){?>
	<tr class="online">
		<th><?php echo __('Online',true)?></th>
		<td><?php echo number_format($a_counters_c['online'])?></td>
	</tr>
	<?php }if(!empty($oneweb_counter['yesterday'])){?>
	<tr class="yesterday">
		<th><?php echo __('Hôm qua',true)?></th>
		<td><?php echo number_format($a_counters_c['yesterday'])?></td>
	</tr>
	<?php }if(!empty($oneweb_counter['today'])){?>
	<tr class="today">
		<th><?php echo __('Hôm nay',true)?></th>
		<td><?php echo number_format($a_counters_c['today'])?></td>
	</tr>
	<?php }if(!empty($oneweb_counter['week'])){?>
	<tr class="week">
		<th><?php echo __('Trong tuần',true)?></th>
		<td><?php echo number_format($a_counters_c['week'])?></td>
	</tr>
	<?php }if(!empty($oneweb_counter['month'])){?>
	<tr class="month">
		<th><?php echo __('Trong tháng',true)?></th>
		<td><?php echo number_format($a_counters_c['month'])?></td>
	</tr>
	<?php }if(!empty($oneweb_counter['year'])){?>
	<tr class="year">
		<th><?php echo __('Trong năm',true)?></th>
		<td><?php echo number_format($a_counters_c['year'])?></td>
	</tr>
	<?php }if(!empty($oneweb_counter['total'])){?>
	<tr class="total">
		<th><?php echo __('Tổng',true)?></th>
		<td><?php echo number_format($a_counters_c['total'])?></td>
	</tr>
	<?php }?>
</table>