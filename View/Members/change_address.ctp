<article class="box_content change_address">
        <div class="title">
			<div class="title_right">
				<div class="title_center">
				<header><h1><span><?php echo __('Thông tin địa chỉ', true);?></span></h1></header>
				</div> <!--  end .title_center -->
			</div> <!--  end .title_right -->
		</div>
		<div class="des">
	     	<?php echo $this->Form->create('Member',array('url'=>array('controller'=>'members','action'=>'changeAddress','lang'=>$lang),'inputDefaults'=>array('label'=>false,'div'=>false),'id'=>'form'));?>
	     		<div>
	     			<?php 
	     				echo $this->Form->label('name', __('Họ tên', true).'<span>*</span>');
	     				echo $this->Form->input('name', array('type'=>'text', 'div'=>false, 'label'=>false, 'class'=>'larger1'))
	     			?>
	     		</div>
	     		<div>
	     			<?php 
	     				echo $this->Form->label('phone', __('Số điện thoại', true).'<span>*</span>');
	     				echo $this->Form->input('phone', array('type'=>'number', 'div'=>false, 'label'=>false, 'class'=>'larger1'))
	     			?>
	     		</div>
	     		<div>
	     			<?php 
	     				echo $this->Form->label('address', __('Địa chỉ', true).'<span>*</span>');
	     				echo $this->Form->input('address', array('type'=>'text', 'div'=>false, 'label'=>false, 'class'=>'larger1'))
	     			?>
	     		</div>
	     		<div>
	     			<?php 
	     				echo $this->Form->label('country_id', __('Tỉnh/ Thành', true).'<span>*</span>');
	     				echo $this->Form->input('country_id', array('type'=>'text', 'div'=>false, 'label'=>false, 'class'=>'larger1'))
	     			?>
	     		</div>
	     		<div class="submit">
	     			<p><span>*</span> Thông tin bắt buộc</p>
	     			<?php echo $this->Form->submit(__('Lưu thay đổi'), array('div'=>false))?>
	     		</div>
	     		<?php echo $this->Form->end();?>
		</div><!-- End .des -->
	
</article> <!--  end .box_content -->