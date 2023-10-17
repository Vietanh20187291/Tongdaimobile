<?php
	$item_contact = $a_contact_c['ContactForm'];
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
						<th><?php echo __('Họ tên',true)?></th>
						<td><?php echo $item_contact['name']?></td>
					</tr>

					<tr>
						<th><?php echo __('Điện thoại',true)?></th>
						<td><?php echo $item_contact['phone']?></td>
					</tr>

					<?php if(!empty($item_contact['email'])) { ?>
					<tr>
						<th>Email</th>
						<td><?php echo $item_contact['email']?></td>
					</tr>
					<?php } ?>
					<?php if(!empty($item_contact['address'])) { ?>
					<tr>
						<th><?php echo __('Địa chỉ',true)?></th>
						<td><?php echo $item_contact['address']?></td>
					</tr>
					<?php } ?>
					<?php if(!empty($item_contact['date_regis'])) { ?>
					<tr>
						<th><?php echo __('Ngày đăng ký',true)?></th>
						<td><?php echo $item_contact['date_regis']?></td>
					</tr>
					<?php } ?>
					<?php if(!empty($item_contact['friend'])) { ?>
					<tr>
						<th><?php echo __('Người đi cùng bạn',true)?></th>
						<td><?php echo $item_contact['friend']?></td>
					</tr>
					<?php } ?>
					<?php if(!empty($item_contact['product_name'])) { ?>
					<tr>
						<th><?php echo __('Sản phẩm',true)?></th>
						<td><?php echo $item_contact['product_name']?></td>
					</tr>
					<?php } ?>
					<tr>
						<th><?php echo __('Ngày tạo',true)?></th>
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