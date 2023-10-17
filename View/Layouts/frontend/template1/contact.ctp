<?php echo $this->Html->docType('html5');?>
<html lang="<?php echo $lang?>">
<head>
	<meta name="viewport" content="width=device-width, height=device-height">
	<meta http-equiv="X-FRAME-OPTIONS" content="DENY">
	<?php
		$controller = $this->params['controller'];
		$action = $this->params['action'];
		echo $this->Html->charset();
		echo $this->Html->meta('favicon.ico',$this->Html->url('/webroot/favicon.ico'),array('type'=>'icon'));
		echo '<title>'.((!empty($this->params['page']) && $this->params['page']>1)?$this->params['page'].' - ':'').$title_for_layout.'</title>';
		if(!empty($meta_keyword_for_layout)) echo $this->Html->meta('keywords',$meta_keyword_for_layout);
		if(!empty($meta_description_for_layout)) echo $this->Html->meta('description',$meta_description_for_layout);

		//Robots
		$robots = !empty($meta_robots_for_layout)?$meta_robots_for_layout:'index,follow';
		if(!empty($this->params['sort'])) $robots='noindex,nofollow';

// 		$robots='noindex,nofollow';	//***********************************************************************//
		if($robots!='index,follow') echo "<meta name='robots' content='$robots'>";
		//End Robots

		echo "<meta name='author' content='{$_SERVER['HTTP_HOST']}'>";
		if($controller == 'posts') {
			echo "<link rel='amphtml' href='".$http_host.'amp'.$this->Html->url($a_canonical)."'/>";
	    echo '<meta name="apple-mobile-web-app-capable" content="yes">';
	    echo '<meta name="apple-mobile-web-app-title" content="Hinlet">';
	  }
		echo $this->Html->css(array(
			'bootstrap/bootstrap.min',
			'bootstrap/bootstrap-theme.min',
			));

		echo $this->Html->script(array(
			'jquery-3.2.1.min','bootstrap.min'
			));
		?>
		<style>
			.im {
				color: red;
			}
			.box_content .form {
		    border: 2px solid #ccc;
		    border-radius: 10px;
		    padding-top: 10px;
			}

			.btn-primary {
				background: #990e15!important;
				border-color: #990e15!important;
		    text-shadow: none;
		    color: white;
			}

			.btn-primary:focus, .btn-primary:hover {

				background: #990e15!important;
				border-color: #990e15!important;
			}
		</style>
</head>
<body>
	<div id="content">
		<div class="container">
			<div class="row">
				<?php
					echo $content_for_layout;
				?>
			</div>
		</div> <!--  end .container -->
	</div>
</body>
</html>