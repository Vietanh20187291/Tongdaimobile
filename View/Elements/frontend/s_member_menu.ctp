<?php 
	$controller = $this->request->params['controller'];
	$action = $this->params['action'];
	$flag = 0;
	if(!empty($oneweb_member)) $flag = $oneweb_member['enable'];
	if($controller == 'members' && $flag){
?>
<aside class="box category">
	<span class="title"><?php echo __('Thông tin cá nhân',true)?></span>
	<div class="nav-v" id="tree">
	
		<ul>
			<li><?php echo $this->Html->link(__('Thông tin tài khoản',true),array('controller'=>'members','action'=>'index','lang'=>$lang,'ext'=>'html'),array('title'=>__('Thông tin tài khoản',true)))?></li>
			<li><?php echo $this->Html->link(__('Thông tin đơn hàng',true),array('controller'=>'members','action'=>'historyPayment','lang'=>$lang,'ext'=>'html'),array('title'=>__('Thông tin đơn hàng',true)))?></li>
			<!-- 
			<li><?php echo $this->Html->link(__('Phiếu giảm giá',true),array('controller'=>'members','action'=>'profile','lang'=>$lang,'ext'=>'html'))?></li>
			 -->
			<li><?php echo $this->Html->link(__('Thông báo',true),array('controller'=>'members','action'=>'managementNotice','lang'=>$lang),array('title'=>__('Thông báo',true)))?></li>
		</ul>
		<div class="clear"></div>				
	</div> <!--  end #tree -->
</aside> <!--  end .box -->
<?php } ?>