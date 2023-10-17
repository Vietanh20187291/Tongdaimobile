<!-- start frontend/both_column.ctp -->
<div class="col-md-3 col-sm-3 hidden-xs" role="navigation">
	<div class="sidebar_left_bottom">
		<div class="sidebar_left_middle">
			<?php
				//Menu thanh vien
				echo $this->element('frontend/s_member_menu');

				//Danh muc sp
				if(!empty($a_product_categories_s)){
					if($controller != 'products' || ($controller=='products' && $action=='maker')){		//Áp dụng khi không cần active trang thái
						$str_pro_cate = Cache::read("pro_cate_general_$lang",'oneweb_view');
						if(!$str_pro_cate){
							$str_pro_cate = $this->element('frontend/s_product_category');
							Cache::write("pro_cate_general_$lang",$str_pro_cate,'oneweb_view');
						}
					}else{
						$str_pro_cate = Cache::read('pro_cate_'.$url_current_encode.'_'.$lang,'oneweb_view');
						if(!$str_pro_cate){
							$str_pro_cate = $this->element('frontend/s_product_category');
							Cache::write('pro_cate_'.$url_current_encode.'_'.$lang,$str_pro_cate,'oneweb_view');
						}
					}
					echo $str_pro_cate;
				}

				//Hang san xuat
				if(!empty($a_product_makers_s)){
					if($controller == 'products'){		//Áp dụng khi cần active trang thái
						if($action=='maker'){
							$str_pro_maker = Cache::read('pro_maker_'.$url_current_encode.'_'.$lang,'oneweb_view');
							if(!$str_pro_maker){
								$str_pro_maker = $this->element('frontend/s_product_maker',array('data'=>$a_product_makers_s));
								Cache::write('pro_maker_'.$url_current_encode.'_'.$lang,$str_pro_maker,'oneweb_view');
							}
						}elseif(empty($this->params['ext'])){
							$str_pro_maker = Cache::read('pro_maker_2_'.$url_current_encode.'_'.$lang,'oneweb_view');
							if(!$str_pro_maker){
								$str_pro_maker = $this->element('frontend/s_product_maker2',array('data'=>$a_product_makers_s));
								Cache::write('pro_maker_2_'.$url_current_encode.'_'.$lang,$str_pro_maker,'oneweb_view');
							}
						}
					}else{
						$str_pro_maker = Cache::read("pro_maker_general_$lang",'oneweb_view');
						if(!$str_pro_maker){
							$str_pro_maker = $this->element('frontend/s_product_maker',array('data'=>$a_product_makers_s));
							Cache::write("pro_maker_general_$lang",$str_pro_maker,'oneweb_view');
						}
					}

					echo @$str_pro_maker;
				}


				//Loc theo gia
