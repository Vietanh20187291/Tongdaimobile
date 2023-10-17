<div class="box_content">
	<div class="col-xs-12 text-center">
		<div class="row">
			<div class="form col-xs-12 col-md-4 col-sm-4 col-sm-offset-4 text-center">
				<h4>
					<b class="text-uppercase"><?php echo __('Nhận quà tặng',true)?></b>
				</h4>
				<?php
					echo $this->Session->flash();
				?>
				 <?php echo $this->Form->create('ContactForm',array('inputDefaults'=>array('div'=>false,'label'=>false))) ?>
				<div class="form-group">
				<?php
				echo $this->Form->label('name',__('Tên của bạn',true).' <span class="im">(*)</span>');
					echo $this->Form->input('name',array('class'=>'form-control', 'autocomplete'=>'off', 'required'=>true));
					?>
				</div>
				<div class="form-group">
					<?php
						echo $this->Form->label('phone',__('Số điện thoại',true).' <span class="im">(*)</span>');
						echo $this->Form->input('phone',array('class'=>'form-control', 'autocomplete'=>'off', 'required'=>true))
					?>
				</div>
				<div class="form-group">
					<?php
						echo $this->Form->label('email',__('Email',true).' <span class="im">(*)</span>');
						echo $this->Form->input('email',array('class'=>'form-control', 'autocomplete'=>'off', 'required'=>true))
					?>
				</div>
				<div class="form-group">
					<?php
						echo $this->Form->label('product_name',__('Sản phẩm bạn quan tâm',true));
						echo $this->Form->input('product_name',array('type'=>'text', 'autocomplete'=>'off','class'=>'form-control'));
						echo $this->Form->input('product_id',array('type'=>'hidden','class'=>'form-control'));
						echo $this->Form->input('type',array('type'=>'hidden','value'=>'gift','class'=>'form-control'))
					?>
				</div>
				<div class="form-group">
				</div>
				<div class="form-group submit text-center m-t-15">
					<?php echo $this->Form->submit(__('Gửi ngay',true),array('class'=>'btn btn-primary','div'=>false))?>
				</div>
				<?php echo $this->Form->end();?>
			</div>
		</div>
	</div>
</div>
<!-- end contacts/index.ctp -->