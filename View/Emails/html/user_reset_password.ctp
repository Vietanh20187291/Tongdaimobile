<?php 
	if(!empty($config['user_description_reset_pass'])) echo $config['user_description_reset_pass'];
	$host = 'http://'.$_SERVER['HTTP_HOST'];
	$url = array('controller'=>'users','action'=>'admin_confirmResetPassword','user_id'=>$data['id'],'token'=>$data['token']);
?>
<?php echo $this->Html->link($host.$this->Html->url($url),$url,array('title'=>$host.$this->Html->url($url)))?>