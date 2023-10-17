<?php
	/*
	* @Description	: Ở đây phải cấu hình đúng link theo như yêu cầu đã comment, trg hợp cấu hình sai: web vẫn chạy, nhưng link ko được đẹp
	*
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/

	$config['link']=array(
		'vi'=>array(
				'information'=>array(
										'1'=>'gioi-thieu',		//position=>'slug'   Chú ý: slug này là slug cố định, muốn link đẹp thì slug này phải trùng với slug trong quản trị
										'2'=>'dieu-khoan',
										'3'=>'tuyen-dung',
										'4'=>'cach-thuc-mua-hang',
										'5'=>'huong-dan-thanh-toan',
										'6'=>'chinh-sach-giao-hang',
										'7'=>'quy-dinh-bao-hanh',
										'8'=>'chinh-sach-dai-ly',
										'9'=>'chinh-sach-ban-hang',
										'10'=>'chinh-sach-bao-mat-thong-tin',
										'11'=>'quy-trinh-xu-ly-khieu-nai',
										'12'=>'he-thong-ban-hang-24h',

									),
				'post'=>array(
						'1'=>'gioi-thieu',			//position => 'cate_slug co dinh'.... Chu y: muốn link đẹp thì slug này phải trùng với slug trong quản trị
						'2'=>'khuyen-mai',
//						'3'=>'chinh-sach-dai-ly',
//						'4'=>'chinh-sach-va-quy-dinh',
						'5'=>'tin-tuc',
				),
				'product_maker'=>'thuong-hieu',
				'document'=>'tailieu',
				'map'=>'bando',
				'faq'=>'hoidap',
				'video'=>'video',
				'gallery'=>'anh',
				'sitemap'=>'so-do',
				'contact'=>'lienhe',
				'member'=>'thanhvien',
				'poll'=>'bophieu'
			),
		'en'=>array(
				'information'=>array(
										'1'=>'about-us',			//position=>'slug'   Chú ý: slug này là slug cố định, muốn link đẹp thì slug này phải trùng với slug trong quản trị
										'2'=>'guide',
										'3'=>'recruitment',
										'4'=>'support',
										'5'=>'page1',
										'6'=>'page2'
									),
				'post'=>array(
						'1'=>'thongtin',			//position => 'cate_slug co dinh'.... Chu y: muốn link đẹp thì slug này phải trùng với slug trong quản trị
						'2'=>'xa-hoi',
						'3'=>'the-thao',
				),
				'product_maker'=>'man',
				'document'=>'document',
				'map'=>'map',
				'faq'=>'faqs',
				'video'=>'video',
				'gallery'=>'gallery',
				'sitemap'=>'sitemap',
				'contact'=>'contact-us',
				'member'=>'member',
				'boll'=>'poll'
			)
	);
?>