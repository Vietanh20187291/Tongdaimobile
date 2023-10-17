<?php echo $this->Html->docType('html5');?>
<html lang="<?php echo $lang?>">
<head>
	<?php 
		$controller = $this->params['controller'];
		$action = $this->params['action'];
		echo $this->Html->charset();
		echo $this->Html->meta('favicon.ico',$this->Html->url('/webroot/favicon.ico'),array('type'=>'icon'));
		echo '<title>Captcha</title>';
		
		$robots='noindex,nofollow';
		echo "<meta name='robots' content='$robots'>";
	?>
</head>
<body>
	<div class="wrapper">
		<?php 
			echo $this->Recaptcha->display(array('recaptchaOptions'=>array(
																	 		'theme'=>'white',			//red, white, blackglass, clean
																	 		'lang'=>"$lang"
																	 	)));
		?>
	</div>
</body>
</html>