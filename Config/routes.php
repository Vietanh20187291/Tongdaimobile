<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::parseExtensions('html');

	/****************************************************************************/
	/****************************************************************************/
	/**************************      Backend       *****************************/
	/****************************************************************************/
	/****************************************************************************/
	// kích hoạt bài viết 
	Router::connect('/kichhoatbaiviet',array('controller'=>'activation','action'=>'kichhoatbaiviet','ext'=>'html'));

	//Đăng nhập
	Router::connect('/a',array('controller'=>'users','action'=>'login','admin'=>true));
	//User
	Router::connect('/forget',array('controller'=>'users','action'=>'forgetPassword','admin'=>true));
	Router::connect('/user-reset-password/:user_id/:token',array('controller'=>'users','action'=>'confirmResetPassword','admin'=>true),array('pass'=>array('user_id','token')));

	//Bảng điều khiển
	Router::connect('/URL.vn',array('controller'=>'pages','action'=>'index','admin'=>true));

	Router::connect('/URL.vn/product-attribute/:controller',array('plugin'=>'AdvancedProductAttributes', 'action'=>'index','admin'=>true));
	Router::connect('/URL.vn/product-attribute/:controller/:action/*',array('plugin'=>'AdvancedProductAttributes','admin'=>true));

	Router::connect('/URL.vn/:controller',array('action'=>'index','admin'=>true));
	Router::connect('/URL.vn/:controller/:action/*',array('admin'=>true));

	/****************************************************************************/
	/****************************************************************************/
	/**************************      Frontend       *****************************/
	/****************************************************************************/
	/****************************************************************************/

	include_once 'oneweb_link.php';
	$a_languages = $config['link'];
	$lang_default = 'vi';

	//Sắp xếp lại thứ tự ngôn ngữ
	$tmp = array();
	foreach ($a_languages as $key=>$val){
		if($key!=$lang_default) $tmp[$key]=$val;
	}
	$tmp[$lang_default] = $a_languages[$lang_default];
	$a_languages = $tmp;

	foreach($a_languages as $key=>$val){
		$prefix = ($key==$lang_default)?'/':'/'.$key;
		$lang = $key;

		//****************** HOME *********************//
		Router::connect($prefix, array('controller' => 'pages', 'action' => 'home','lang'=>$lang));

		if($key!=$lang_default) $prefix.='/';



		//***************** INFORMATION ******************//
		foreach($val['information'] as $key2=>$val2){
			Router::connect($prefix.$val2,array('controller'=>'information','action'=>'view','lang'=>$lang,'position'=>$key2,'slug'=>$val2),array('pass'=>array('slug','position'),'position' => '[0-9]+'));
			Router::connect($prefix.$val2.'/:slug',array('controller'=>'information','action'=>'view','lang'=>$lang,'position'=>$key2),array('pass'=>array('slug','position'),'position' => '[0-9]+'));
		}
		//Trg hop slug_fix ko trung voi trong quan tri
		Router::connect($prefix.'info_:position'.'/:slug',array('controller'=>'information','action'=>'view','lang'=>$lang),array('pass'=>array('slug','position'),'position' => '[0-9]+'));

		//*******************MEMBER*********************//
		Router::connect($prefix.$val['member'].'/member', array('controller'=>'members', 'action'=>'index', 'lang'=>$key));
		Router::connect($prefix.$val['member'].'/logout',array('controller'=>'members','action'=>'logout','lang'=>$key));
		Router::connect($prefix.$val['member'].'/login', array('controller'=>'members', 'action'=>'login', 'lang'=>$key));
		Router::connect($prefix.$val['member'].'/registration', array('controller'=>'members', 'action'=>'registration', 'lang'=>$key));
		Router::connect($prefix.$val['member'].'/forget-password', array('controller'=>'members', 'action'=>'forgetPassword', 'lang'=>$key));
		Router::connect($prefix.$val['member'].'/edit-account', array('controller'=>'members', 'action'=>'editAccount', 'lang'=>$key));
		Router::connect($prefix.$val['member'].'/change-address', array('controller'=>'members', 'action'=>'changeAddress', 'lang'=>$key));
		Router::connect($prefix.$val['member'].'/change-password', array('controller'=>'members', 'action'=>'changePassword', 'lang'=>$key));
		Router::connect($prefix.$val['member'].'/history-payment', array('controller'=>'members', 'action'=>'historyPayment', 'lang'=>$key));
		Router::connect($prefix.$val['member'].'/detail-history-payment/*', array('controller'=>'members', 'action'=>'detailHistoryPayment', 'lang'=>$key));
		Router::connect($prefix.$val['member'].'/reset-password/:member_id/:token',array('controller'=>'members','action'=>'confirmResetPassword','lang'=>$key),array('pass'=>array('member_id','token')));
		Router::connect($prefix.$val['member'].'/management-notice', array('controller'=>'members', 'action'=>'managementNotice', 'lang'=>$key));
		Router::connect($prefix.$val['member'].'/detail-notice/*', array('controller'=>'members', 'action'=>'detailNotice', 'lang'=>$key));

		//***************** DOCUMENT ******************//
		Router::connect($prefix.$val['document'].'/:slug_cate/page-:page',array('controller'=>'documents','action'=>'view','lang'=>$lang),array('pass'=>array('slug_cate'),'page' => '[0-9]+'));
		Router::connect($prefix.$val['document'].'/page-:page',array('controller'=>'documents','action'=>'index','lang'=>$key),array('page' => '[0-9]+'));
		Router::connect($prefix.$val['document'].'/:slug_cate/:slug-:id',array('controller'=>'documents','action'=>'download','lang'=>$lang),array('pass'=>array('id'), 'id' => '[0-9]+'));
		Router::connect($prefix.$val['document'].'/:slug_cate',array('controller'=>'documents','action'=>'view','lang'=>$lang),array('pass'=>array('slug_cate')));
		Router::connect($prefix.$val['document'],array('controller'=>'documents','action'=>'index','lang'=>$key));


		//***************** VIDEO ******************//
		Router::connect($prefix.$val['video'].'/:slug0/sort_:sort-:direction/page-:page',array('controller'=>'videos','action'=>'index','lang'=>$key),array('pass'=>array('slug0'),'page'=>'[0-9]+'));
		Router::connect($prefix.$val['video'].'/:slug0/sort_:sort-:direction',array('controller'=>'videos','action'=>'index','lang'=>$key),array('pass'=>array('slug0')));
		Router::connect($prefix.$val['video'].'/:slug0/page-:page',array('controller'=>'videos','action'=>'index','lang'=>$key),array('pass'=>array('slug0'),'page'=>'[0-9]+'));
		Router::connect($prefix.$val['video'].'/:slug0/:slug1',array('controller'=>'videos','action'=>'index','lang'=>$key),array('pass'=>array('slug1')));
		Router::connect($prefix.$val['video'].'/:slug0',array('controller'=>'videos','action'=>'index','lang'=>$key),array('pass'=>array('slug0')));
		Router::connect($prefix.$val['video'],array('controller'=>'videos','action'=>'index','lang'=>$key));


		//***************** GALLERY ******************//
		Router::connect($prefix.$val['gallery'].'/:slug0/sort_:sort-:direction/page-:page',array('controller'=>'galleries','action'=>'index','lang'=>$key),array('pass'=>array('slug0'),'page'=>'[0-9]+'));
		Router::connect($prefix.$val['gallery'].'/:slug0/sort_:sort-:direction',array('controller'=>'galleries','action'=>'index','lang'=>$key),array('pass'=>array('slug0')));
		Router::connect($prefix.$val['gallery'].'/:slug0/page-:page',array('controller'=>'galleries','action'=>'index','lang'=>$key),array('pass'=>array('slug0'),'page'=>'[0-9]+'));
		Router::connect($prefix.$val['gallery'].'/:slug0/:slug1',array('controller'=>'galleries','action'=>'index','lang'=>$key),array('pass'=>array('slug1')));
		Router::connect($prefix.$val['gallery'].'/:slug0',array('controller'=>'galleries','action'=>'index','lang'=>$key),array('pass'=>array('slug0')));
		Router::connect($prefix.$val['gallery'],array('controller'=>'galleries','action'=>'index','lang'=>$key));


		//***************** SITEMAP ******************//
		Router::connect($prefix.$val['sitemap'],array('controller'=>'sitemaps','action'=>'html','lang'=>$key));


		//***************** FAQs ******************//
		Router::connect($prefix.$val['faq'],array('controller'=>'faqs','action'=>'view','lang'=>$key));

		//***************** Binh chon ******************//
		Router::connect('/ajax'.$prefix.'p-o-l-l',array('controller'=>'polls','action'=>'ajaxAddPoll','lang'=>$key));
		Router::connect('/ajax'.$prefix.'p-o-l-l-r-e-s-u-l-t',array('controller'=>'polls','action'=>'ajaxResultPoll','lang'=>$key));


		//***************** CONTACT ******************//
		Router::connect($prefix.$val['contact'],array('controller'=>'contacts','action'=>'index','lang'=>$key));
		Router::connect($prefix.$val['contact'].'/request_support',array('controller'=>'contacts','action'=>'request_support','lang'=>$key));

		Router::connect($prefix.'frm-dang-ky-tu-van',array('controller'=>'contact_forms','action'=>'registv','lang'=>$key));
		Router::connect($prefix.'frm-nhan-qua-tang',array('controller'=>'contact_forms','action'=>'gift','lang'=>$key));
		Router::connect($prefix.'frm-tham-gia-su-kien',array('controller'=>'contact_forms','action'=>'event','lang'=>$key));

		//***************** CURRENCY ******************//
		Router::connect($prefix.'currency',array('controller'=>'pages','action'=>'currency','lang'=>$key));

		//***************** COUNTER ******************//
		Router::connect('/ajax'.$prefix.'c-o-u-n-t-e-r',array('controller'=>'pages','action'=>'ajaxCounter','lang'=>$lang));

		//****************** COMMENT *********************//
		Router::connect('/ajax'.$prefix.'l-i-s-t-c-o-m-m-e-n-t',array('controller'=>'comments','action'=>'ajaxComment','lang'=>$lang));
		Router::connect('/ajax'.$prefix.'a-d-d-c-o-m-m-e-n-t',array('controller'=>'comments','action'=>'ajaxAddComment','lang'=>$lang));
		Router::connect('/ajax'.$prefix.'a-n-s-w-e-r-c-o-m-m-e-n-t',array('controller'=>'comments','action'=>'ajaxAnswerComment','lang'=>$lang));
		Router::connect('/ajax'.$prefix.'l-i-k-e-c-o-m-m-e-n-t',array('controller'=>'comments','action'=>'ajaxLike','lang'=>$lang));
		Router::connect('/ajax'.$prefix.'c-a-p-t-c-h-a-c-o-m-m-e-n-t',array('controller'=>'comments','action'=>'captchaImage','lang'=>$lang));

		Router::connect('/ajax'.$prefix.'g-e-t-r-a-t-e-c-o-m-m-e-n-t',array('controller'=>'comments','action'=>'ajaxGetRateComment','lang'=>$lang));

		//****************** RATE *********************//
		Router::connect($prefix.'s-t-a-r-r-a-t-e',array('controller'=>'pages','action'=>'ajaxStarRate','lang'=>$lang));

		//******************* NEWSLETTER ***************//
		Router::connect('/ajax'.$prefix.'n-e-w-l-e-t-t-e-r', array('controller' => 'newsletters', 'action' => 'ajaxSaveEmail','lang'=>$lang));

		//****************** FILTER *******************//
		Router::connect($prefix.'result-product/sort_:sort-:direction/page-:page',array('controller'=>'filters','action'=>'product','lang'=>$lang));
		Router::connect($prefix.'result-product/page-:page',array('controller'=>'filters','action'=>'product','lang'=>$lang));
		Router::connect($prefix.'result-product/sort_:sort-:direction',array('controller'=>'filters','action'=>'product','lang'=>$lang));
		Router::connect($prefix.'result-product',array('controller'=>'filters','action'=>'product','lang'=>$lang));

		Router::connect($prefix.'result-post/sort_:sort-:direction/page-:page',array('controller'=>'filters','action'=>'post','lang'=>$lang));
		Router::connect($prefix.'result-post/page-:page',array('controller'=>'filters','action'=>'post','lang'=>$lang));
		Router::connect($prefix.'result-post/sort_:sort-:direction',array('controller'=>'filters','action'=>'post','lang'=>$lang));
		Router::connect($prefix.'result-post',array('controller'=>'filters','action'=>'post','lang'=>$lang));

		Router::connect($prefix.'result-search',array('controller'=>'filters','action'=>'search','lang'=>$lang));

		Router::connect('/ajax'.$prefix.'get-search', array('controller' => 'filters', 'action' => 'ajaxGetSearch', 'lang' => $lang));
		//***************** BÀI VIẾT ******************//
		$n = 7;			//Hỗ trợ write n cấp danh mục
		//Danh sach, chi tiết bài viết theo danh muc (danh sách và danh mục phân biệt bằng có .html và ko có .html)
		foreach($val['post'] as $key2=>$val2){
			//Danh sách sản phẩm khi sắp xếp và phân trang
			for($i=$n;$i>=0;$i--){
				$slug = $val2;
				for($j=0;$j<$i;$j++) $slug.='/'.':slug'.$j;
				//Start: Trường hợp đb, khi slug mục gốc trùng với slug fix cứng
				$slug2 = $val2;
				for($j=1;$j<$i;$j++) $slug2.='/'.':slug'.$j;
				Router::connect($prefix.$slug2.'/sort_:sort-:direction/page-:page',array('controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2,'slug0'=>$val2),array('pass'=>array('slug'.($i-1)),'page' => '[0-9]+'));
				//End.
				Router::connect($prefix.$slug.'/sort_:sort-:direction/page-:page',array('controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2),array('pass'=>array('slug'.($i-1)),'page' => '[0-9]+'));
			}

			//Danh sách sản phẩm khi sắp xếp
			for($i=$n;$i>=0;$i--){
				$slug = $val2;
				for($j=0;$j<$i;$j++) $slug.='/'.':slug'.$j;
				//Start: Trường hợp đb, khi slug mục gốc trùng với slug fix cứng
				$slug2 = $val2;
				for($j=1;$j<$i;$j++) $slug2.='/'.':slug'.$j;
				Router::connect($prefix.$slug2.'/sort_:sort-:direction',array('controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2,'slug0'=>$val2),array('pass'=>array('slug'.($i-1)),'page' => '[0-9]+'));
				//End.
				Router::connect($prefix.$slug.'/sort_:sort-:direction',array('controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2),array('pass'=>array('slug'.($i-1))));
			}

			for($i=$n;$i>=0;$i--){		//Danh sách phân trang
				$slug = $val2;
				for($j=0;$j<$i;$j++) $slug.='/'.':slug'.$j;

				//Start: Trường hợp đb, khi slug mục gốc trùng với slug fix cứng
				$slug2 = $val2;
				for($j=1;$j<$i;$j++) $slug2.='/'.':slug'.$j;
				Router::connect($prefix.$slug2.'/page-:page',array('controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2,'slug0'=>$val2),array('pass'=>array('slug'.($i-1)),'page' => '[0-9]+'));
				//End.

				Router::connect($prefix.$slug.'/page-:page',array('controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2),array('pass'=>array('slug'.($i-1)),'page' => '[0-9]+'));
				//AMP
				// Router::connect('/amp'.$prefix.$slug2.'/page-:page',array('plugin'=>'Amp','controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2,'slug0'=>$val2),array('pass'=>array('slug'.($i-1)),'page' => '[0-9]+'));
				// Router::connect('/amp'.$prefix.$slug.'/page-:page',array('plugin'=>'Amp','controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2),array('pass'=>array('slug'.($i-1)),'page' => '[0-9]+'));
			}

			for($i=$n;$i>=0;$i--){		//Danh sách bài viết
				$slug = $val2;
				for($j=0;$j<$i;$j++) $slug.='/'.':slug'.$j;

				//Start: Trường hợp đb, khi slug mục gốc trùng với slug fix cứng
				$slug2 = $val2;
				for($j=1;$j<$i;$j++) $slug2.='/'.':slug'.$j;
// 				print_r($prefix);die;
				Router::connect($prefix.$slug2,array('controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2,'slug0'=>$val2),array('pass'=>array('slug'.($i-1))));
				//End.

				Router::connect($prefix.$slug,array('controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2),array('pass'=>array('slug'.($i-1))));

				//amp
				// Router::connect('/amp'.$prefix.$slug2,array('plugin'=>'Amp', 'controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2,'slug0'=>$val2),array('pass'=>array('slug'.($i-1))));
				// Router::connect('/amp'.$prefix.$slug,array('plugin'=>'Amp', 'controller'=>'posts','action'=>'index','lang'=>$lang,'position'=>$key2),array('pass'=>array('slug'.($i-1))));
			}
		}

		//****************** SẢN PHẨM *********************//

		//Giỏ hàng
		Router::connect('/ajax'.$prefix.'s-h-o-w-c-a-r-t', array('controller' => 'orders', 'action' => 'ajaxShowCart','lang'=>$lang));
		Router::connect('/ajax'.$prefix.'a-d-d-t-o-c-a-r-t', array('controller' => 'orders', 'action' => 'ajaxAddToCart','lang'=>$lang));
		Router::connect('/ajax'.$prefix.'d-e-l-p-r-o-c-a-r-t', array('controller' => 'orders', 'action' => 'ajaxDelProCart','lang'=>$lang));
		Router::connect('/ajax'.$prefix.'a-d-d-sighted', array('controller' => 'orders', 'action' => 'ajaxAddSighted','lang'=>$lang));
		Router::connect('/ajax'.$prefix.'fast-order', array('controller' => 'orders', 'action' => 'ajaxFastOrder','lang'=>$lang));

		Router::connect($prefix.'order-info', array('controller' => 'orders', 'action' => 'info','lang'=>$lang));
		Router::connect($prefix.'order-confirm', array('controller' => 'orders', 'action' => 'confirm','lang'=>$lang));
		Router::connect($prefix.'order-thanks', array('controller' => 'orders', 'action' => 'thanks','lang'=>$lang));


		//Hãng sx
		Router::connect($prefix.$val['product_maker'].'/:slug/sort_:sort-:direction/page-:page',array('controller'=>'products','action'=>'maker','lang'=>$lang),array('pass'=>array('slug')));
		Router::connect($prefix.$val['product_maker'].'/:slug/sort_:sort-:direction',array('controller'=>'products','action'=>'maker','lang'=>$lang),array('pass'=>array('slug')));

		Router::connect($prefix.$val['product_maker'].'/:slug/page-:page',array('controller'=>'products','action'=>'maker','lang'=>$lang),array('pass'=>array('slug')));
		Router::connect($prefix.$val['product_maker'].'/:slug',array('controller'=>'products','action'=>'maker','lang'=>$lang),array('pass'=>array('slug')));

		//Tag
		Router::connect($prefix.'tag/:id/:slug',array('controller'=>'tags','action'=>'index','lang'=>$lang),array('pass'=>array('id', 'slug')));

		Router::connect('/ajax'.$prefix.'g-e-t-p-r-o-d-u-c-t',array('controller'=>'tags','action'=>'ajaxGetProduct','lang'=>$key));
		Router::connect('/ajax'.$prefix.'g-e-t-p-o-s-t',array('controller'=>'tags','action'=>'ajaxGetPost','lang'=>$key));
		Router::connect('/ajax'.$prefix.'d-e-l-e-t-e-i-t-e-m',array('controller'=>'tags','action'=>'ajaxDeleteItem','lang'=>$key));
		Router::connect('/ajax'.$prefix.'list-more',array('controller'=>'products','action'=>'ajaxListMore','lang'=>$key));
        Router::connect('/ajax'.$prefix.'load-product-related',array('controller'=>'products','action'=>'ajaxScrollLoadProductRelated','lang'=>$key));

		$n = 7;			//Hỗ trợ write n cấp danh mục

		//Danh sách sản phẩm khi sắp xếp và phân trang
		for($i=$n;$i>=0;$i--){
			$slug = '';
			for($j=0;$j<$i;$j++) $slug.=(($j>0)?'/':'').':slug'.$j;
			Router::connect($prefix.$slug.'/sort_:sort-:direction/page-:page',array('controller'=>'products','action'=>'index','lang'=>$lang),array('pass'=>array('slug'.($i-1)),'page' => '[0-9]+'));
		}

		//Danh sách sản phẩm khi sắp xếp
		for($i=$n;$i>=0;$i--){
			$slug = '';
			for($j=0;$j<$i;$j++) $slug.=(($j>0)?'/':'').':slug'.$j;

			Router::connect($prefix.$slug.'/sort_:sort-:direction',array('controller'=>'products','action'=>'index','lang'=>$lang),array('pass'=>array('slug'.($i-1))));
		}

		//Danh sách sản phẩm phân trang
		for($i=$n;$i>=0;$i--){
			$slug = '';
			for($j=0;$j<$i;$j++) $slug.=(($j>0)?'/':'').':slug'.$j;

			Router::connect($prefix.$slug.'/page-:page',array('controller'=>'products','action'=>'index','lang'=>$lang),array('pass'=>array('slug'.($i-1)),'page' => '[0-9]+'));
		}

		//Danh sach, chi tiết san pham theo danh muc (danh sách và danh mục phân biệt bằng có .html và ko có .html)
		for($i=$n;$i>=0;$i--){
			$slug = '';
			for($j=0;$j<$i;$j++) $slug.=(($j>0)?'/':'').':slug'.$j;
			Router::connect($prefix.$slug,array('controller'=>'products','action'=>'index','lang'=>$lang),array('pass'=>array('slug'.($i-1))));
		}
		// Router::connect($prefix.'/khuyen-mai-hot',array('controller'=>'products','action'=>'index','lang'=>$lang),array('pass'=>array('slug'.($i-1))));
	}



/**
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
