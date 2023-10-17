<?php
	$item_contact = $a_contact_c['Contact'];
	$item_cate = $a_contact_c['ContactCategory'];
?>

<div id="column_right">
	<!-- tab -->
	<div id="action_top">
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo $item_contact['name']?></a></li>
   		</ul> <!-- end .tabs -->

    	<ul class="action_top_2">
    		<li><?php echo $this->Html->link('&nbsp;',(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('title'=>__('Thoát',true),'class'=>'exit','escape'=>false))?></li>
   		</ul> <!-- end .action_top_2 -->
	</div> <!--  end #action_top -->

	<div id="content">
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="add column1">
					<tr>
						<th><?php echo __('Tiêu đề',true)?></th>
						<td><?php echo $item_contact['subject']?></td>
					</tr>
					<tr>
						<th><?php echo __('Họ tên',true)?></th>
						<td><?php echo $item_contact['name']?></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><?php echo $item_contact['email']?></td>
					</tr>
					<tr>
						<th><?php echo __('Điện thoại',true).'/'.__('Fax',true)?></th>
						<td><b><?php echo __('ĐT',true)?>:</b> <?php echo $item_contact['phone']?> - <b><?php echo __('Fax',true)?> </b><?php echo $item_contact['fax']?></td>
					</tr>
					<tr>
						<th><?php echo __('Địa chỉ',true)?></th>
						<td><?php echo $item_contact['address']?></td>
					</tr>
					<tr>
						<th><?php echo __('Tin nhắn',true)?></th>
						<td><?php echo $item_contact['message']?></td>
					</tr>
					<tr>
						<th><?php echo __('Ngày liên hệ',true)?></th>
						<td><?php echo date('d-M-Y / H:m:s',$item_contact['created'])?></td>
					</tr>
					<tr>
						<th>IP/Proxy</th>
						<td>
							<?php
								echo $item_contact['ip'];
								if(!empty($item_contact['proxy'])) echo '/'.$item_contact['proxy'];
								echo '&nbsp;&nbsp;&nbsp;'.$this->Html->link(__('Kiểm tra IP',true),'http://whois.domaintools.com/'.$item_contact['ip'],array('title'=>__('Kiểm tra IP',true),'class'=>'tooltip','target'=>'_blank'))
							?>
						</td>
					</tr>
				</table> <!-- end .add -->
			</div> <!-- end #tab1 -->

			<ul class="submit">
				<li><?php echo $this->Html->link(__('Thoát',true),(!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index'),array('class'=>'exit'))?></li>
			</ul> <!-- end .submit -->

		</div> <!-- end .tab_container -->

		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->