<?php 
	if($this->Session->check('auth_member')){
		$a_members = $this->Session->read('auth_member');
?>
<article class="box_content history_payment management_notice">
        <div class="title">
			<div class="title_right">
				<div class="title_center">
				<header><h1><span><?php echo __('Thông báo', true);?></span></h1></header>
				</div> <!--  end .title_center -->
			</div> <!--  end .title_right -->
		</div>
		<div class="des">
     			<table class="list">
				<tr>
					<th><?php echo __('Tiêu đề', true);?></th>
				<th class="small"><?php echo __('Ngày tạo', true);?></th>
			</tr>
			<?php 
				if(!empty($a_message_c)){
					
					foreach($a_message_c as $val){
						$class_bold = '';
						$item_notice = $val['MemberMessage'];
						if(!empty($item_notice)){
							$a_bool = strstr($item_notice['member_message_read'],$a_members['id'].'-');
							if(!$a_bool){
								$class_bold = 'No_Read';
							}
						}
			?>
			<tr>
				<td align="center" class="<?php echo $class_bold; ?>">
					<?php echo $this->Html->link($item_notice['title'], array('controller'=>'members','action'=>'detailNotice',$item_notice['id'], 'lang'=>$lang,'ext'=>'html'), array('title'=>__('Chi tiết giao dịch', true)));?>
				</td>
				<td class="small">
				<?php 
					echo date('d/m/Y',$item_notice['created']);
					echo '<span class="time">'.date('H:i:s',$item_notice['created']).'</span>';
				?>
				</td>
			</tr>
			<?php } }?>
			
				</table>
		</div><!-- End .des -->
	
</article> <!--  end .box_content -->
<?php } ?>