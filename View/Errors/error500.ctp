<?php $this->layout = 'error';
	$this->set('title_for_layout',__('Lỗi 500',true));
?>
<table border="0" cellpadding="0" cellspacing="0" align="center" >
	<tr>
		<td><h1><?php echo $name?></h1></td>
	</tr>
	<tr>
		<td>
		<fieldset>
			<legend><h3><?php echo __('Lỗi truy cập',true)?></h3></legend>
			<p style="margin: 3px 0"><strong><?php echo __('Chúng tôi thành thật xin lỗi! Trang quý khách truy cập (URL) có lỗi.',true)?></strong></p>
			<p style="margin: 3px 0"><?php echo __('Quý khách vui lòng',true)?> <a href="javascript:history.back()">&laquo; <?php echo __('quay lại',true)?></a> <?php echo __('hoặc truy cập vào',true)?> <?php echo $this->Html->link($_SERVER['HTTP_HOST'],'/',array('title'=>$_SERVER['HTTP_HOST']))?>, <?php echo __('tìm kiếm nội dung cần thiết trên trang. Xin cám ơn!',true)?></p>
		</fieldset>
		</td>
	</tr>
</table>