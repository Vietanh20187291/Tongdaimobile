<article class="box_content change_account">
        <div class="title">
			<div class="title_right">
				<div class="title_center">
				<header><h1><span><?php echo __('Thông tin thành viên', true);?></span></h1></header>
				</div> <!--  end .title_center -->
			</div> <!--  end .title_right -->
		</div>
		<div class="des">
	     	<?php echo $this->Form->create('Member',array('url'=>array('controller'=>'members','action'=>'editAccount','lang'=>$lang,'ext'=>'html'),'inputDefaults'=>array('label'=>false,'div'=>false),'id'=>'form'));?>
	     		<div>
	     			<?php echo $this->Form->label('email',__('Email', true));?>
	     			<span class="info_mail">huuquynhbn87@gmail.com</span>
	     		</div>
	     		<div>
	     			<?php 
	     				echo $this->Form->label('gender', __('Giới tính', true).'<span>*</span>');
	     				echo $this->Form->input('gender', array('type'=>'select', 'options'=>array('Nam', 'Nữ'), 'div'=>false, 'label'=>false, 'class'=>'small'));
	     			?>
	     		</div>
	     		<div>
	     			<?php 
	     				echo $this->Form->label('name', __('Họ tên', true).'<span>*</span>');
	     				echo $this->Form->input('name', array('type'=>'text', 'div'=>false, 'label'=>false, 'class'=>'larger'))
	     			?>
	     		</div>
	     		<div>
	     			<?php 
	     				echo $this->Form->label('birthday', __('Ngày sinh', true));
	     				echo $this->Form->input('birthday', array('type'=>'date', 'minYear'=>date('Y')-80, 'maxYear'=>date('Y')-5,'div'=>false,'label'=>false));
	     			?>
	     		</div>
	     		<div class="submit">
	     			<p><span>*</span> Thông tin bắt buộc</p>
	     			<?php echo $this->Form->submit(__('Lưu thay đổi'), array('div'=>false))?>
	     		</div>
	     		<?php echo $this->Form->end();?>
		</div><!-- End .des -->
	
</article> <!--  end .box_content -->