//								echo $this->element('frontend/s_product_filter_price');

				//San pham hien thi tuy chon
				if(!empty($a_products_pos7)){
					$str_pro_pos7 = Cache::read("pro_7_$lang",'oneweb_view');
					if(!$str_pro_pos7){
						$str_pro_pos7 = $this->element('frontend/s_product_display',array(
																		'data'		=> $a_products_pos7,
																		'position'	=> 7,
																		'class'		=> '',
																		'run'		=> true,			//Bật/Tắt chức năng chạy
																		'w'			=> 110,
																		'zc'		=> 2,
																		'direction'	=> 'up',			//up, down
																		'items'		=> 2,
																		'fx'        => 'fade'			//"none", "scroll", "directscroll", "fade", "crossfade", "cover",  "uncover" hoặc "scroll_continuous".
																	));
						Cache::write("pro_7_$lang",$str_pro_pos7,'oneweb_view');
					}
					echo $str_pro_pos7;
				}

				//Video
				if(!empty($a_videos_pos1)){
					$str_video_1 = Cache::read("video_1_$lang",'oneweb_view');
					if(!$str_video_1){
						$str_video_1 = $this->element('frontend/s_video',array('data'=>$a_videos_pos1));
						Cache::write("video_1_$lang",$str_video_1,'oneweb_view');
					}
					echo $str_video_1;
				}

				//Gallery
				if(!empty($a_galleries_pos1)){
					$str_gallery_1 = Cache::read("gallery_1_$lang",'oneweb_view');
					if(!$str_gallery_1){
						$str_gallery_1 = $this->element('frontend/s_gallery',array(
																		'data'		=> $a_galleries_pos1,
																		'position'	=> 1,
																		'class'		=> '',
																		'w'			=> 110,
																		'zc'		=> 2,
																		'direction'	=> 'up', 			//up, down
																		'items'		=> 1,
																		'fx'        => 'scroll_continuous'			//"none", "scroll", "directscroll", "fade", "crossfade", "cover",  "uncover" hoặc "scroll_continuous".
																	));
						Cache::write("gallery_1_$lang",$str_gallery_1,'oneweb_view');
					}
					echo $str_gallery_1;
				}

				//Newsletter
				if(!empty($oneweb_newsletter['enable'])) echo $this->element('frontend/s_newsletter');
				//Binh chon
				if(!empty($oneweb_poll['enable']) && !empty($a_polls)) echo $this->element('frontend/s_poll', array(
																			'data'=>$a_polls,
																			'title'=>__('Thăm dò ý kiến', true)
																		));

				//Banner partner
				if(!empty($a_partner_l)) {
					$str_partner_l = Cache::read("partner_l_$lang",'oneweb_view');
					if(!$str_partner_l){
						$str_partner_l = $this->element('frontend/s_banner',array(
																					'data'=>$a_partner_l,
																					'title'=>__('Đối tác',true),
																					'position'=>6
																				));
						Cache::write("partner_l_$lang",$str_partner_l,'oneweb_view');
					}
					echo $str_partner_l;
				}

				//Banner adv
				if(!empty($a_adv_l)){
					$str_adv_l = Cache::read("adv_l_$lang",'oneweb_view');
					if(!$str_adv_l){
						$str_adv_l = $this->element('frontend/s_banner',array(
																				'data'=>$a_adv_l,
																				'title'=>__('Quảng cáo',true),
																				'position'=>4
																			));
						Cache::write("adv_l_$lang",$str_adv_l,'oneweb_view');
					}
					echo $str_adv_l;
				}
			?>
		</div>
	</div>
</div>
<div class="col-md-6">
	<?php echo $content_for_layout;?>
