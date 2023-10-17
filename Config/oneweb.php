<?php
	include_once 'oneweb_path.php';

	/** Kích thước ảnh **/
	$config['Product']['size']['product']				=	array(600,600);
	$config['Product']['size']['category']				=	array(190,140);
	$config['Product']['size']['category_banner']		=	array(400,600);
	$config['Product']['size']['icon']				=	array(90,90);

	$config['Product']['size']['maker']					=	array(100,100);
	$config['Product']['size']['maker_banner']			=	array(580,120);

	$config['Post']['size']['post']						=	array(540,334);
	$config['Post']['size']['category']					=	array(120,100);
	$config['Post']['size']['category_banner']			=	array(600,120);

	$config['Media']['size']['gallery']					=	array(600,370);

	$config['Banner']['size']	= array(
										'1' 	=>	array(1140,120), 						//'Banner',
										'2' 	=>	array(1400,663),//455 						//'Slideshow',
										'3' 	=>	array(375,115), 							//'Đối tac chân web',
										'4' 	=>	array(270,450), 						//'Quảng cáo cột trái',
										'5' 	=>	array(200,200), 						//'Quảng cáo cột phải',
										'6' 	=>	array(180,'n'), 						//'Đối tác cột trái',
										'7' 	=>	array(180,'n'), 						//'Đối tác cột phải',
										'8' 	=>	array(1000, 70), 						// Quảng cáo dưới trang chủ
										'9'		=>	array(95,'n'), 						//'Bên trái web',
										'10'	=>	array(95,'n'), 						//'Bên phải web'
										'11' 	=>	array(375,115),//218 							//'Quảng cáo dưới slideshow',
										'12' 	=>	array(580,720), //popup quảng cáo
									);


/////////////////// CẤU HÌNH GIAO DIỆN ////////////////////////////

	/******* Cau hinh trang web ********/
	$config['Web']['layout']			= 'template1';
	$config['Web']['column_left']		= true;
	$config['Web']['column_right']		= false;
	$config['Web']['logo']				= 'logo (1).jpg';		//Tên file logo đặt tại thư mục img
	$config['Web']['social']			= true;
	$config['Web']['nav_footer']		= false;
	$config['Web']['drop_down'] 		= false;			//Hiệu ứng drop down ở sidebar
	$config['Web']['notifice']			= true;				//Thông báo khi có đơn hàng hoặc liên hệ mới
	$config['Web']['tooltip_effect']	= 'none';			//Hieu ung Tooltip sản phẩm:  {none, tooltip, bt, tb, lr, rl}
	$config['Web']['icon_discount']		= false;			//Hiển thị icon thể hiện sản phẩm giảm giá
	$config['Web']['icon_promotion']	= false;			//Hiển thị icon thể hiện sản phẩm khuyến mãi
	$config['Web']['right_click']		= true;				//Cho phép hay ko cho phép click chuột phải
	$config['Web']['comment']			= true;
	$config['Web']['help']				= 'http://help.url.net.vn/';				//Địa chỉ viết hướng dẫn

/////////////////// CẤU HÌNH TÍNH NĂNG ////////////////////////////

	/******* Cau hinh SEO ********/
	$config['Seo']		= true;		//Seo toàn trang


	/******* Sản phẩm ********/
	$config['Product']['enable']		= true;
	$config['Product']['code']		= true;			//Mã sản phẩm
	$config['Product']['images']	= true;			//Nhiều ảnh
	$config['Product']['quantity']	= true;
	$config['Product']['unit'] 		= array(
										'Cái'	=>'Cái',
										'Chiếc'	=>'Chiếc',
										'Bộ'	=>'Bộ',
										'Con'	=>'Con',
										'Túi'	=>'Túi',
										'Hộp'	=>'Hộp',
										'Kg'    =>'Kg',
										'Yến'	=>'Yến',
										'Tạ'	=>'Tạ',
										'Tấn'	=>'Tấn',
										'Bức'	=>'Bức',
										'Thùng'	=>'Thùng'
									);
	$config['Product']['price']			= true;
	$config['Product']['warranty']		= true;
	$config['Product']['summary']		= true;				//Mô tả tóm tắt bên phải sp
	$config['Product']['comment']		= true;
	$config['Product']['related']		= true;				//Phụ kiện liên quan, được phép chọn trọng quản trị
	$config['Product']['tax']			= false;
	$config['Product']['rate']			= false;
	$config['Product']['currency']		= true;
	$config['Product']['discount']		= true;
	$config['Product']['promotion']		= true;
	$config['Product']['option']		= false;
	$config['Product']['tag']			= true;
	$config['Product']['atribute']			= false; //color, size
	$config['Product']['order']			= true;				//Bật tắt đặt hàng
	$config['Product']['cart_button']	= true;				//Bặt tắt nút đặt hàng bên ngoài chi tiết
	$config['Product']['comment_face']	= false;				//Bặt tắt comment_faceboo
	$config['Product']['comment_google']	= false;				//Bặt tắt comment_google
		//Chưa sdung
