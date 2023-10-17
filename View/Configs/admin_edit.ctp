<?php
	echo $this->Html->script(array('ckeditor/ckeditor','ckfinder/ckfinder'));

	//Slideshow
	$a_transitions = array(
		'random'=>'random',
		'none'=>'none',
		'fade'=>'fade',
		'h.slide'=>'horizontal slide',
		'v.slide'=>'vertical slide',
		'vertical stripes'=>array(
								'vert.random.fade'=>'random fade',
								'vert.tl'=>'top left',
								'vert.tr'=>'top right',
								'vert.bl'=>'bottom left',
								'vert.br'=>'bottom right',
								'fade.left'=>'left fade',
								'fade.right'=>'right fade',
								'alt.left'=>'left alternating',
								'alt.right'=>'right alternating',
								'blinds.left'=>'left blinds',
								'blinds.right'=>'right blinds'
							),
		'horizontal stripes'=>array(
							'horz.random.fade'=>'random fade',
							'horz.tl'=>'top left',
							'horz.tr'=>'top right',
							'horz.bl'=>'bottom left',
							'horz.br'=>'bottom right',
							'fade.top'=>'top fade',
							'fade.bottom'=>'bottom fade',
							'alt.top'=>'top alternating',
							'alt.bottom'=>'bottom alternating',
							'blinds.top'=>'top blinds',
							'blinds.bottom'=>'bottom blinds'
							),
		'blocks'=>array(
							'diag.fade'=>'diagional fade',
							'diag.exp'=>'diagional expand',
							'rev.diag.fade'=>'reverse diagional fade',
							'rev.diag.exp'=>'reverse diagional expand',
							'block.fade'=>'random fade',
							'block.exp'=>'random expand',
							'block.drop'=>'random drop',
							'spiral.in'=>'spiral in',
							'spiral.out'=>'spiral out',
							'block.top.zz'=>'top zig zag',
							'block.bottom.zz'=>'bottom zig zag',
							'block.left.zz'=>'left zig zag',
							'block.right.zz'=>'right zig zag',
							'block.top'=>'top expand',
							'block.bottom'=>'bottom expand',
							'block.left'=>'left expand',
							'block.right'=>'right expand'
							)
		);
	$a_essings = array(
				''=>'none',
				'linear'=>'linear',
				'easeInElastic'=>'easeInElastic',
				'easeOutElastic'=>'easeOutElastic',
				'easeInOutElastic'=>'easeInOutElastic',
				'easeInBack'=>'easeInBack',
				'easeOutBack'=>'easeOutBack',
				'easeInOutBack'=>'easeInOutBack',
				'easeInBounce'=>'easeInBounce',
				'easeOutBounce'=>'easeOutBounce',
				'easeInOutBounce'=>'easeInOutBounce',
				'easeInCirc'=>'easeInCirc',
				'easeOutCirc'=>'easeOutCirc',
				'easeInOutCirc'=>'easeInOutCirc',
				'easeInQuad'=>'easeInQuad',
				'easeOutQuad'=>'easeOutQuad',
				'easeInOutQuad'=>'easeInOutQuad',
				'easeInCubic'=>'easeInCubic',
				'easeOutCubic'=>'easeOutCubic',
				'easeInOutCubic'=>'easeInOutCubic',
				'easeInQuart'=>'easeInQuart',
				'easeOutQuart'=>'easeOutQuart',
				'easeInOutQuart'=>'easeInOutQuart',
				'easeInQuint'=>'easeInQuint',
				'easeOutQuint'=>'easeOutQuint',
				'easeInOutQuint'=>'easeInOutQuint',
				'easeInSine'=>'easeInSine',
				'easeOutSine'=>'easeOutSine',
				'easeInOutSine'=>'easeInOutSine',
				'easeInExpo'=>'easeInExpo',
				'easeOutExpo'=>'easeOutExpo',
				'easeInOutExpo'=>'easeInOutExpo'
			);
	$a_textbox_effects = array(
									'none'=>'none',
									'fade'=>'fade',
									'up'=>'up',
									'down'=>'down',
									'left'=>'left',
									'right'=>'right'
								);
	$a_tooltips = array(
								'image'=>'image',
								'text'=>'text',
								'none'=>'none'
							);
	$a_control_panels = array(
									'TL'=>'top left',
									'TC'=>'top center',
									'TR'=>'top right',
									'BL'=>'bottom left',
									'BC'=>'bottom center',
									'BR'=>'bottom right'
								);
	//end slideshow
