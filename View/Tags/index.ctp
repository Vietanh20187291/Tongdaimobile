<!-- start tags/index.ctp -->
<article>
	<!-- tab -->
	<script type="text/javascript">
		$(document).ready(function() {
			//Default Action
			$(".tab_content").hide(); //Hide all content
			$("ul.tabs li:first").addClass("active").show(); //Activate first tab
			$(".tab_content:first").show(); //Show first tab content

			//On Click Event
			$("ul.tabs li").click(function() {
				$("ul.tabs li").removeClass("active"); //Remove any "active" class
				$(this).addClass("active"); //Add "active" class to selected tab
				$(".tab_content").hide(); //Hide all tab content
				var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
				$(activeTab).fadeIn(); //Fade in the active content
				return false;
			});

		});
	</script>

	<!-- Sản phẩm -->
	<div class="box_content list_tag">
		<div class="box_content_bottom">
			<div class="box_content_middle">
				<header class="title">
					<h1><?php if($a_tags_c['Tag']['name']) echo 'Tag: '.$a_tags_c['Tag']['name']?></h1>
				</header>
				<div class="des">
					<!-- Description category -->
					<?php if($a_tags_c['Tag']['description']){?>
					<div class="box_about_top">
						<div class="box_about_top_bottom">
							<div class="box_about_top_middle">
								<div class="des">
									<?php echo $a_tags_c['Tag']['description']?>
								</div>
							</div>
						</div>
					</div>
					<?php }?>


					<ul class="tabs">
						<?php

							if(!empty($oneweb_product['tag'])){
						?>
					    <li><a href="#tab1" onclick="getProduct(1)"><?php echo __('Sản phẩm',true)?></a></li>
					    <?php }?>
					    <?php

							if(!empty($oneweb_post['tag'])){
						?>
					    <li><a href="#tab2" onclick="getPost(1);"><?php echo __('Bài viết',true)?></a></li>
					    <?php } ?>
					</ul>

					<div class="tab_container">
						<?php
							if ( ! empty($oneweb_product['tag']))
							{
						?>
							<div id="tab1" class="tab_content">
								<div id="ajax_content_product"></div>
							</div>
						<?php
							}
						?>
						<?php
							if ( ! empty($oneweb_post['tag']))
							{
						?>
							<div id="tab2" class="tab_content">
								<div id="ajax_content_post"></div>
							</div>
						<?php
							}
						?>
					</div>

					<script type="text/javascript">
						$(document).ready(function(){
							getProduct(1);
						});

						//Lay san pham theo trang
						function getProduct(page){
							$.ajax({
								type: 'post',
								url:'<?php echo $this->Html->url(array('controller'=>'tags','action'=>'ajaxGetProduct','lang'=>$lang))?>',
								data:'tag_id=<?php echo $a_tags_c['Tag']['id']?>&page='+page,
								beforeSend:function(){
									$("#ajax_loading").show();
								},
								success:function(result){
									$("#ajax_content_product").html(result);
									$("#ajax_loading").hide();
								}
							});
						};

						//Lấy bài viết theo trang
						function getPost(page){
							$.ajax({
								type: 'post',
								url:'<?php echo $this->Html->url(array('controller'=>'tags','action'=>'ajaxGetPost','lang'=>$lang))?>',
								data:'tag_id=<?php echo $a_tags_c['Tag']['id']?>&page='+page,
								beforeSend:function(){
									$("#ajax_loading").show();
								},
								success:function(result){
									$("#ajax_content_post").html(result);
									$("#ajax_loading").hide();
								}
							});
						}
					</script>
				</div>
			</div>
		</div>
	</div>
</article>
<!-- end tags/index.ctp -->