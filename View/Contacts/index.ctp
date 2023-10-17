<!-- start contacts/index.ctp -->
<div class="box_content row">
	<div class="col-xs-12">
		<header class="title">
			<h1>
				<?php echo __('Liên hệ',true)?>
			</h1>
		</header>
		<div class="row">
			<div class="info col-xs-12 col-md-6 col-sm-6">
				<?php echo $a_configs_c['description'];?>

				<?php if(!empty($oneweb_map['contact']) && !empty($a_configs_c['map_latitude']) && !empty($a_configs_c['map_key'])){?>
				<div class="map">
					<script src="http://maps.googleapis.com/maps/api/js?key=<?php echo $a_configs_c['map_key']?>&sensor=false"></script>

					<script>
						var myCenter=new google.maps.LatLng(<?php echo $a_configs_c['map_latitude'] ?>);

						function initialize()
						{
						var mapProp = {
						  center:myCenter,
						  zoom:<?php echo $a_configs_c['map_zoom']?>,
						  mapTypeId:google.maps.MapTypeId.ROADMAP
						  };

						var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

						var marker=new google.maps.Marker({
						  position:myCenter,
						  });

						marker.setMap(map);

						var infowindow = new google.maps.InfoWindow({
						  content:"<?php echo '<p><b>'.$a_configs_c['name'].'</b></p><p>'.$a_configs_c['address'].'</p><p>'.$a_configs_c['phone'].'</p><p>'.$a_configs_c['email'].'</p>'?>"
						  });

						infowindow.open(map,marker);
						}

						google.maps.event.addDomListener(window, 'load', initialize);
					</script>

					<div id="googleMap" style="width: 100%; height: 400px"></div>
				</div>
				<?php }?>
			</div>

			<div class="form col-xs-12 col-md-6 col-sm-6">
				<p class="warning"><?php echo __('Liên hệ với chúng tôi bằng cách điền thông tin vào mẫu dưới đây',true)?></p>
				 <?php echo $this->Form->create('Contact',array('inputDefaults'=>array('div'=>false,'label'=>false))) ?>

				<div class="form-group">
				<?php
				echo $this->Form->label('name',__('Họ tên',true).' <span class="im">*</span>');
					echo $this->Form->input('name',array('class'=>'form-control'));
					?>
				</div>
				<div class="form-group">
					<?php
						echo $this->Form->label('phone',__('Điện thoại',true).' <span class="im">*</span>');
						echo $this->Form->input('phone',array('class'=>'form-control'))
					?>
				</div>
				<div class="form-group">
					<?php
						echo $this->Form->label('email',__('Email',true));
						echo $this->Form->input('email',array('class'=>'form-control'))
					?>
				</div>
				<div class="form-group">
					<?php
						echo $this->Form->label('message',__('Nội dung',true));
						echo $this->Form->input('message',array('type'=>'textarea','class'=>'form-control'))
					?>
				</div>
				<div class="form-group">
					<?php
						echo $this->Recaptcha->display(array('recaptchaOptions'=>array(
																				 		'theme'=>'white',			//red, white, blackglass, clean
																				 		'lang'=>"$lang"
																				 	)));
					?>
				</div>
				<div class="form-group submit text-center m-t-15">
					<?php echo $this->Form->submit(__('Gửi',true),array('class'=>'btn btn-default','div'=>false))?>
				</div>
				<?php echo $this->Form->end();?>
			</div>
		</div>
	</div>
</div>
<!-- end contacts/index.ctp -->