// 		$config['Product']['img_zoom'] 		= true;				//Hiệu ứng di chuột vào zoom ảnh (mục chi tiết sp)
// 		$config['Product']['img_box'] 		= true;				//Hiệu ứng click vào ảnh hiển thi ra box chua nhiều ảnh để view (muc chi tiet sp)
		//Chưa sdung

		$config['Product']['pro_child'] 	= true;				//Bật tắt hiển thị toàn bộ sản phẩm của các danh mục con khi vào danh mục cha

	//Danh muc san pham
	$config['Product']['multi_box']				= false;			//Hỗ trợ nhiều box danh mục hiển thị ở sidebar
	$config['Product']['category_banner']		= true;			//Anh banner cho danh muc
	$config['Product']['category_image']		= true;			//Anh cho danh muc
	$config['Product']['category_description']	= true;	//Mo ta cho danh muc
//	$config['Product']['property']=array(				//Thông số kỹ thuật cho từng loại sản phẩm
//											'basic'=>'Cơ bản',
//											'mobile'=>'Điện thoại di động',
//											'laptop'=>'Máy tính xách tay',
//											'desktop'=>'Máy tính để bàn'
//											);

	//Hang san xuat
	$config['Product']['maker']				= true;
	$config['Product']['maker_image']		= true;
	$config['Product']['maker_banner']		= false;
	$config['Product']['maker_description']	= false;

	//Cau hinh vi tri hien thi cua san pham
	$config['Product']['display']=array(
										// '1'=>'Sản phẩm khuyến mại',
										'2'=>'Sản phẩm khuyến mại',
										'3'=>'Sản phẩm bán chạy',				//Home 3
// 										'4'=>'Sản phẩm quan tâm',			//Home run
//										'5'=>'Sản phẩm Hot 1',
//										'6'=>'Sản phẩm hot 2',
// 										'7'=>'Sản phẩm Hot',				//sidebar
// 										'8'=>'Sản phẩm hot 4'				//sidebar
									);


	/******* Bai viet ********/
	$config['Post']['enable']				= true;
	$config['Post']['category_banner']		= false;					//Anh banner cho danh muc
	$config['Post']['category_image']		= false;					//Anh cho danh muc		-- Chưa sử dụng
	$config['Post']['category_description']	= true;					//Mo ta cho danh muc
	$config['Post']['tag']					= false;					//Tag bài viết
	$config['Post']['comment'] 				= true;    				// comment bai viet
	$config['Post']['comment_face']			= false;				//Bặt tắt comment_facebook
	$config['Post']['comment_google']		= false;				//Bặt tắt comment_google
	$config['Post']['rate'] 				= true;
	$config['Post']['post_child'] 			= true;				//Bật tắt hiển thị toàn bộ bài viết của các danh mục con khi vào danh mục cha
	$config['Post']['display']				= array(					//Cau hinh vi tri hien thi
												'1'=>'Tin trang chủ',						//Home
// 												'2'=>'Hot news',				//
//												'3'=>'Tin 1',						//
//												'4'=>'Tin 2',						//
//  												'5'=>'Popular posts',						//Sidebar
// 												'6'=>'Lastest news',				//Sidebar
											);

	$config['Post']['category_position']	= array(						//Vi tri hien thi
													'1'=>'Menu vị trí 1',
													'2'=>'Menu vị trí 2',
													'3'=>'Menu vị trí 3',		//Chú ý: khi thêm vị trí mới, fai thêm cả trong file "url_link.php", nếu ko sitemap xml chạy sai
													'4'=>'Menu vị trí 4'		//Chú ý: khi thêm vị trí mới, fai thêm cả trong file "url_link.php", nếu ko sitemap xml chạy sai
												);
	/******* Tìm kiếm ******/
	$config['Search']['product']['enable'] 		= true;							//Module tìm kiếm sp
	$config['Search']['product']['maker']		= true;
	$config['Search']['product']['category']	= true;
	$config['Search']['product']['price']		= array(						//Khoảng giá tìm kiếm
														'0-1000000',
														'1000000-2000000',
														'2000000-5000000',
														'5000000-10000000',
														'1000000-20000000',
													);

	$config['Search']['post']['enable'] 		= true;										//Module tìm kiếm bài viết
	$config['Search']['post']['category']		= true;


	/******* Media ********/
	$config['Media']['video']['enable']			= true;
	$config['Media']['video']['comment']	= false;
	$config['Media']['video']['comment_face']	= false;				//Bặt tắt comment_facebook
	$config['Media']['video']['comment_google']	= false;				//Bặt tắt comment_google
	$config['Media']['video']['rate']		= false;
	$config['Media']['video']['display']	= array(
														'1'=>'Hiển thị trang chủ',		//Sidebar
//														'2'=>'Vị trí hiển thị 2',
													);
	$config['Media']['document']['enable']		= false;
		$config['Media']['document']['display']	= array(
														'1'=>'Vị trí hiển thị 1',		//Sidebar
//														'2'=>'Vị trí hiển thị 2',
													);
	$config['Media']['gallery']['enable']		= false;
		$config['Media']['gallery']['comment']	= false;
		$config['Media']['gallery']['comment_face']	= false; //Bat tat comment_face
		$config['Media']['gallery']['comment_google']	= false; //Bat tat comment_google
		$config['Media']['gallery']['rate']		= false;
		$config['Media']['gallery']['display']	= array(
														'1'=>'Hiển thị cột trái',		//Sidebar
//														'2'=>'Vị trí hiển thị 2',
													);

	//Quảng cáo
	$config['Advertisement']['enable']		= true;
	$config['Advertisement']['position'] = array(
									'meta_header'=>'Thẻ meta header',
// 									'adv_home0'=>'QC đầu trang chủ',
// 									'adv_home1'=>'QC home 1',
// 									'adv_home2'=>'QC home 2',
// 									'adv_home3'=>'QC home 3',
// 									'adv_col_right1'=>'QC sidebar 1',
// 									'adv_col_right'=>'QC sidebar 2',
// 									'adv_content'=>'QC đầu bài viết',
// 									'adv_content_center'=>'QC giữa bài viết',
// 									'adv_content_bottom'=>'QC cuối bài viết',
// 									'adv_bottom_left'=>'QC góc dưới trái',
									'google_analytics'=>'Mã google analytics',
			);

	/******* Bỏ phiếu ********/
	$config['Poll']['enable']			= false;

	/******* Information ********/
	$config['Information']['enable']	= true;
	$config['Information']['position']	= array(						//Vi tri hien thi:... Chú ý: cấu hình ở đây xong phải cấu hình ỏ file url_link.php
											'1'=>'Menu vi tri 1',
											'2'=>'Menu vi tri 2',
											'3'=>'Menu vi tri 3',
											'4'=>'Menu top 1',
											'5'=>'Menu top 2',
											'6'=>'Menu top 3',
											'7'=>'Menu top 4',
											'8'=>'Menu vi tri 4',
											'9'=>'Menu top 5',
										);


	/******* Faq ********/
	$config['Faq']['enable']			= false;

	/******* Newsletter ***/
	$config['Newsletter']['enable']		= false;

	/******* Liên hệ của khách hàng ********/
	$config['Contact']['enable']		= true;

	/******* Banner ********/
	$config['Banner']['enable']			= true;					//Banner động
	$config['Banner']['page']			= array(				//Cau hinh trang hien thi
											'1'=>'Tất cả',
											'2'=>'Trang chủ',
// 											'3'=>'Danh mục sản phẩm',
// 											'18'=>'Hãng sản xuất',
// 											'14'=>'Chi tiết sản phẩm',
// 											'7'=>'Tìm kiếm sản phẩm',
// 											'4'=>'Danh mục bài viết',
// 											'15'=>'Chi tiết bài viết',
// 											'8'=>'Tìm kiếm bài viết',
// 											'6'=>'Các trang thông tin',
// 											'10'=>'Trang hiển thị Tag',
// 											'11'=>'Video',
// 											'16'=>'Chi tiết video',
// 											'12'=>'Hình ảnh',
// 											'17'=>'Chi tiết hình ảnh',
// 											'13'=>'Tài liệu',
// 											'9'=>'Faqs',
// 											'5'=>'Liên hệ'
										);
	$config['Banner']['display']	= array(					//Vị trí hiển thị
// 											'1'=>'Banner',
											'2'=>'Slideshow',
											'3'=>'Quảng cáo dưới sp khuyến mại',
											'4'=>'Quảng cáo cột trái',
 											'5'=>'Logo banner dưới menu',
// 											'6'=>'Đối tác cột trái',
// 											'7'=>'Đối tác cột phải',
											'8'=>'Quảng cáo dưới trang chủ',
											'9'=>'Bên trái web',
											'10'=>'Bên phải web',
											'11'=>'Quảng cáo dưới slideshow',
											'12'=>'Popup'
										);



	/******* Hỗ trợ trực tuyến ********/
	$config['Support']['enable']	= true;
	$config['Support']['livechat'] 	= false;

	/*******  Ban do  *****/
	$config['Map']['enable']		= true;		//Ban do
	$config['Map']['contact']		= true;		//Ban do o muc lien he


	//Counter
	$config['Counter']['enable'] 		= true;
		$config['Counter']['online']	= true;
		$config['Counter']['yesterday'] = true;
		$config['Counter']['today'] 	= true;
		$config['Counter']['week'] 		= false;
		$config['Counter']['month'] 	= false;
		$config['Counter']['year'] 		= false;
		$config['Counter']['total'] 	= true;
	//Thanh viên
	$config['Member']['enable'] 	= false;
	$config['Member']['message']    = false;

	/******* Sitemapxml	********/
	$config['Sitemap']['xml'] 		= true;
	$config['Sitemap']['html'] 		= true;

	/******* Da ngon ngu *****/
	$config['Language']		= array(
								'vi'=>'Tiếng Việt',
// 								'en'=>'English',
//								'ja'=>'Japanese',
//								'ko'=>'Korean'
//								'ch'=>'China'
							);

