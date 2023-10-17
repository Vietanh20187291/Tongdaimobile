<?php if(!empty($a_notice_c)){
	$a_notice = $a_notice_c['MemberMessage'];
?>

<article class="box_content history_payment">
        <div class="title">
			<div class="title_right">
				<div class="title_center">
				<header><h1><span><?php echo __('Chi tiết thông báo', true);?></span></h1></header>
				</div> <!--  end .title_center -->
			</div> <!--  end .title_right -->
		</div>
		<div class="des">
			<h3><?php echo $a_notice['title'];?></h3>
			<div>
				<?php echo $a_notice['message'];?>
			</div>
		
			
		</div><!-- End .des -->
	
</article> <!--  end .box_content -->
<?php } ?>