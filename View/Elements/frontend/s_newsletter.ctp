<aside class="box newsletter">
	<span class="title"><?php echo __('Newsletters',true)?></span>
	
	<?php 
		echo $this->Form->create('Newsletter',array('inputDefaults'=>array('label'=>false,'div'=>false),'id'=>'newsletter'));
		echo '<p>'.$this->Form->input('email',array('value'=>'Email','placeholder'=>__('Your email',true),'class'=>'larger')).'</p>';
		echo $this->Html->tag('p','&nbsp;',array('id'=>'newsletter_result'));
		echo '<p>'.$this->Form->button(__('Đăng ký',true),array('onclick'=>'newsletter();return false;','class'=>'submit')).'</p>';
		echo $this->Form->end();
	?>
</aside> <!--  end .box -->