$config['ActivePost']['so_luong'] 		= true;



	/******************************************************************************************/
	/******************************************************************************************/
	/********************** Tắt các module con nếu module chính tắt ***************************/
	/******************************************************************************************/
	/******************************************************************************************/

	if(empty($config['Product']['enable'])){
		$config['Product']['comment']		= false;
		$config['Product']['tax']			= false;
		$config['Product']['currency']		= false;
		$config['Product']['tag']			= false;
		$config['Product']['order']			= false;								//Bật tắt đặt hàng
		$config['Product']['display']		= array();
		$config['Product']['maker']			= false;
		$config['Search']['product']['enable'] 	= false;							//Module tìm kiếm sp
	}else{
		if(empty($config['Search']['product']['enable'])){
			$config['Search']['product']['maker']		= false;
			$config['Search']['product']['category']	= false;
			$config['Search']['product']['price']		= array();
		}
	}

	if(empty($config['Product']['order'])) $config['Product']['cart_button'] = false;

	if(empty($config['Post']['enable'])){
		$config['Post']['tag']					= false;							//Tag bài viết
		$config['Post']['comment'] 				= false;    						// comment bai viet
		$config['Search']['post']['enable'] 	= false;							//Module tìm kiếm bài viết
	}else{
		if(empty($config['Search']['post']['enable'])){
			$config['Search']['post']['category']	= false;
		}
	}

	if(empty($config['Media']['video']['enable'])){
		$config['Media']['video']['comment']	= false;
	}
	if(empty($config['Media']['gallery']['enable'])){
		$config['Media']['gallery']['comment']	= false;
	}

	if(empty($config['Web']['comment'])){
		$config['Product']['comment']			= false;
		$config['Post']['comment'] 				= false;
		$config['Media']['video']['comment']	= false;
		$config['Media']['gallery']['comment']	= false;
	}elseif(empty($config['Product']['comment']) && empty($config['Post']['comment']) && empty($config['Media']['video']['comment']) && empty($config['Media']['gallery']['comment'])){
		$config['Web']['comment'] = false;
	}
//Phân quyền quản trị
$config['User']['display']   	= array(
    '1'=>'Xem thành viên',
    '2'=>'Sửa thành viên',
    '3'=>'Xem dự án',
);
?>
