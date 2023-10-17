<?php 
	if(!empty($url)){
?>
<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<?php 
			$link_current = $this->OnewebVn->curPageURL($url);
		?>
<section>
	<div id="comment">
		<div class="fb-comments" data-href="<?php echo $link_current;?>" data-width="<?php echo $width;?>"></div>
	</div> <!--  end #comment -->
</section>
<?php } ?>