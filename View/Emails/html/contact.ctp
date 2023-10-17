<div style="width: 600px; font-size: 1em; margin: 0 auto;">
	<?php if(!$admin){?>
	<div style="text-align: justify">
		<?php if ( ! empty($config['contact_description'])) echo $config['contact_description']?>
	</div>
	<?php }?>
	<table style="border: 1px solid #ff000; color: #333; width: 100%; border-collapse: collapse;">
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px; width: 120px"><?php echo __('Tiêu đề',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 2px 0 2px 5px;"><?php echo $data['subject']?></td>
		</tr>
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px;"><?php echo __('Họ tên',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 2px 0 2px 5px;"><?php echo $data['name']?></td>
		</tr>
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px;"><?php echo __('Email',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 2px 0 2px 5px;"><?php echo $data['email']?></td>
		</tr>
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px;"><?php echo __('Điện thoại',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 2px 0 2px 5px;"><?php echo $data['phone']?></td>
		</tr>
		<tr>
			<th style="border: 1px solid #ddd; color: #333; text-align: left; padding: 2px 0 2px 5px;"><?php echo __('Nội dung',true)?></th>
			<td style="border: 1px solid #ddd; color: #333; padding: 2px 0 2px 5px;"><?php echo $data['message']?></td>
		</tr>
	</table>
</div>