</div>
<div class="col-md-3 col-sm-3 hidden-xs" role="navigation">
	<div class="sidebar_right_bottom">
	<div class="sidebar_right_middle">
		<?php
			//Ho tro truc tuyen
			if(!empty($a_support_s)){
				$str_support = Cache::read("support_$lang",'oneweb_view');
				if(!$str_support){
					$str_support = $this->element('frontend/s_support');
					Cache::write("support_$lang",$str_support,'oneweb_view');
				}
				echo $str_support;
			}


			//Tim kiem
			if(!empty($oneweb_search['product']['enable']) || !empty($oneweb_search['post']['enable'])){
				$str_search = Cache::read("search_$lang",'oneweb_view');
				if(!$str_search){
					$str_search = $this->element('frontend/s_search');
					Cache::write("search_$lang",$str_search,'oneweb_view');
				}
				echo $str_search;
			}

			//Bai viet hien thi tuy chon
			if(!empty($a_posts_pos5)){
				$str_posts_pos5 = Cache::read("post_pos5_$lang",'oneweb_view');
				if(!$str_posts_pos5){
					$str_posts_pos5 = $this->element('frontend/s_post_display',array(
																	'data'		=> $a_posts_pos5,
																	'position'	=> 5,
																	'class'		=> '',
																	'run'		=> true,			//Bật/Tắt chức năng chạy
																	'direction'	=> 'up',			//up, down
																	'items'		=> 5,
																	'fx'        => 'scroll_continuous'			//"none", "scroll", "directscroll", "fade", "crossfade", "cover",  "uncover" hoặc "scroll_continuous".
																));
					Cache::write("post_pos5_$lang",$str_posts_pos5,'oneweb_view');
				}
				echo $str_posts_pos5;
			}

			//San pham tuy chon hien thi
			if(!empty($a_products_pos8)){
				$str_pro_pos8 = Cache::read("pro_8_$lang",'oneweb_view');
				if(!$str_pro_pos8){
					$str_pro_pos8 = $this->element('frontend/s_product_display',array(
							'data'		=> $a_products_pos8,
							'position'	=> 8,
							'class'		=> '',
							'run'		=> true,			//Bật/Tắt chức năng chạy
							'w'			=> 110,
							'zc'		=> 2,
							'direction'	=> 'up',			//up, down
							'items'		=> 2,
							'fx'        => 'fade'			//"none", "scroll", "directscroll", "fade", "crossfade", "cover",  "uncover" hoặc "scroll_continuous".
					));
					Cache::write("pro_8_$lang",$str_pro_pos8,'oneweb_view');
				}
				echo $str_pro_pos8;
			}

			//Bai viet hien thi tuy chon
			if(!empty($a_posts_pos6)){
				$str_posts_pos6 = Cache::read("post_pos6_$lang",'oneweb_view');
				if(!$str_posts_pos6){
					$str_posts_pos6 = $this->element('frontend/s_post_display',array(
																	'data'		=> $a_posts_pos6,
																	'position'	=> 6,
																	'class'		=> '',
																	'run'		=> true,			//Bật/Tắt chức năng chạy
																	'direction'	=> 'up',			//up, down
																	'items'		=> 2,
																	'fx'        => 'scroll_continuous'			//"none", "scroll", "directscroll", "fade", "crossfade", "cover",  "uncover" hoặc "scroll_continuous".
																));
					Cache::write("post_pos6_$lang",$str_posts_pos6,'oneweb_view');
				}
				echo $str_posts_pos6;
			}

			//Tai lieu
			if(!empty($a_documents_pos1)){
				$str_documents_pos1 = Cache::read("document_pos1_$lang",'oneweb_view');
				if(!$str_documents_pos1){
					$str_documents_pos1 =  $this->element('frontend/s_document',array('data'=>$a_documents_pos1));
					Cache::write("document_pos1_$lang",$str_documents_pos1,'oneweb_view');
				}
				echo $str_documents_pos1;
			}

			//Tag cloudy
			if(!empty($a_tags_s)){
				echo $this->element('frontend/s_tag_cloudy',array('data'=>$a_tags_s));
			}

			//Banner partner
				if(!empty($a_partner_r)){
					$str_partner_r = Cache::read("partner_r_$lang",'oneweb_view');
					if(!$str_partner_r){
						$str_partner_r =  $this->element('frontend/s_banner',array(
																					'data'=>$a_partner_r,
																					'title'=>__('Đối tác',true),
																					'position'=>7
																				));
						Cache::write("partner_r_$lang",$str_partner_r,'oneweb_view');
					}
					echo $str_partner_r;
				}

				//Banner adv
				if(!empty($a_adv_r)){
					$str_adv_r = Cache::read("adv_r_$lang",'oneweb_view');
					if(!$str_adv_r){
						$str_adv_r =  $this->element('frontend/s_banner',array(
																					'data'=>$a_adv_r,
																					'title'=>__('Quảng cáo',true),
																					'position'=>5
																				));
						Cache::write("adv_r_$lang",$str_adv_r,'oneweb_view');
					}
					echo $str_adv_r;
				}
			echo $this->element('frontend/s_counter');
		?>
		</div>
	</div>
</div>
<!-- start frontend/both_column.ctp -->