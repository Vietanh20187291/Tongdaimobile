<div class="col-xs-12 col-md-8 col-lg-8">
	<?php echo $content_for_layout;?>
</div>
<div class="col-lg-4 col-xs-12 col-md-4  sidebar" role="navigation">
<?php
	echo $this->element('frontend/s_box_gg_translate');
	//Ho tro truc tuyen
	if(!empty($a_support_s)){
		$str_support = Cache::read("support_$lang",'oneweb_view');
		if(!$str_support){
			$str_support = $this->element('frontend/s_support');
			$str_support = $this->element('frontend/s_support');
			Cache::write("support_$lang",$str_support,'oneweb_view');
		}
		echo $str_support;
	}
	//Tim kiem
// 	if(!empty($oneweb_search['product']['enable']) || !empty($oneweb_search['post']['enable'])){
// 		$str_search = Cache::read("search_$lang",'oneweb_view');
// 		if(!$str_search){
// 			$str_search = $this->element('frontend/s_search');
// 			Cache::write("search_$lang",$str_search,'oneweb_view');
// 		}
// 		echo $str_search;
// 	}

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
	if(!empty($a_advertisements['col_right'])) echo $this->element('frontend/s_advertisement');
	//Bai viet hien thi tuy chon
	if(!empty($a_posts_pos6)){
		$str_posts_pos6 = Cache::read("post_pos6_$lang",'oneweb_view');
		if(!$str_posts_pos6){
			$str_posts_pos6 = $this->element('frontend/s_post_display_run',array(
															'data'		=> $a_posts_pos6,
															'position'	=> 6,
															'class'		=> '',
															'run'		=> true,			//Bật/Tắt chức năng chạy
															'direction'	=> 'left',			//up, down
															'items'		=> 1,
															'fx'        => 'scroll'			//"none", "scroll", "directscroll", "fade", "crossfade", "cover",  "uncover" hoặc "scroll_continuous".
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

	//counter
//	if(!empty($a_counters_s)) echo $this->element('frontend/s_counter',array('data'=>$a_counters_s));
?>
</div>