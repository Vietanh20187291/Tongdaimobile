<!-- Plugin AMP -->
<!-- Author: Ngô Văn Nam -->
<!-- Thêm vào Config/bootstrap.php -->
CakePlugin::load('Amp');
<!-- Thêm vào Config/routes.php-->
<!-- danh sách phân trang -->
Router::connect('/amp'.$prefix.$slug2.'/page-:page',array('plugin'=>'Amp','controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2,'slug0'=>$val2),array('pass'=>array('slug'.($i-1)),'page' => '[0-9]+'));
Router::connect('/amp'.$prefix.$slug.'/page-:page',array('plugin'=>'Amp','controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2),array('pass'=>array('slug'.($i-1)),'page' => '[0-9]+'));
<!-- danh sách bài viết -->
Router::connect('/amp'.$prefix.$slug2,array('plugin'=>'Amp', 'controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2,'slug0'=>$val2),array('pass'=>array('slug'.($i-1))));
Router::connect('/amp'.$prefix.$slug,array('plugin'=>'Amp', 'controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2),array('pass'=>array('slug'.($i-1))));

<!-- Thêm vào layout/index.ctp -->
<?php if($controller == 'posts' && !empty($this->params['ext'])) {?>
<link rel='amphtml' href="<?php echo $http_host.'amp'.$this->Html->url($a_canonical) ?>"/>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="Hinlet">
<?php } ?>