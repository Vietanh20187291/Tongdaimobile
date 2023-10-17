
<!-- validate. --> 
<?php echo $this->Html->script(array('jquery.validate'));?>
<script type="text/javascript"> 
  $(document).ready(function(){
    $("#form").validate({
    	rules: {
        	"data[Member][password]": {
            	required: true,
            	minlength:6            	
            	},
            "data[Member][re-password]": {
               	required: true,
               	equalTo: "#password"
               	}
    	},
    	<?php if($lang=='vi'){ ?>
    	messages: {
        	"data[Member][email]": {
        		required: '<?php echo __('Bạn chưa nhập Email',true)?>',
        		email: '<?php echo __('Sai định dạng Email',true)?>'
        		},
        	"data[Member][password]": {
            	required: '<?php echo __('Bạn chưa nhập mật khẩu',true)?>',
            	minlength: '<?php echo __('Mật khẩu ít nhất 6 ký tự', true)?>'
            	},
            "data[Member][re-password]": {
               	required: '<?php echo __('Bạn chưa nhập mật khẩu xác nhận',true)?>',
               	equalTo: '<?php echo __('Mật khẩu xác nhận không đúng',true)?>'
               	}
        }
        <?php }?>
    });
});
</script>
<div class="box_content registration">
	<div class="title">
		<div class="title_right">
			<div class="title_center">
				<h1><span><?php echo __('Tạo tài khoản thành viên mới',true)?></span></h1>
			</div> <!--  end .title_center -->
		</div> <!--  end .title_right -->
	</div> <!--  end .title -->
	
	<div class="des">
		
		<div class="form_register">
			 <?php echo $this->Form->create('Member',array('url'=>array('controller'=>'members','action'=>'registration','lang'=>$lang),'inputDefaults'=>array('label'=>false,'div'=>false),'id'=>'form')); ?>
			<div class="row">
				<?php 
					echo $this->Form->label('name',__('Họ tên',true).'<span class="im">*</span>');
					echo $this->Form->input('name',array('type'=>'text','class'=>'larger'));
				?>
			</div>
			<div class="row">
				<?php 
					echo $this->Form->label('gender',__('Giới tính',true).'<span class="im">*</span>');
					 echo $this->Form->input('gender',array('type'=>'select','options'=>array('nam'=>'Nam','nu'=>'Nữ'),'class'=>'small'));
				?>
			</div>
			<div class="row">
				<?php 
					echo $this->Form->label('gender',__('Ngày sinh',true));
					echo $this->Form->input('birthday',array('type'=>'date','minYear'=>date('Y')-80,'maxYear'=>date('Y')-5));
				?>
			</div>
			
			<div class="row">
				<?php 
					echo $this->Form->label('email',__('Email',true).'<span class="im">*</span>');
					echo $this->Form->input('email',array('type'=>'email','class'=>'larger'))
				?>
			</div>
			<div class="row">
				<?php 
					echo $this->Form->label('password',__('Mật khẩu',true).'<span class="im">*</span>');
					echo $this->Form->input('password',array('type'=>'password','class'=>'larger','id'=>'password'))
				?>
			</div>
			<div class="row">
				<?php 
					echo $this->Form->label('re-password',__('Nhập lại mật khẩu',true).'<span class="im">*</span>');
					echo $this->Form->input('re-password',array('type'=>'password','class'=>'larger','id'=>'re_password','required'=>'required'))
				?>
			</div>
			
			<div class="row submit">
				<?php echo $this->Form->submit(__('Gửi',true),array('div'=>false))?>
			</div>
			
			<?php echo $this->Form->end();?>
		</div> <!-- end .form -->
		
		<div class="clear"></div>
	</div> <!--  end .des -->
			
	<div class="top"></div>
	<div class="bottom"></div>
</div> <!--  end .box_content -->