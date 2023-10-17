
<div class="col-md-3" role="navigation" id="post_sidebar">
	<div id="sidebar-left">
<?php
	if($controller !='pages'){


		// Danh mục toàn bộ ở các trang nội dung khác
		if ($controller != 'products' || $this->params['slug0'] =='khuyen-mai-hot' || $this->params['slug0'] =='san-pham-ban-chay' || $this->params['slug0'] =='san-pham-moi-nhat') {
			echo $this->element('frontend/s_product_category_other_page_sidebar');
		}

		// Danh mục con khi xem 1 sản phẩm trong danh mục đó
		if($controller == 'products' && $this->params['slug0'] !='khuyen-mai-hot' && $this->params['slug0'] !='san-pham-ban-chay' && $this->params['slug0'] !='san-pham-moi-nhat'){
			// Danh mục con
			if(!empty($a_child_direct_categories)){
				echo $this->element('frontend/s_product_category_sidebar',array(
						'title'=>$a_category_c['name'],
						'data'=>$a_child_direct_categories));
			}else{
				echo $this->element('frontend/s_product_category_sidebar',array(
						'title'=> (isset($a_category_parent_current['ProductCategory']))?$a_category_parent_current['ProductCategory']['name']:__('Danh mục sản phẩm'),
						'data'=>!empty($a_child_direct_categories_parent)?$a_child_direct_categories_parent:''));
				// echo $this->element('frontend/s_list_product',array(
				// 		'data'		=>$a_listsidebar,
				// 		'title'=>$a_category_c
				// 		));

			}

			// Thương hiệu
// 			if($controller == 'products'){		//Áp dụng khi cần active trang thái
// 				if($action=='maker'){
// 					$str_pro_maker = $this->element('frontend/s_product_maker',array('data'=>$a_product_makers_s));
// 				}elseif(empty($this->params['ext'])){
// 					$str_pro_maker = $this->element('frontend/s_product_maker2',array('data'=>$a_product_makers_list));
// 				}
// 			}else{
// 				$str_pro_maker = $this->element('frontend/s_product_maker',array('data'=>$a_product_makers_s));
// 			}
// 			echo @$str_pro_maker;
//
// 			// Lọc theo giá
// 			if ($controller == 'products' && $action == 'index' && $this->params['slug0'] != 'khuyen-mai-hot' && $this->params['slug0'] !='san-pham-ban-chay' && $this->params['slug0'] !='san-pham-moi-nhat')
// 			{
// 				echo $this->element('frontend/s_product_filter_price');
// 			}
		}


		//Danh muc post
		if(!empty($a_post_categories_s) && $controller == 'posts' ){
			$str_post_cate = Cache::read("post_cate_general_$lang",'oneweb_view');
			if(!$str_post_cate){
				$str_post_cate = $this->element('frontend/s_post_category');
				Cache::write("post_cate_general_$lang",$str_post_cate,'oneweb_view');
			}
			echo $str_post_cate;

		}
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
// 	if(!empty($a_videos_pos1)){
// 		$str_video_1 = Cache::read("video_1_$lang",'oneweb_view');
// 		if(!$str_video_1){
// 			$str_video_1 = $this->element('frontend/s_video',array('data'=>$a_videos_pos1));
// 			Cache::write("video_1_$lang",$str_video_1,'oneweb_view');
// 		}
// 		echo $str_video_1;
// 	}

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
																	'title'=>'',
																	'position'=>4,
																	'id_fixed'=>'sidebar_fixed'
																));
			Cache::write("adv_l_$lang",$str_adv_l,'oneweb_view');
		}
		echo $str_adv_l;
	}
	//Gallery
	if(!empty($a_galleries_pos1)){
		$str_gallery_1 = Cache::read("gallery_1_$lang",'oneweb_view');
		if(!$str_gallery_1){
			$str_gallery_1 = $this->element('frontend/s_gallery',array(
					'data'		=> $a_galleries_pos1,
					'position'	=> 1,
					'class'		=> '',
					'w'			=> 275,
					'zc'		=> 2,
			));
			Cache::write("gallery_1_$lang",$str_gallery_1,'oneweb_view');
		}
		echo $str_gallery_1;
	}
	//echo $this->element('frontend/s_like_box');
	}
?>
	</div>
	<?php
			// bài viết nổi bật
			if(!empty($featured_post_c) && $controller == 'posts') {
				$str_featured_post_c = Cache::read("featured_post_c_$lang",'oneweb_view');
				if(!$str_featured_post_c){
					$str_featured_post_c = $this->element('frontend/s_post', array('data'=>$featured_post_c));
					Cache::write("featured_post_c$lang",$str_featured_post_c,'oneweb_view');
				}
				echo $str_featured_post_c;
			}
	?>
</div>
<div class="col-xs-12 col-sm-12 col-md-9 m-b-15" id="post_content">
	<?php echo $content_for_layout;?>
</div>
<script>
    const featuredPosts = document.querySelector('#columnSwap #post_sidebar .s_post');
    if (featuredPosts) {
		const imagesFeaturedPosts = featuredPosts.querySelectorAll('a img');

        imagesFeaturedPosts.forEach(imgFeaturedPosts => {
			let srcFeaturedPosts = imgFeaturedPosts.getAttribute('src');
			console.log(srcFeaturedPosts);
            if (!srcFeaturedPosts || srcFeaturedPosts.trim() === '') {
                imgFeaturedPosts.setAttribute('src','https://hstatic.net/620/1000063620/10/2016/4-16/4g-la-gi.jpg');
                imgFeaturedPosts.style.objectFit = 'cover';
            }
            imgFeaturedPosts.onerror = function() {
                imgFeaturedPosts.setAttribute('src','https://hstatic.net/620/1000063620/10/2016/4-16/4g-la-gi.jpg');
                imgFeaturedPosts.style.objectFit = 'cover';
            }
        });
    }
</script>