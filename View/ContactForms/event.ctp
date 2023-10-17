<?php
  echo $this->Html->script(array('date_picker/jquery.ui.core','date_picker/jquery.ui.widget','date_picker/jquery.ui.datepicker'));
  echo $this->Html->css(array($oneweb_web['layout'].'/date_picker/jquery.ui.datepicker',$oneweb_web['layout'].'/date_picker/jquery.ui.datepicker',$oneweb_web['layout'].'/date_picker/jquery.ui.theme',$oneweb_web['layout'].'/date_picker/jquery.ui.core'));
  ?>
<div class="box_content">
	<div class="col-xs-12 text-center">
		<div class="row">
			<div class="form col-xs-12 col-md-4 col-sm-4 col-sm-offset-4 text-center">
				<h4>
					<b class="text-uppercase"><?php echo __('Tham gia sự kiện',true)?></b>
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
						echo $this->Form->label('date_regis',__('Ngày đăng ký',true).' <span class="im">(*)</span>');
						echo $this->Form->input('date_regis',array('type'=>'text','class'=>'form-control', 'required'=>true))
					?>
				</div>
				<div class="form-group">
					<?php
						echo $this->Form->label('friend',__('Bạn đi cùng ai',true));
						echo $this->Form->input('friend',array('type'=>'text', 'autocomplete'=>'off', 'class'=>'form-control'));
						echo $this->Form->input('type',array('type'=>'hidden','value'=>'event','class'=>'form-control'))
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
<script>
	$(function(){
		$("#ContactFormDateRegis").datepicker({
                             minDate: new Date(),
                             //maxDate: "+90d",
                             changeMonth: true,
                             changeYear: true,
                             numberOfMonths: 1,
                             dateFormat: 'dd/mm/yy'
                         });
	});
</script>