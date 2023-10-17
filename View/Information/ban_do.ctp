<?php
	if(!empty($a_configs_c['map_latitude']) && !empty($a_configs_c['map_key'])){?>
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

		<div id="googleMap"></div>
	</div> <!--  end .map -->
<?php }?>