<?php echo $this->Html->docType('html5');?>
<html>
<head>
	<?php 
		echo $this->Html->charset();
		echo $this->Html->meta('favicon.ico',$this->Html->url('/webroot/favicon.ico'),array('type'=>'icon'));
		echo $this->Html->tag('title','Công ty thiết kế web URL | Đăng nhập');
		echo "<meta name='robots' content='noindex,nofollow'>";
		echo "<meta name='author' content='Công ty thiết kế web URL'>";
		echo $this->Html->css('admin/login.css');
		echo $this->Html->script(array('admin/jquery','admin/hover'));
	?>

	<script type="text/javascript">
		$(document).ready(function(){
			size = windowSize();
			$("#login").css("margin-top",(size[1]/2 - 150));
		})
		
		//Lay kich thuoc phan vung lam viec cua trinh duyet
		function windowSize(){
			var width = 0, height = 0;
			if( typeof( window.innerWidth ) == 'number' ) {
			  //Non-IE
			  width = window.innerWidth;
			  height = window.innerHeight;
			} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
			  //IE 6+ in 'standards compliant mode'
			  width = document.documentElement.clientWidth;
			  height = document.documentElement.clientHeight;
			} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
			  //IE 4 compatible
			  width = document.body.clientWidth;
			  height = document.body.clientHeight;
			}
			return [width,height];
		}

		//Thiết lập thời gian ẩn thông báo
		var t=setTimeout('hideMessage()',5000);
		function hideMessage(){
			$("#authMessage").fadeOut('medium');
		}
	</script>
</head>
<body>
	<?php 
		echo $content_for_layout;
		echo $this->element('sql_dump');
	?>
</body>
</html>