?>

<script type="text/javascript">
	function activeTabChild(id){
		$("#"+id).show();
		$("ul.tabs_2 li").removeClass('active');
		$("li."+id).addClass('active');
	}

	$(document).ready(function(){
		//Chọn phương thức gửi email
		$("#typeEmail").change(function(){
			if($(this).val()=='Mail'){
				$("tr.smtp").hide();
			}else{
				$("tr.smtp").show();
			}
		});
		<?php if($this->request->data['Config']['email_smtp_transport']=='Mail') echo '$("tr.smtp").hide()'?>
	})
</script>


<div id="column_right">

	<div id="action_top">
		<ul class="tabs">
			<?php $i=1; foreach($oneweb_language as $key=>$val){?>
    		<li><a href="#tab<?php echo $key.$i?>" onclick="activeTabChild('<?php echo 'child1'.$key;?>')"><?php echo $val ?></a></li>
    		<?php $i++;}?>
    		<li><a href="#tab100" onclick="activeTabChild('<?php echo 'general1';?>')"><?php echo __('Cấu hình chung',true)?></a></li>
    	</ul> <!-- end .tabs -->
	</div> <!--  end #action_top -->
	<div id="content" class="config">
		<?php echo $this->Form->create('Config',array('type'=>'file','id'=>'form','inputDefaults'=>array('label'=>false,'div'=>false)))?>

		<div class="tab_container">
			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
			</ul> <!-- end .submit -->
			<?php $i=1;foreach($oneweb_language as $key=>$val){?>
			<div id="tab<?php echo $key.$i?>" class="tab_content">
				<table class="add">
					<tr>
						<td>
							<ul class="tabs_2">
								<li class="<?php echo 'child1'.$key;?>"><a href="<?php echo '#child1'.$key;?>"><?php echo __('Thông tin',true)?></a></li>
								<li class="<?php echo 'child2'.$key;?>"><a href="<?php echo '#child2'.$key;?>"><?php echo __('Giới thiệu trang',true)?></a></li>
								<?php if(!empty($oneweb_product['enable'])){?>
								<li class="<?php echo 'child5'.$key;?>"><a href="<?php echo '#child5'.$key;?>"><?php echo __('Trang sản phẩm',true)?></a></li>
								<?php }?>
								<li class="<?php echo 'child7'.$key;?>"><a href="<?php echo '#child7'.$key;?>">Trang đặt hàng</a></li>
								<li class="<?php echo 'child3'.$key;?>"><a href="<?php echo '#child3'.$key;?>">Email</a></li>
								<li class="<?php echo 'child4'.$key;?>"><a href="<?php echo '#child4'.$key;?>">SEO</a></li>
								<li class="<?php echo 'child6'.$key;?>"><a href="<?php echo '#child6'.$key;?>"><?php echo __('Khác',true)?></a></li>
				    		</ul> <!-- end .tabs -->
							<div class="tab_container_2">
								<div id="<?php echo 'child1'.$key;?>" class="tab_content_2">
									<table class="add column1">
										<tr><th class="title" colspan="2"><h3><?php echo __('Thông tin công ty',true)?></h3></th></tr>
										<tr>
											<th><?php echo $this->Form->label('contact_name.'.$key,__('Tên công ty',true))?></th>
											<td><?php echo $this->Form->input('contact_name.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_address.'.$key,__('Địa chỉ',true))?></th>
											<td><?php echo $this->Form->input('contact_address.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_email.'.$key,'Email')?></th>
											<td><?php echo $this->Form->input('contact_email.'.$key,array('class'=>'medium'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_phone.'.$key,__('Điện thoại',true))?></th>
											<td><?php echo $this->Form->input('contact_phone.'.$key,array('class'=>'medium'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_fax.'.$key,'Fax')?></th>
											<td><?php echo $this->Form->input('contact_fax.'.$key,array('class'=>'medium'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_hotline.'.$key,'Hotline')?></th>
											<td><?php echo $this->Form->input('contact_hotline.'.$key,array('class'=>'medium','type'=>'text'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_fb_chat.'.$key,'FB Chat')?></th>
											<td><?php echo $this->Form->input('contact_fb_chat.'.$key,array('class'=>'medium','placeholder'=>'Link facebook message','type'=>'text'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_zalo.'.$key,'Zalo')?></th>
											<td><?php echo $this->Form->input('contact_zalo.'.$key,array('class'=>'medium','placeholder'=>'Số điện thoại zalo','type'=>'text'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_top_promotion.'.$key,'Slogan')?></th>
											<td><?php echo $this->Form->input('contact_top_promotion.'.$key,array('class'=>'medium','type'=>'text'))?></td>
										</tr>
                                        <tr>
                                            <th><?php echo $this->Form->label('password_desk.'.$key,'Password Desk')?></th>
                                            <td><?php echo $this->Form->input('password_desk.'.$key,array('class'=>'medium','type'=>'text'))?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->Form->label('password_mobile.'.$key,'Password Mobile')?></th>
                                            <td><?php echo $this->Form->input('password_mobile.'.$key,array('class'=>'medium','type'=>'text'))?></td>
                                        </tr>
										<tr><th class="title" colspan="2"><h3>Chân web</h3></th></tr>
										<tr>
											<th><?php echo $this->Form->label('site_footer.'.$key,__('Chân web 1',true))?></th>
											<td>
												<?php
													echo $this->Form->input('site_footer.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('site_footer.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('site_footer2.'.$key,__('Chân web 2',true))?></th>
											<td>
												<?php
													echo $this->Form->input('site_footer2.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('site_footer2.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('site_footer_mobile.'.$key,__('Chân web 3',true))?></th>
											<td>
												<?php
													echo $this->Form->input('site_footer_mobile.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('site_footer_mobile.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
									</table>
								</div> <!-- end #tab21 -->
								<div id="<?php echo 'child2'.$key;?>" class="tab_content_2">
									<table class="add column1">
										<tr>
											<th><?php echo $this->Form->label('home_headline.'.$key,__('Thẻ h1 trang chủ',true))?></th>
											<td>
												<?php
													echo $this->Form->input('home_headline.'.$key, array('type'=> 'text','class'=>'larger'));
												?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('home_description.'.$key,__('Trang chủ',true))?></th>
											<td>
												<?php
													echo $this->Form->input('home_description.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('home_description.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<?php if(!empty($oneweb_contact['enable'])){?>
										<tr>
											<th><?php echo $this->Form->label('contact_description.'.$key,__('Trang liên hệ',true))?></th>
											<td>
												<?php
													echo $this->Form->input('contact_description.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('contact_description.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<?php }
										if(!empty($oneweb_media['document']['enable'])){
										?>
										<tr>
											<th><?php echo $this->Form->label('document_description.'.$key,__('Trang tài liệu',true))?></th>
											<td>
												<?php
													echo $this->Form->input('document_description.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('document_description.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<?php }
										if(!empty($oneweb_media['gallery']['enable'])){
										?>
										<tr>
											<th><?php echo $this->Form->label('gallery_description.'.$key,__('Trang hình ảnh',true))?></th>
											<td>
												<?php
													echo $this->Form->input('gallery_description.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('gallery_description.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<?php }
										if(!empty($oneweb_media['video']['enable'])){
										?>
										<tr>
											<th><?php echo $this->Form->label('video_description.'.$key,__('Trang video',true))?></th>
											<td>
												<?php
													echo $this->Form->input('video_description.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('video_description.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<?php }
										if(!empty($oneweb_faq['enable'])){
										?>
										<tr>
											<th><?php echo $this->Form->label('faq_description.'.$key,__('Trang FAQs',true))?></th>
											<td>
												<?php
													echo $this->Form->input('faq_description.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('faq_description.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<?php }?>
									</table>
								</div> <!-- end #child2 -->
								<?php if(!empty($oneweb_product['enable'])){?>
								<div id="<?php echo 'child5'.$key;?>" class="tab_content_2">
									<table class="add column1">
										<tr><th class="title" colspan="2"><h3><?php echo __('Trang chi tiết sản phẩm',true)?></h3></th></tr>
										<tr>
											<th><?php echo $this->Form->label('product_tab.'.$key,__('Tab sản phẩm',true))?></th>
											<td><?php echo $this->Form->input('product_tab.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('product_description.'.$key,__('Giới thiệu',true))?></th>
											<td>
												<?php
													echo $this->Form->input('product_description.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('product_description.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('product_address.'.$key,__('Địa chỉ(phía dưới mỗi sp)',true))?></th>
											<td>
												<?php
													echo $this->Form->input('product_address.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('product_address.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<tr><th class="title" colspan="2"><h3>Trang xuất hiện sau khi đặt hàng</h3></th></tr>
										<tr>
											<th><?php echo $this->Form->label('product_thank.'.$key,__('Trang cảm ơn',true))?></th>
											<td>
												<?php
													echo $this->Form->input('product_thank.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('product_thank.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('product_error.'.$key,__('Trang báo lỗi',true))?></th>
											<td>
												<?php
													echo $this->Form->input('product_error.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('product_error.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
									</table>
								</div> <!-- end #child5 -->
								<?php }?>


								<div id="<?php echo 'child7'.$key;?>" class="tab_content_2">
									<table class="add column1">
										<tr>
											<th><?php echo $this->Form->label('order_bankinfo',__('Thông tin ngân hàng'))?></th>
											<td>
											<?php
												echo $this->Form->input('order_bankinfo',array('type'=>'textarea'));
												echo $this->CkEditor->create('order_bankinfo',array('toolbar'=>'standard'));
											?>
											</td>
										</tr>
										<!-- <tr>
											<th><?php //echo $this->Form->label('order_placeinfo',__('Địa điểm mua hàng trực tiếp'))?></th>
											<td>
											<?php
												//echo $this->Form->input('order_placeinfo',array('type'=>'textarea'));
												//echo $this->CkEditor->create('order_placeinfo',array('toolbar'=>'standard'));
											?>
											</td>
										</tr>
										<tr>
											<th><?php //echo $this->Form->label('order_thanks',__('Thông tin cảm ơn'))?></th>
											<td>
											<?php
												// echo $this->Form->input('order_thanks',array('type'=>'textarea'));
												// echo $this->CkEditor->create('order_thanks',array('toolbar'=>'standard'));
											?>
											</td>-->
										</tr>
									</table>
								</div>

								<div id="<?php echo 'child3'.$key;?>" class="tab_content_2">
									<table class="add column1">
										<?php if(!empty($oneweb_product['order'])){?>
										<tr><th colspan="2" class="title"><h3><?php echo __('Email nhận đơn đặt hàng',true)?></h3></th></tr>
										<tr>
											<th><?php echo $this->Form->label('email_product.'.$key,'Email')?></th>
											<td><?php echo $this->Form->input('email_product.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('email_product_subject.'.$key,__('Tiêu đề',true))?></th>
											<td><?php echo $this->Form->input('email_product_subject.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('email_product_description.'.$key,__('Nội dung',true))?></th>
											<td>
												<?php
													echo $this->Form->input('email_product_description.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('email_product_description.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<?php }?>

										<?php if(!empty($oneweb_contact['enable'])){?>
										<tr><th colspan="2" class="title"><h3><?php echo __('Email nhận từ form liên hệ',true)?></h3></th></tr>
										<tr>
											<th><?php echo $this->Form->label('email_contact.'.$key,'Email')?></th>
											<td><?php echo $this->Form->input('email_contact.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('email_contact_subject.'.$key,__('Tiêu đề',true))?></th>
											<td><?php echo $this->Form->input('email_contact_subject.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('email_contact_description.'.$key,__('Nội dung',true))?></th>
											<td>
												<?php
													echo $this->Form->input('email_contact_description.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('email_contact_description.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<?php }?>
										<?php if(!empty($oneweb_member['enable'])){?>
										<tr><th colspan="2" class="title"><h3><?php echo __('Email trang thành viên', true)?></h3></th></tr>
										<tr>
											<th><?php echo $this->Form->label('email_member.'.$key,'Email')?></th>
											<td><?php echo $this->Form->input('email_member.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('email_member_subject.'.$key,'Tiêu đề')?></th>
											<td><?php echo $this->Form->input('email_member_subject.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('email_member_description.'.$key,'Nội dung đăng ký')?></th>
											<td>
												<?php
													echo $this->Form->input('email_member_description.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('email_member_description.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('email_member_description_reset_pass.'.$key,'Nội dung thư lấy lại mật khẩu')?></th>
											<td>
												<?php
													echo $this->Form->input('email_member_description_reset_pass.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('email_member_description_reset_pass.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
										<?php }?>

										<tr><th colspan="2" class="title"><h3><?php echo __('Email User', true)?></h3></th></tr>

										<tr>
											<th><?php echo $this->Form->label('email_user_subject.'.$key,'Tiêu đề')?></th>
											<td><?php echo $this->Form->input('email_user_subject.'.$key,array('class'=>'larger'))?></td>
										</tr>

										<tr>
											<th><?php echo $this->Form->label('email_user_description_reset_pass.'.$key,'Nội dung thư lấy lại mật khẩu')?></th>
											<td>
												<?php
													echo $this->Form->input('email_user_description_reset_pass.'.$key, array('type'=> 'textarea','div'=>'description'));
													echo $this->CkEditor->create('email_user_description_reset_pass.'.$key,array('toolbar'=>'full'));
												?>
											</td>
										</tr>
									</table>
								</div> <!-- end #child3 -->

								<div id="<?php echo 'child4'.$key;?>" class="tab_content_2">
									<table class="add column1">
										<tr>
											<th colspan="2" class="title"><h3><?php echo __('Trang chủ',true)?></h3></th>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('home_meta_title.'.$key,'Meta title')?></th>
											<td><?php echo $this->Form->input('home_meta_title.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('home_meta_keyword.'.$key,'Meta keyword')?></th>
											<td><?php echo $this->Form->input('home_meta_keyword.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('home_meta_description.'.$key,'Meta description')?></th>
											<td><?php echo $this->Form->input('home_meta_description.'.$key,array('type'=>'textarea','class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('home_meta_robots.'.$key,'Meta robots')?></th>
											<td><?php echo $this->Form->input('home_meta_robots.'.$key,array('class'=>'medium','type'=>'select','options'=>array('index,follow'=>'index,follow','noindex,nofollow'=>'noindex,nofollow','index,nofollow'=>'index,nofollow','noindex,follow'=>'noindex,follow')))?></td>
										</tr>
										<?php if(!empty($oneweb_seo)){
											if(!empty($oneweb_contact['enable'])){
										?>
										<tr>
											<th colspan="2" class="title"><h3><?php echo __('Trang liên hệ',true)?></h3></th>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_meta_title.'.$key,'Meta title')?></th>
											<td><?php echo $this->Form->input('contact_meta_title.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_meta_keyword.'.$key,'Meta keyword')?></th>
											<td><?php echo $this->Form->input('contact_meta_keyword.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_meta_description.'.$key,'Meta description')?></th>
											<td><?php echo $this->Form->input('contact_meta_description.'.$key,array('type'=>'textarea','class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_meta_robots.'.$key,'Meta robots')?></th>
											<td><?php echo $this->Form->input('contact_meta_robots.'.$key,array('class'=>'medium','type'=>'select','options'=>array('index,follow'=>'index,follow','noindex,nofollow'=>'noindex,nofollow','index,nofollow'=>'index,nofollow','noindex,follow'=>'noindex,follow')))?></td>
										</tr>
										<?php }
										if(!empty($oneweb_media['document']['enable'])){
										?>
										<tr>
											<th colspan="2" class="title"><h3><?php echo __('Trang tài liệu',true)?></h3></th>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('document_meta_title.'.$key,'Meta title')?></th>
											<td><?php echo $this->Form->input('document_meta_title.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('document_meta_keyword.'.$key,'Meta keyword')?></th>
											<td><?php echo $this->Form->input('document_meta_keyword.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('document_meta_description.'.$key,'Meta description')?></th>
											<td><?php echo $this->Form->input('document_meta_description.'.$key,array('type'=>'textarea','class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('document_meta_robots.'.$key,'Meta robots')?></th>
											<td><?php echo $this->Form->input('document_meta_robots.'.$key,array('class'=>'medium','type'=>'select','options'=>array('index,follow'=>'index,follow','noindex,nofollow'=>'noindex,nofollow','index,nofollow'=>'index,nofollow','noindex,follow'=>'noindex,follow')))?></td>
										</tr>
										<?php }
										if(!empty($oneweb_media['gallery']['enable'])){
										?>
										<tr>
											<th colspan="2" class="title"><h3><?php echo __('Trang hình ảnh',true)?></h3></th>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('gallery_meta_title.'.$key,'Meta title')?></th>
											<td><?php echo $this->Form->input('gallery_meta_title.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('gallery_meta_keyword.'.$key,'Meta keyword')?></th>
											<td><?php echo $this->Form->input('gallery_meta_keyword.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('gallery_meta_description.'.$key,'Meta description')?></th>
											<td><?php echo $this->Form->input('gallery_meta_description.'.$key,array('type'=>'textarea','class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('gallery_meta_robots.'.$key,'Meta robots')?></th>
											<td><?php echo $this->Form->input('gallery_meta_robots.'.$key,array('class'=>'medium','type'=>'select','options'=>array('index,follow'=>'index,follow','noindex,nofollow'=>'noindex,nofollow','index,nofollow'=>'index,nofollow','noindex,follow'=>'noindex,follow')))?></td>
										</tr>
										<?php }
										if(!empty($oneweb_media['video']['enable'])){
										?>
										<tr>
											<th colspan="2" class="title"><h3><?php echo __('Trang Video',true)?></h3></th>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('video_meta_title.'.$key,'Meta title')?></th>
											<td><?php echo $this->Form->input('video_meta_title.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('video_meta_keyword.'.$key,'Meta keyword')?></th>
											<td><?php echo $this->Form->input('video_meta_keyword.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('video_meta_description.'.$key,'Meta description')?></th>
											<td><?php echo $this->Form->input('video_meta_description.'.$key,array('type'=>'textarea','class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('video_meta_robots.'.$key,'Meta robots')?></th>
											<td><?php echo $this->Form->input('video_meta_robots.'.$key,array('class'=>'medium','type'=>'select','options'=>array('index,follow'=>'index,follow','noindex,nofollow'=>'noindex,nofollow','index,nofollow'=>'index,nofollow','noindex,follow'=>'noindex,follow')))?></td>
										</tr>
										<?php }
										if(!empty($oneweb_faq['enable'])){
										?>
										<tr>
											<th colspan="2" class="title"><h3><?php echo __('Trang FAQs',true)?></h3></th>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('faq_meta_title.'.$key,'Meta title')?></th>
											<td><?php echo $this->Form->input('faq_meta_title.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('faq_meta_keyword.'.$key,'Meta keyword')?></th>
											<td><?php echo $this->Form->input('faq_meta_keyword.'.$key,array('class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('faq_meta_description.'.$key,'Meta description')?></th>
											<td><?php echo $this->Form->input('faq_meta_description.'.$key,array('type'=>'textarea','class'=>'larger'))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('faq_meta_robots.'.$key,'Meta robots')?></th>
											<td><?php echo $this->Form->input('faq_meta_robots.'.$key,array('class'=>'medium','type'=>'select','options'=>array('index,follow'=>'index,follow','noindex,nofollow'=>'noindex,nofollow','index,nofollow'=>'index,nofollow','noindex,follow'=>'noindex,follow')))?></td>
										</tr>
										<?php }}?>
									</table>
								</div> <!-- end #child4 -->

								<div id="<?php echo 'child6'.$key;?>" class="tab_content_2">
									<table class="add column1">
										<tr>
											<th><?php echo $this->Form->label('site_currency.'.$key,__('Đơn vị tiền mặc định',true))?></th>
											<td><?php echo $this->Form->input('site_currency.'.$key,array('type'=>'select','options'=>$a_currencies_c,'class'=>'medium'))?></td>
										</tr>
									</table> <!-- end .add -->
								</div> <!-- end #child6 -->

							</div> <!-- end .tab_container_2 -->
						</td>
					</tr>
				</table>
			</div> <!-- end #tab1 -->
			<?php $i++;}?>

			<div id="tab100" class="tab_content">
				<table class="add">
					<tr>
						<td colspan="2">
							<ul class="tabs_2">
								<li class="<?php echo 'general1';?>"><a href="<?php echo '#general1';?>"><?php echo __('Cấu hình Email',true)?></a></li>
								<li class="<?php echo 'general3';?>"><a href="<?php echo '#general3';?>"><?php echo __('Bản đồ',true)?></a></li>
								<?php if(!empty($oneweb_web['social'])){?>
								<li class="<?php echo 'general4';?>"><a href="<?php echo '#general4';?>"><?php echo __('Mạng xã hội',true)?></a></li>
								<li class="<?php echo 'general5';?>"><a href="<?php echo '#general5';?>"><?php echo __('Bài Viết',true)?></a></li>
								<?php }?>
				    		</ul> <!-- end .tabs -->

							<div class="tab_container_2">
								<div id="general1" class="tab_content_2">
									<table class="add column1">
										<tr>
											<th><?php //echo $this->Form->label('email_smtp_transport','Transport')?></th>
											<td><?php //echo $this->Form->input('email_smtp_transport',array('class'=>'medium','type'=>'select','options'=>array('Mail'=>'Mail','Smtp'=>'Smtp'),'id'=>'typeEmail'))?></td>
										</tr>
										<tr>
											<th><?php //echo $this->Form->label('email_smtp_username','Email')?></th>
											<td><?php //echo $this->Form->input('email_smtp_username',array('class'=>'medium'))?></td>
										</tr>
										<tr class="smtp">
											<th><?php //echo $this->Form->label('email_smtp_password','Password')?></th>
											<td><?php //echo $this->Form->input('email_smtp_password',array('class'=>'medium','type'=>'password'))?></td>
										</tr>
										<tr class="smtp">
											<th><?php //echo $this->Form->label('email_smtp_host','SMTP Host')?></th>
											<td><?php //echo $this->Form->input('email_smtp_host',array('class'=>'medium'))?></td>
										</tr>
										<tr class="smtp">
											<th><?php //echo $this->Form->label('email_smtp_port','SMTP Port')?></th>
											<td><?php //echo $this->Form->input('email_smtp_port',array('class'=>'small'))?></td>
										</tr>
									</table> <!-- end .add -->
								</div> <!-- end #general2 -->

								<div id="general2" class="tab_content_2">
									<table class="add column1">
										<tr>
											<th><?php echo $this->Form->label('slideshow_transition','Transition')?></th>
											<td><?php echo $this->Form->input('slideshow_transition',array('class'=>'medium','type'=>'select','options'=>$a_transitions))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('slideshow_transition_easing','Transition easing')?></th>
											<td><?php echo $this->Form->input('slideshow_transition_easing',array('class'=>'medium','type'=>'select','options'=>$a_essings))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('slideshow_textbox_effect','Textbox Effect')?></th>
											<td><?php echo $this->Form->input('slideshow_textbox_effect',array('class'=>'medium','type'=>'select','options'=>$a_textbox_effects))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('slideshow_tooltip','Tooltip')?></th>
											<td><?php echo $this->Form->input('slideshow_tooltip',array('class'=>'medium','type'=>'select','options'=>$a_tooltips))?></td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('slideshow_control_panel_position','Control Panel Position')?></th>
											<td><?php echo $this->Form->input('slideshow_control_panel_position',array('class'=>'medium','type'=>'select','options'=>$a_control_panels))?></td>
										</tr>
										<tr>
											<th>Display</th>
											<td class="display">
												<ul>
													<li>
														<?php
															echo $this->Form->checkBox('slideshow_display_thumb');
															echo $this->Form->label('slideshow_display_thumb','Thumbs');
														?>
													</li>
													<li>
														<?php
															echo $this->Form->checkBox('slideshow_display_play_button');
															echo $this->Form->label('slideshow_display_play_button','Play button');
														?>
													</li>
													<li>
														<?php
															echo $this->Form->checkBox('slideshow_display_back_forward');
															echo $this->Form->label('slideshow_display_back_forward','Back/forward');
														?>
													</li>
													<li>
														<?php
															echo $this->Form->checkBox('slideshow_display_timer_bar');
															echo $this->Form->label('slideshow_display_timer_bar','Timer bar');
														?>
													</li>
												</ul> <!-- end .display -->
											</td>
										</tr>
										<tr>
											<th>Mouseover</th>
											<td class="display">
												<ul>
													<li>
														<?php
															echo $this->Form->checkBox('slideshow_mouseover_pause');
															echo $this->Form->label('slideshow_mouseover_pause','Pause');
														?>
													</li>
													<li>
														<?php
															echo $this->Form->checkBox('slideshow_mouseover_text_panel');
															echo $this->Form->label('slideshow_mouseover_text_panel','Text panel');
														?>
													</li>
													<li>
														<?php
															echo $this->Form->checkBox('slideshow_mouseover_control_panel');
															echo $this->Form->label('slideshow_mouseover_control_panel','Control panel');
														?>
													</li>
												</ul> <!-- end .display -->
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('slideshow_auto_start','Auto start')?></th>
											<td><?php echo $this->Form->checkBox('slideshow_auto_start')?></td>
										</tr>
									</table> <!-- end .add -->
								</div> <!-- end #general2 -->

								<div id="general3" class="tab_content_2">
									<table class="add column1">
										<tr>
											<th><?php echo $this->Form->label('contact_map_latitude',__('Tọa độ',true))?></th>
											<td>
											<?php
												echo $this->Form->input('contact_map_latitude',array('class'=>'medium'));
												echo $this->Html->link(__('?',true),$oneweb_web['help'].'google_map.php?d='.base64_encode($_SERVER['HTTP_HOST']).'#map_latitude',array('title'=>__('Hướng dẫn',true),'target'=>'_blank','class'=>'help tooltip'));
											?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_map_key',__('API Key',true))?></th>
											<td>
												<?php
													echo $this->Form->input('contact_map_key',array('class'=>'medium'));
													echo $this->Html->link(__('?',true),$oneweb_web['help'].'google_map.php?d='.base64_encode($_SERVER['HTTP_HOST']).'#map_key',array('title'=>__('Hướng dẫn',true),'target'=>'_blank','class'=>'help tooltip'));
												?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('contact_map_zoom',__('Phóng to',true))?></th>
											<td><?php echo $this->Form->input('contact_map_zoom',array('class'=>'medium'))?></td>
										</tr>
									</table> <!-- end .add -->
								</div> <!-- end #general3 -->

								<?php if(!empty($oneweb_web['social'])){?>
								<div id="general4" class="tab_content_2">
									<table class="add column1">
										<tr>
											<th><?php echo $this->Form->label('site_facebook',__('Facebook',true))?></th>
											<td>
												<?php
													echo $this->Form->input('site_facebook',array('class'=>'larger'));
													echo ' '.$this->Form->input('site_facebook_like',array('type'=>'checkbox','label'=>'Like'))
												?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('site_google',__('Google Plus',true))?></th>
											<td>
												<?php
													echo $this->Form->input('site_google',array('class'=>'larger'));
													echo ' '.$this->Form->input('site_google_like',array('type'=>'checkbox','label'=>'Like'))
												?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('site_twitter',__('Twitter',true))?></th>
											<td>
												<?php
													echo $this->Form->input('site_twitter',array('class'=>'larger'));
													echo ' '.$this->Form->input('site_twitter_like',array('type'=>'checkbox','label'=>'Like'))
												?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('site_linkedin',__('LinkedIn',true))?></th>
											<td>
												<?php
													echo $this->Form->input('site_linkedin',array('class'=>'larger'));
													echo ' '.$this->Form->input('site_linkedin_like',array('type'=>'checkbox','label'=>'Like'))
												?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('site_blogspot',__('BlogSpot',true))?></th>
											<td>
												<?php echo $this->Form->input('site_blogspot',array('class'=>'larger')) ?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('site_youtube',__('YouTube',true))?></th>
											<td>
												<?php echo $this->Form->input('site_youtube',array('class'=>'larger'))?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('site_pinterest',__('Pinterest',true))?></th>
											<td>
												<?php echo $this->Form->input('site_pinterest',array('class'=>'larger'))?>
											</td>
										</tr>
										<tr>
											<th><?php echo $this->Form->label('site_rss',__('RSS',true))?></th>
											<td>
												<?php echo $this->Form->input('site_rss',array('class'=>'larger'))?>
											</td>
										</tr>
									</table> <!-- end .add -->
								</div> <!-- end #general4 -->
								<?php }?>
								<div id="general5" class="tab_content_2">
								<table class="add column1">
										<tr>
											<th><?php echo $this->Form->label('number_of_posts',__('Số lượng bài viết',true))?></th>
											<td>
												<?php echo $this->Form->input('number_of_posts',array('class'=>'larger'))?>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2"><hr></td>
					</tr>
					<tr>
						<th><?php echo $this->Form->label('site_enable',__('Bật website',true))?></th>
						<td><?php echo $this->Form->checkBox('site_enable')?></td>
					</tr>
					<tr><th colspan="2" class="title"><h3><?php echo $this->Form->label('site_message',__('Nội dung hiển thị khi tắt website',true))?></h3></th></tr>
					<tr>
						<td colspan="2">
							<?php
								echo $this->Form->input('site_message', array('type'=> 'textarea','div'=>'description'));
								echo $this->CkEditor->create('site_message',array('toolbar'=>'user'));
							?>
						</td>
					</tr>
				</table>
			</div> <!-- end #tab100 -->


			<ul class="submit">
				<li><?php echo $this->Form->submit(__('Lưu',true),array('name'=>'save','div'=>false))?><span></span></li>
			</ul> <!-- end .submit -->
		</div> <!-- end .tab_container -->
		<?php echo $this->Form->end();?>
	</div> <!--  end #content -->
</div> <!--  end #column_right -->
