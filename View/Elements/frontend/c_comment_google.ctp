<section>
	<div id="comment">
		<span class="title3" id="write_comment"><?php echo __('Ý kiến của bạn',true)?></span>
		<?php 
		echo $this->Html->script(array('plusone'));
		$link_current = $this->OnewebVn->curPageURL($url);
		?>
		   <div id="google_comments"></div>
			<script>
			gapi.comments.render('google_comments', {
		    href:'<?php echo $link_current;?>',
		    width: '<?php echo $width; ?>',
		    first_party_property: 'BLOGGER',
		    view_type: 'FILTERED_POSTMOD'
		});
		</script>		
	</div> <!--  end #comment -->
</section>