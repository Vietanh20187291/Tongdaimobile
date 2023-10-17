<?php
	$controller = $this->params['controller'];
	$action = $this->params['action'];
?>
<!-- Begin menu -->
<nav class="navbar navbar-transform">
	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse in" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav">
			<li class=""><?php echo $this->Html->link($this->Html->tag('span',__('Trang chủ',true)),array('controller'=>'pages','action'=>'home','lang'=>$lang),array('title'=>__('Trang chủ',true),'escape'=>false))?></li>
			<?php if(!empty($a_information_nav)) echo $this->OnewebVn->linkInformation($a_information_nav,array(1),$sub=true)?>
			<?php if(!empty($a_information_nav)) echo $this->OnewebVn->linkInformation($a_information_nav,array(8),$sub=true)?>
		</ul>
	</div>
</nav>