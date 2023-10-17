<?php 
	if(!empty($config['member_description_reset_pass'])) echo $config['member_description_reset_pass'];
	$host = 'http://'.$_SERVER['HTTP_HOST'];
	$url = array('controller'=>'members','action'=>'confirmResetPassword','lang'=>$lang,'member_id'=>$data['id'],'token'=>$data['token']);
?>
<?php echo $this->Html->link($host.$this->Html->url($url),$url,array('title'=>$host.$this->Html->url($url)))?>