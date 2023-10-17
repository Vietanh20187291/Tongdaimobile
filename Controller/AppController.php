<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components 	= array('Session','Cookie','Auth','Oneweb');
	public $helpers 	= array('Html','Session','Text','OnewebVn');
	public $uses 		= array('Config');

	/*
	* @Description	:
	*
	* @param	: array
	* @param	: string
	* @param	: int
	* @return	: array
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	function beforeFilter(){
//		$this->_resetHtaccess(1440);
//		if($this->_lockIp()==true) $this->redirect('/anti_ddos.php');
//		else{
//			$this->_logIp($total=1000,$continuity=20,$time = 10);

			Configure::load('oneweb');
			// $oneweb_product = Configure::read('Product');
			// $oneweb_post = Configure::read('Post');
			// $oneweb_web = Configure::read('Web');
			$oneweb_language = Configure::read('Language');
			// $oneweb_banner = Configure::read('Banner');
			// $oneweb_poll = Configure::read('Poll');
			// $oneweb_information = Configure::read('Information');
			// $oneweb_faq = Configure::read('Faq');
			// $oneweb_contact = Configure::read('Contact');
			// $oneweb_support = Configure::read('Support');
			// $oneweb_seo = Configure::read('Seo');
			// $oneweb_notifice = Configure::read('Notifice');
			// $oneweb_media = Configure::read('Media');
			// $oneweb_path = Configure::read('Path');
			// $oneweb_size = Configure::read('Size');
			// $oneweb_map = Configure::read('Map');
			// $oneweb_sitemap = Configure::read('Sitemap');
			// $oneweb_newsletter = Configure::read('Newsletter');
			// $oneweb_member = Configure::read('Member');
			// $oneweb_country = Configure::read('Country');
			// $oneweb_advertisement = Configure::read('Advertisement');
			$GB_config['disabled'] = false;
			$GB_config['Contact'] = true;
			$this->set(compact('oneweb_language','GB_config'));
			$this->set('http_host',$this->getHttpHost());
			//Kiểm tra ngôn ngữ có được enable không
			$lang = @$this->params['lang'];
			if(!empty($lang)){
				$a_languages = array_keys($oneweb_language);
				if(!in_array($lang, $a_languages )) $this->redirect(array('controller'=>'pages','action'=>'home','lang'=>$a_languages[0]));
			}
			$this->__handleAuthentication();
			// $this->disableCache();
	}
	/**
	 * @Description : Xóa cache
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function _deleteCache(){
		Cache::clear('','oneweb');
		Cache::clear('','oneweb_view');
	}

	public function __handleAuthentication(){
		$a_params = $this->request->params;
		
		if (empty($a_params['admin']) && empty($a_params['staff'])) {
			$this->Auth->allow();
			$this->layout = 'frontend/index';
			$this->_beforeFilterFrontend();
				}else{
					//Configure AuthComponent
			$this->Auth->loginAction 	= array('controller' => 'users', 'action' => 'login','admin'=>true);
			$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login','admin'=>true);
			$this->Auth->authError 		= "Bạn chưa đăng nhập";
			$this->Auth->authorize		= 'Controller';
			$this->layout = 'backend/index';
			$this->_beforeFilterBackend();
				}

				if($a_params['controller']=='users' && $a_params['action']=='admin_login') $this->layout = 'backend/login';
	}

	public function isAuthorized($user) {
			// Admin can access every action
			if (isset($user['role']) && ($user['role'] === 'admin' || $user['role'] === 'staff')) {
					return true;
			}

			// Default deny
			return false;
	}

	public function getHttpHost() {
    $http = 'http://';
    if($_SERVER['HTTP_HOST']=='localhost'){
      $path = array_values(array_filter(explode('/',$_SERVER['REQUEST_URI'])));
      $path = '/'.$path[0];
    }else {
        if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') $http = 'https://';
        // if($_SERVER['HTTPS']) $http = 'https://';
        $path = '';
    }

    return $http.$_SERVER['HTTP_HOST'].$path.'/';
	}

	/*
	* @Description	:
	*
	* @param	: array
	* @param	: string
	* @param	: int
	* @return	: array
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	public function _beforeFilterBackend(){
		parent::beforeFilter();
		$oneweb_web = Configure::read('Web');
		$oneweb_product = Configure::read('Product');
		$oneweb_post = Configure::read('Post');
		$oneweb_web = Configure::read('Web');
		$oneweb_language = Configure::read('Language');
		$oneweb_banner = Configure::read('Banner');
		$oneweb_poll = Configure::read('Poll');
		$oneweb_information = Configure::read('Information');
		$oneweb_faq = Configure::read('Faq');
		$oneweb_contact = Configure::read('Contact');
		$oneweb_support = Configure::read('Support');
		$oneweb_seo = Configure::read('Seo');
		$oneweb_notifice = Configure::read('Notifice');
		$oneweb_media = Configure::read('Media');
		$oneweb_path = Configure::read('Path');
		$oneweb_size = Configure::read('Size');
		$oneweb_map = Configure::read('Map');
		$oneweb_sitemap = Configure::read('Sitemap');
		$oneweb_newsletter = Configure::read('Newsletter');
		$oneweb_member = Configure::read('Member');
		$oneweb_country = Configure::read('Country');
		$oneweb_advertisement = Configure::read('Advertisement');
		$this->set(compact('GB_config','oneweb_web','oneweb_product','oneweb_post','oneweb_poll','oneweb_information','oneweb_faq','oneweb_contact','oneweb_banner','oneweb_support','oneweb_seo','oneweb_notifice','oneweb_media','oneweb_language','oneweb_path','oneweb_size','oneweb_map','oneweb_sitemap','oneweb_document','oneweb_newsletter','oneweb_member','oneweb_country','oneweb_advertisement'));
		if(!$this->Session->check('lang')) $this->Session->write('lang','vi');

// 		$this->Cookie->name = 'baker_id';
// 		$this->Cookie->time = 3600;  // or '1 hour'
// 		$this->Cookie->path = '/bakers/preferences/';
// 		$this->Cookie->domain = 'example.com';
// 		$this->Cookie->secure = true;  // i.e. only sent if using secure HTTPS
// 		$this->Cookie->key = 'qSI232qs*&sXOw!adre@34SAv!@*(XSL#$%)asGb$@11~_+!@#HKis~#^';
// 		$this->Cookie->httpOnly = true;

// 		$admin = array(
// 				'id' => '1',
// 				'name' => 'Tuấn Anh',
// 				'username' => 'admin',
// 				'role' => 'admin',
// 				'group_id' => '1',
// 				'created' => '1347811466',
// 				'modified' => '1352023114'
// 			);

// 		$this->Cookie->write('auth_remember', $admin, true, 36000);

// 		$admin2 = $this->Cookie->read('auth_remember');
// 		debug($admin2);die;

// 		$this->Session->write('Auth.User',$admin);
        if(!empty($this->Auth->user())) {
            $a_user = $this->Auth->user();
            //Cập nhật lại thông tin user từ cơ sở dữ liệu
            $this->loadModel('User');
            $a_user = $this->User->find('first', array(
                'conditions'=>array('User.id'=>$a_user['id'])
            ));
            $a_user = $a_user['User'];
            $this->set('admin', $this->Auth->user());
            Configure::write('Config.language', 'vi');
            $controller = $this->params['controller'];
            $action = $this->params['action'];

//        var_dump($controller);exit;
            if($a_user['name'] != 'Admin'){
                if($a_user['pos_1'] != 1) {
                    switch ($controller) {
                        case 'products':
                            if ($action == in_array($action, array('admin_add', 'admin_edit', 'admin_index')) && empty($a_user['pos_3']) || $a_user['pos_3'] == 0) {
                                $this->Session->setFlash('<span>' . __("Bạn không có quyền truy cập.", true) . '</span>', 'default', array('class' => 'error'));
                                $this->redirect(array('controller' => 'pages', 'action' => 'admin_index'));
                            }
                            break;
                        case 'posts':
                            if ($action == in_array($action, array('admin_add', 'admin_edit', 'admin_index')) && empty($a_user['pos_2']) || $a_user['pos_2'] == 0) {
                                $this->Session->setFlash('<span>' . __("Bạn không có quyền truy cập.", true) . '</span>', 'default', array('class' => 'error'));
                                $this->redirect(array('controller' => 'pages', 'action' => 'admin_index'));
                            }
                            break;
                        case 'orders':
                            if ($action == in_array($action, array('admin_add', 'admin_edit', 'admin_index')) && empty($a_user['pos_4']) || $a_user['pos_4'] == 0) {
                                $this->Session->setFlash('<span>' . __("Bạn không có quyền truy cập.", true) . '</span>', 'default', array('class' => 'error'));
                                $this->redirect(array('controller' => 'pages', 'action' => 'admin_index'));
                            }
                            break;
                        case 'users':
                            if ($action == in_array($action, array('admin_add', 'admin_edit', 'admin_index', 'admin_ajaxChangeStatus', 'admin_ajaxTrashItem'))) {
                                $this->Session->setFlash('<span>' . __("Bạn không có quyền truy cập.", true) . '</span>', 'default', array('class' => 'error'));
                                $this->redirect(array('controller' => 'pages', 'action' => 'admin_index'));
                            }
                            break;
                    }
                }
            }
        }
        if(!$this->Session->check('Auth')) $this->Auth->logout();
        //Thống kê form nhúng
		$this->loadModel('ContactForm');
		$count_tv =  $this->ContactForm->find('count',array('conditions'=>array('type'=>'registv')));
		$count_tv_new =  $this->ContactForm->find('count',array('conditions'=>array('type'=>'registv','view'=>0)));
		$this->set('count_tv_c',$count_tv);
		$this->set('count_tv_new_c',$count_tv_new);

		$count_gift =  $this->ContactForm->find('count',array('conditions'=>array('type'=>'gift')));
		$count_gift_new =  $this->ContactForm->find('count',array('conditions'=>array('type'=>'gift','view'=>0)));
		$this->set('count_gift_c',$count_gift);
		$this->set('count_gift_new_c',$count_gift_new);

		$count_event =  $this->ContactForm->find('count',array('conditions'=>array('type'=>'event')));
		$count_event_new =  $this->ContactForm->find('count',array('conditions'=>array('type'=>'event','view'=>0)));
		$this->set('count_event_c',$count_event);
		$this->set('count_event_new_c',$count_event_new);
	}

	/*
	* @Description	:
	*
	* @param	: array
	* @param	: string
	* @param	: int
	* @return	: array
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	public function _beforeFilterFrontend(){
		
		parent::beforeFilter();
		$oneweb_web = Configure::read('Web');
		$oneweb_product = Configure::read('Product');
		$oneweb_counter = Configure::read('Counter');
		$oneweb_post = Configure::read('Post');
		$oneweb_support = Configure::read('Support');
		$oneweb_information = Configure::read('Information');
		$oneweb_media = Configure::read('Media');
		$oneweb_banner = Configure::read('Banner');
		$oneweb_search = Configure::read('Search');
		$oneweb_counter = Configure::read('Counter');
		$oneweb_poll = Configure::read('Poll');
		$oneweb_advertisement = Configure::read('Advertisement');
		$oneweb_contact = Configure::read('Contact');
		
		$this->set(compact('oneweb_web','oneweb_product','oneweb_counter','oneweb_post','oneweb_support',
		'oneweb_information','oneweb_media','oneweb_banner','oneweb_search','oneweb_counter','oneweb_poll','oneweb_advertisement','oneweb_contact'));
		
		$this->layout = 'frontend/'.$oneweb_web['layout'].'/index';
		
		$this->set('url_current_encode',md5($this->Oneweb->curPageURL()));
		
		$controller = $this->params['controller'];
		$action = $this->params['action'];
		
		//Ngon ngu
		$lang = 'vi';
		if(!empty($this->params['lang'])) $lang = $this->params['lang'];
		elseif(!empty($this->passedArgs['lang'])) $lang = $this->passedArgs['lang'];
		Configure::write('Config.language', $lang);
		$this->set(compact('lang'));

		//CONFIG - SITE
		//Đọc cấu hình
		$a_configs_h = $this->_getConfig('contact');
		$this->set(compact('a_configs_h'));
		$a_site_info = $this->_getConfig('site');
		$this->set(compact('a_site_info'));
		$this->set('a_slideshow_config',$this->_getConfig('slideshow'));
		//Tiền tệ
		if(empty($oneweb_product['currency'])) $this->Session->delete("Currency_$lang.id");

		if(!empty($a_site_info['currency']) && !$this->Session->check("Currency_$lang.id")) $this->Session->write("Currency_$lang.id",$a_site_info['currency']);		//Thiết lập đơn vị tiền mặc định từ trong quản trị
		$currency_id = $this->Session->read("Currency_$lang.id");

// 		$a_currencies = Cache::read('a_currencies_h_'.$lang,'oneweb');
// 		if (!$a_currencies) {
//
// 			$this->loadModel('Currency');
// 			$a_currencies = $this->Currency->find('all',array(
// 					'conditions'=>array('status'=>1),
// 					'order'=>'name asc'
// 			));
//
//
// 			$tmp = array();
// 			$currency_set = array();				//Đơn vị tiền được thiết lập để hiển thị
// 			$currency_default = array();			//Đơn vị tiền hiển thị mặc định được thiết lập từ trong quản trị
// 			foreach($a_currencies as $val){
// 				$item = $val['Currency'];
// 				$tmp[$item['id']] = $item['name'];
// 				if($item['id']==$currency_id) $currency_set = $item;
// 				if($item['id']==$a_site_info['currency']) $currency_default = $item;
// 			}
// 			$a_currencies = $tmp;
// 			Cache::write('a_currencies_h_'.$lang,$a_currencies,'oneweb');
// 		}
// 		$this->set('a_currencies_h',$a_currencies);
		
// 		$a_currency = array();
// 		if(empty($currency_set)){		//Mặc định ban đầu
// 			$a_currency['name']='VNĐ';
// 			$a_currency['value']='1';
// 			$a_currency['location']='last';
// 			$a_currency['decimal'] = 0;
// 			$a_currency['sep1'] = ',';
// 			$a_currency['sep2'] = '.';
// 		}else{
// 			$a_currency['name']=$currency_set['unit'];
// 			$a_currency['value']=@round($currency_set['value']/$currency_default['value'],10);
// 			$a_currency['location']=$currency_set['location'];
// 			if(in_array($currency_set['name'], array('đ','d','vnđ','vnd','Đ','D','VNĐ','VND'))){
// 				$a_currency['decimal'] = 0;
// 				$a_currency['sep1'] = ',';
// 				$a_currency['sep2'] = '.';
// 			}else{
// 				$a_currency['decimal'] = 2;
// 				$a_currency['sep1'] = '.';
// 				$a_currency['sep2'] = ',';
// 			}
// 		}
// 		$this->set('a_currency_c',$a_currency);
// 		$this->set('a_currency_default_c',$currency_default);

		// Quảng cáo
		if($oneweb_advertisement['enable']){
			$a_advertisements = Cache::read('a_advertisement_enable_'.$lang,'oneweb');
			if(!$a_advertisements) {
				$this->loadModel('Advertisement');
				$a_advertisements['meta_header'] = $this->_getAdv('meta_header',$lang);
				$a_advertisements['adv_home0'] = $this->_getAdv('adv_home0',$lang);
				// $a_advertisements['adv_home1'] = $this->_getAdv('adv_home1',$lang);
				// $a_advertisements['adv_home2'] = $this->_getAdv('adv_home2',$lang);
				// $a_advertisements['adv_home3'] = $this->_getAdv('adv_home3',$lang);
				// $a_advertisements['adv_col_right1'] = $this->_getAdv('adv_col_right1',$lang);
				// $a_advertisements['adv_col_right'] = $this->_getAdv('adv_col_right',$lang);
				// $a_advertisements['adv_content'] = $this->_getAdv('adv_content',$lang);
				// $a_advertisements['adv_content_center'] = $this->_getAdv('adv_content_center',$lang);
				// $a_advertisements['adv_content_bottom'] = $this->_getAdv('adv_content_bottom',$lang);
				// $a_advertisements['adv_bottom_left'] = $this->_getAdv('adv_bottom_left',$lang);
				$a_advertisements['google_analytics'] = $this->_getAdv('google_analytics',$lang);

				Cache::write('a_advertisement_enable_'.$lang,$a_advertisements,'oneweb');
			}
			$this->set(compact('a_advertisements'));
		}


		if(!$this->request->is('ajax')){

// 			if($_SERVER['HTTP_HOST']!='localhost'){
// 				$encode = true;
// 				if($action=='captchaImage') $encode = false;
// 				elseif($controller=='contacts')  $encode = false;
// 				elseif($controller=='products' && !empty($this->params['ext']) && $oneweb_product['comment']) $encode=false;
// 				elseif($controller=='posts' && !empty($this->params['ext']) && $oneweb_post['comment']) $encode=false;
// 				elseif($controller=='videos' && !empty($this->params['ext']) && $oneweb_media['video']['comment']) $encode=false;
// 				elseif($controller=='galleries' && !empty($this->params['ext']) && $oneweb_media['gallery']['comment']) $encode=false;

// 				if($encode) App::import('Vendor','' ,array('file'=>'compressor.php'));
// 			}

// 			App::import('Vendor','' ,array('file'=>'compressor.php'));

			//**** Sản phẩm *****/
			if(!empty($oneweb_product['enable'])){
				$this->loadModel('Product');

				//Danh mục - cây thư mục
				$a_product_categories = Cache::read('product_category_'.$lang,'oneweb');
				if(!$a_product_categories){
					$this->Product->ProductCategory->unbindModel(array(
															'hasMany'=>array('Product','ChildProductCategory'),
															'belongsTo'=>array('ParentProductCategory')
														));

					$a_product_categories = $this->Product->ProductCategory->find('threaded',array(
							'conditions'=>array('ProductCategory.lang'=>$lang,'ProductCategory.status'=>1,'ProductCategory.trash'=>0),
							'fields'=>array('slug','meta_title','name','pos_home','banner','icon','lang','id','path','rel','parent_id','target','link','banner_link', 'counter'),
							'order'=>array('lft'=>'asc'),
					));
					Cache::write('product_category_'.$lang,$a_product_categories,'oneweb');
				}
				$this->set('a_product_categories_s',$a_product_categories);

				//Danh mục - Dạng list
				if(!empty($oneweb_search['product']['category'])){
// 					$a_product_categories_2_s = Cache::read('product_category_tree_'.$lang,'oneweb');

// 					if(!$a_product_categories_2_s){
// 						$a_product_categories_2_s = $this->Product->ProductCategory->generateTreeList(array('lang'=>$lang,'status'=>1,'trash'=>0));

// 						Cache::write('product_category_tree_'.$lang,$a_product_categories_2_s,'oneweb');
// 					}
// 					$this->set('a_product_categories_2_s',$a_product_categories_2_s);
					$a_product_categories_2_s = Cache::read('product_category_tree_'.$lang,'oneweb');

					if(!$a_product_categories_2_s){
						$a_product_categories_2_s = $this->Product->ProductCategory->find('list',array(
								'conditions'=>array('lang'=>$lang,'status'=>1,'trash'=>0,'parent_id'=>null),
								'fields'=>array('name')
								));

						Cache::write('product_category_tree_'.$lang,$a_product_categories_2_s,'oneweb');
					}
					$this->set('a_product_categories_2_s',$a_product_categories_2_s);
				}
				// San phan hien thi theo danh muc
				$a_products_display_categories = Cache::read('product_category_display_categories_'.$lang,'oneweb');


				if(!$a_products_display_categories) {

					$a_products_display_categories = array();
					foreach($a_product_categories as $categories) {
						$a_category = $categories['ProductCategory'];
						if(!empty($a_category['pos_home'])){
							$a_category_name[] = $a_category['name'];
							$a_ids = array($a_category['id']);
							$a_ids2 = $a_ids;
								$a_child_direct_categories = $this->Product->ProductCategory->find('all',array(
									'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'parent_id'=>$a_category['id']),
									'fields'=>array('id','name','slug','lang','path','meta_title','rel','target','link','image','status','counter'),
									'order'=>array('lft'=>'asc','name'=>'asc'),
									'recursive'=>-1
								));

						if(!empty($a_child_direct_categories)) {
							$a_child_categories = $this->Product->ProductCategory->children($a_category['id'],false,array('id','status','trash'));
							$a_ids2 = $a_ids;
							foreach ($a_child_categories as $val){
								$item_cate = $val['ProductCategory'];

								if($item_cate['status'] && !$item_cate['trash']){
									if(!empty($oneweb_product['pro_child'])) $a_ids[] = $item_cate['id'];
									$a_ids2[] = $item_cate['id'];
								}
							}
						}
						$a_conditions2 = array(array('product_category_id'=>$a_ids));

						for ($i=0;$i<count($a_ids);$i++){
							$a_conditions2[] = array('category_other like'=>'%-'.$a_ids[$i].'-%');
						}

						$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
						$a_conditions = array('Product.lang'=>$lang,'Product.status'=>1,'Product.trash'=>0,'or'=>$a_conditions2,'Product.public <='=>$date_current);
						if(!empty($oneweb_product['maker'])) {
							$a_conditions = array_merge($a_conditions,array('ProductMaker.status'=>1,'ProductMaker.trash'=>0));
							if(!empty($_GET['maker_id'])) $a_conditions = array_merge($a_conditions,array('product_maker_id'=>$_GET['maker_id']));

							if(empty($a_makers)){
								$a_conditions3[] = array('product_category_id'=>$a_ids2);
								for ($i=0;$i<count($a_ids2);$i++){
									$a_conditions3[] = array('category_other like'=>'%-'.$a_ids2[$i].'-%');
								}

									$a_maker_ids = $this->Product->find('all',array(
										'conditions'=>	array('Product.lang'=>$lang,'Product.status'=>1,'Product.trash'=>0,'or'=>$a_conditions3),
										'fields'=>array('product_maker_id'),
										'recursive'=>-1
									));



								$tmp = array();
								for($i=0;$i<count($a_maker_ids);$i++){
									$tmp[] = $a_maker_ids[$i]['Product']['product_maker_id'];
								}
								$a_maker_ids = array_unique($tmp);
									$a_makers = $this->Product->ProductMaker->find('all',array(
											'conditions'=>array('id'=>$a_maker_ids),
											'fields'=>array('id','name','slug','image','meta_title','link','slug','rel','target','counter'),
											'order'=>array('sort'=>'asc','name'=>'asc'),
											'recursive'=>-1
									));

							}

						}
						//lấy sản phẩm theo danh mục

							$products = $this->Product->find('all', array(
									'conditions' => $a_conditions,
									'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.price_new','Product.hot','Product.slug','Product.meta_title','Product.rel','Product.target','Product.image','Product.price','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.public','Product.summary', 'Product.star_rate', 'Product.star_rate_count',
											'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
									),
									'order' => 'rand()',
									'limit' => 5
							));


						$categories['Products'] = $products;
						if(!empty($categories['Products']) ) //&& (count($products) >= $oneweb_product['display_home']['ProductQuantity'])
							$a_products_display_categories[] = $categories;
						}
					}
					if(!empty($a_category_name)){
						$categoryDisplay = array();
						foreach($a_category_name as $itemCategory)
							$categoryDisplay[] = $this->Oneweb->slug($itemCategory);

						foreach($a_products_display_categories as $key => $val)
							if(!in_array($val['ProductCategory']['slug'], $categoryDisplay))
							unset($a_products_display_categories[$key]);
						}

					Cache::write('product_category_display_categories_'.$lang,$a_products_display_categories,'oneweb');
				}
				

				$this->set('a_products_display_categories', $a_products_display_categories);

				//Hãng sản xuất
				if(!empty($oneweb_product['maker'])){
					$a_product_makers = Cache::read('product_maker_'.$lang,'oneweb');
					if(!$a_product_makers){
						$a_product_makers = $this->Product->ProductMaker->find('all',array(
							'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang),
							'fields'=>array('id','name','slug','image','meta_title','link','slug','rel','target','counter'),
							'order'=>array('sort'=>'asc','name'=>'asc'),
							'recursive'=>-1
						));
						Cache::write('product_maker_'.$lang,$a_product_makers,'oneweb');
					}
					$this->set('a_product_makers_s',$a_product_makers);

					if(!empty($oneweb_search['product']['maker'])){
						$a_product_makers_2 = array();
						foreach ($a_product_makers as $val){
							$item = $val['ProductMaker'];
							$a_product_makers_2[$item['id']] = $item['name'];
						}
						$this->set('a_product_makers_2_c',$a_product_makers_2);
					}
				}
				//San phẩm tùy chọn vị trí hiển thị
				if(!empty($oneweb_product['display'][7])) $a_products_pos7 = $this->_productDisplay('pos_7');
				if(!empty($oneweb_product['display'][8])) $a_products_pos8 = $this->_productDisplay('pos_8');
				$this->set(compact('a_products_pos7','a_products_pos8'));




			}

			/**
			 * @Description :
			 * @throws 	: NotFoundException
			 * @param 	: int id
			 * @return 	: void
			 * @Author 	: Huu Quynh - quynh@url.vn
			 */
			if($oneweb_product['tag'] || $oneweb_post['tag']){
				$a_tags = Cache::read('a_tags_s_tag_'.$lang,'oneweb');
				if(!$a_tags) {
					$this->loadModel('Tag');
					$a_tags = $this->Tag->find('all',array('conditions'=>array('lang'=>$lang),'limit'=>20,'order'=>'number desc','recursive'=>-1,'fields'=>array('name','meta_title','number')));
					$size_count = 4;
					for($i=0;$i<count($a_tags);$i++){
						$item = $a_tags[$i]['Tag'];
	
						if($item['number']>5 &&$size_count>1){		//Chọn ra 3 kích thước to nhất và sổ bản ghi phải lớn hơn 5
							$class = 'size'.$size_count;
							if(!empty($a_tags[$i+1]['Tag']['number']) && ($item['number']!=$a_tags[$i+1]['Tag']['number']))	//Truong hop so ban ghi cua tag nay bang so ban ghi cua tag truoc
								$size_count--;
						}else $class = '';
	
						$a_tags[$i]['Tag']['class'] = $class;
					}
					sort($a_tags);
					Cache::write('a_tags_s_tag_'.$lang,$a_tags,'oneweb');
				}
				$this->set('a_tags_s',$a_tags);
			}
			//**** Bài viết *****/
			if(!empty($oneweb_post['enable'])){

				$this->loadModel('Post');

				//Danh mục - Dạng cây thư mục
				$a_post_categories = Cache::read('post_category_'.$lang,'oneweb');
				if(!$a_post_categories){
					$this->Post->PostCategory->unbindModel(array(
															'hasMany'=>array('Post','ChildPostCategory'),
															'belongsTo'=>array('ParentPostCategory')
														));

					$a_post_categories = $this->Post->PostCategory->find('threaded',array(
						'contain'=>array('ChildPostCategory'=>array('conditions'=>array('ChildPostCategory.status'=>1, 'ChildPostCategory.trash'=>0))),
						'conditions'=>array('PostCategory.lang'=>$lang,'PostCategory.status'=>1,'PostCategory.trash'=>0, 'PostCategory.parent_id'=>NULL),
						'fields'=>array('slug','meta_title','name','lang','id','path','rel','parent_id','target','link','position'),
						'order'=>array('lft'=>'asc'),
					));
					Cache::write('post_category_'.$lang,$a_post_categories,'oneweb');
				}
				$this->set('a_post_categories_s',$a_post_categories);

				//Danh mục - Dạng list
				if($oneweb_search['post']['category']){
					$a_post_categories_2_s = Cache::read('post_category_tree_'.$lang,'oneweb');

					if(!$a_post_categories_2_s){
						$a_post_categories_2_s = $this->Post->PostCategory->generateTreeList(array('lang'=>$lang,'status'=>1,'trash'=>0));
						Cache::write('post_category_tree_'.$lang,$a_post_categories_2_s,'oneweb');
					}
					$this->set('a_post_categories_2_s',$a_post_categories_2_s);
				}
				//Bài viết tùy chọn vị trí hiển thị
				if(!empty($oneweb_post['display'][5])) $a_posts_pos5 = $this->_postDisplay('pos_5','10');
				if(!empty($oneweb_post['display'][6])) $a_posts_pos6 = $this->_postDisplay('pos_6','40');

				$this->set(compact('a_posts_pos5','a_posts_pos6'));
				//danh mục sidebar
				$a_post_categories = Cache::read('post_categories_sidebar_'.$lang,'oneweb');

				if(!$a_post_categories) {
					$a_post_categories = $this->Post->PostCategory->find('all',array(
						'contain'=>array('ChildPostCategory'=>array('conditions'=>array('ChildPostCategory.status'=>1, 'ChildPostCategory.trash'=>0))),
						'conditions'=>array('PostCategory.lang'=>$lang,'PostCategory.status'=>1,'PostCategory.trash'=>0, 'PostCategory.parent_id'=>NULL),
						'fields'=>array('slug','meta_title','name','lang','id','path','rel','parent_id','target','link','position'),
						'order'=>array('PostCategory.lft'=>'asc'),
					));
					Cache::write('post_categories_sidebar_'.$lang,$a_post_categories,'oneweb');
				}

				$this->set('a_post_categories_s',$a_post_categories);

				// bài viết nổi bật
				$featured_posts = Cache::read("featured_posts", 'oneweb');
				if (!$featured_posts) {
					$featured_posts = $this->Post->find('all', array(
						'conditions' => array('Post.sort' => 1),
						'fields' => array('Post.id','Post.lang','Post.slug','Post.meta_title','Post.target','Post.rel','Post.name','Post.summary','Post.image','Post.user_id',
						'PostCategory.id','PostCategory.position','PostCategory.path'
					),
						'recursive' => 0,
					));
					Cache::write("featured_posts", $featured_posts, 'oneweb');
				}
				$this->set('featured_post_c', $featured_posts);

			}

			//****** Hỗ trợ trực tuyến *******/
			if(!empty($oneweb_support['enable'])){
					$this->loadModel('Support');
					$a_support = Cache::read('a_support'.$lang,'oneweb');
					if(!$a_support) {
						$a_support = $this->Support->find('all',array(
							'conditions'=>array('status'=>1),
							'order'=>'sort asc'
						));
						Cache::write('a_support'.$lang,$a_support,'oneweb');
					}

				$this->set('a_support_s',$a_support);

				foreach ($a_support as $key => $value) {
					$support = $value['Support'];
					if (strtolower($support['name']) == 'hotline') $this->set('a_support_hotline', $support);
				}
			}
			/************Tham do y kien************/
			if(!empty($oneweb_poll['enable'])){
				$this->loadModel('PollQuestion');
				$this->loadModel('Poll');
				$a_polls = $this->PollQuestion->find('all',array(
						'conditions'=>array('PollQuestion.status'=>1,'PollQuestion.lang'=>$lang),
						'recursive'=>1,
						'fields'=>array('PollQuestion.name','PollQuestion.total'),
						'order'=>'PollQuestion.sort asc'
				));
				$this->set(compact('a_polls'));
			}

			//Trang thông tin
			if(!empty($oneweb_information['enable'])){
				$a_information = Cache::read('information_'.$lang,'oneweb');

				if(!$a_information){
					$this->loadModel('Information');
					$this->Information->unbindModel(array('belongsTo'=>array('ParentInformation')));
					$this->Information->bindModel(array(
						'hasMany'=>array(
							'ChildInformation' => array(
													'className' => 'Information',
													'foreignKey' => 'parent_id',
													'dependent' => true,
													'conditions' => array('status'=>1,'trash'=>0),
													'fields' => array('id','name','lang','slug','parent_id','link','rel','target','position','meta_title'),
													'order' => 'sort asc',
													'limit' => '',
													'offset' => '',
													'exclusive' => '',
													'finderQuery' => '',
													'counterQuery' => ''
												)
						)
					));


					$a_information = $this->Information->find('all',array(
						'conditions'=>array('Information.status'=>1,'Information.trash'=>0,'Information.parent_id'=>null,'Information.lang'=>$lang),
						'fields'=>array('id','name','lang','slug','parent_id','link','rel','target','position','meta_title'),
						'order'=>'sort asc'
					));

					Cache::write('information_'.$lang,$a_information,'oneweb');
				}
				$this->set('a_information_nav',$a_information);
				// debug(empty(array())); die();
			}

			//DOCUMENT
			if(!empty($oneweb_media['document']['enable'])){
				$this->loadModel('Document');

				//Danh mục tài liệu
				$a_document_categories = Cache::read('document_category_'.$lang,'oneweb');
				if(!$a_document_categories){
					$a_document_categories = $this->Document->DocumentCategory->find('all',array(
						'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang),
						'fields'=>array('id','name','slug','meta_title','rel','target'),
						'order'=>'sort asc',
						'recursive'=>-1
					));

					Cache::write('document_category_'.$lang,$a_document_categories,'oneweb');
				}
				$this->set('a_document_categories_c',$a_document_categories);
				//Tài liệu kích hoạt ra vị trí hiển thị
				if(!empty($oneweb_media['document']['display'][1])) $this->set('a_documents_pos1',$this->_documentDisplay('pos_1'));
			}

			//VIDEO
			if(!empty($oneweb_media['video']['enable'])){
				$this->loadModel('Video');

				//Danh mục video
				$a_video_categories = Cache::read('video_category_'.$lang,'oneweb');
				if(!$a_video_categories){
					$a_video_categories = $this->Video->VideoCategory->find('all',array(
						'conditions'=>array('lang'=>$lang,'status'=>1,'trash'=>0),
						'fields'=>array('id','name','slug','meta_title','rel','target'),
						'order'=>array('sort'=>'asc','name'=>'asc'),
						'recursive'=>-1
					));

					Cache::write('video_category_'.$lang,$a_video_categories,'oneweb');
				}
				$this->set('a_video_categories_h',$a_video_categories);

				//Video kích hoạt ra vị trí hiển thị
				if(!empty($oneweb_media['video']['display'][1])) $this->set('a_videos_pos1',$this->_videoDisplay('pos_1'));
			}

			//GALLERY
			if(!empty($oneweb_media['gallery']['enable'])){
				$this->loadModel('Gallery');

				//Danh mục gallery
				$a_gallery_categories = Cache::read('gallery_category_'.$lang,'oneweb');
				if(!$a_gallery_categories){
					$a_gallery_categories = $this->Gallery->GalleryCategory->find('all',array(
						'conditions'=>array('lang'=>$lang,'status'=>1,'trash'=>0),
						'fields'=>array('id','name','slug','meta_title','rel','target'),
						'order'=>array('sort'=>'asc','name'=>'asc'),
						'recursive'=>-1
					));

					Cache::write('gallery_category_'.$lang,$a_gallery_categories,'oneweb');
				}
				$this->set('a_gallery_categories_h',$a_gallery_categories);

				//Gallery kích hoạt ra vị trí hiển thị
				if(!empty($oneweb_media['gallery']['display'][1])) {
					$position = 1;
					$a_galleries_pos1 = Cache::read('a_galleries_pos1_'.$lang,'oneweb');
					if(!$a_galleries_pos1) {
						$a_galleries_pos1 = $this->Gallery->find('first',array(
							'conditions'=>array('Gallery.status'=>1,'Gallery.trash'=>0,'GalleryCategory.status'=>1,'GalleryCategory.trash'=>0,'Gallery.lang'=>$lang,'Gallery.pos_'.$position.' !='=>0),
							'fields'=>array('Gallery.id','Gallery.name','Gallery.slug','Gallery.image','Gallery.meta_title','Gallery.rel','Gallery.target','GalleryCategory.id','GalleryCategory.slug'),
							'order'=>array('Gallery.pos_'.$position=>'asc','Gallery.name'=>'asc'),
							'limit'=>  10,
							'recursive'=>1
						));
						Cache::write('a_galleries_pos1_'.$lang,$a_galleries_pos1,'oneweb');
					}
					$this->set('a_galleries_pos1',$a_galleries_pos1);
				}
			}

			//Banner
			if(!empty($oneweb_banner['enable'])){
				$this->loadModel('Banner');
				if(!empty($oneweb_banner['display'][1])) $this->set('a_banners_h',$this->_banner('pos_1'));
				if(!empty($oneweb_banner['display'][2])) $this->set('a_slideshows_c',$this->_banner('pos_2'));
				if(!empty($oneweb_banner['display'][3])) $this->set('a_banner_c',$this->_banner('pos_3'));
				if(!empty($oneweb_banner['display'][4])) $this->set('a_adv_l',$this->_banner('pos_4'));
				if(!empty($oneweb_banner['display'][5])) $this->set('a_banner_run',$this->_banner('pos_5'));
				if(!empty($oneweb_banner['display'][6])) $this->set('a_partner_l',$this->_banner('pos_6'));
				if(!empty($oneweb_banner['display'][7])) $this->set('a_partner_r',$this->_banner('pos_7'));
				if(!empty($oneweb_banner['display'][8])) $this->set('a_banners_pos8',$this->_banner('pos_8', 1));
				if(!empty($oneweb_banner['display'][9])) $this->set('a_banners_out_l',$this->_banner('pos_9'));
				if(!empty($oneweb_banner['display'][10])) $this->set('a_banners_out_r',$this->_banner('pos_10'));
				if(!empty($oneweb_banner['display'][12])) $this->set('a_popups',$this->_banner('pos_12'));
			}
		}

		// Lấy các tags để đưa vào dưới footer
		$this->loadModel('Tags');
		$a_footer_tags = Cache::read('a_footer_tags_'.$lang,'oneweb');
		if(!empty($a_footer_tags)) {
			$a_footer_tags = $this->Tags->find('all', array (
					'conditions'	=> array('lang' => $lang),
					'fields'			=> array('id', 'name', 'number', 'description', 'lang', 'slug'),
					'order'				=> 'number desc',
					'limit'				=> 20,
					'recursive'		=>-1,
			));
			Cache::write('a_footer_tags_'.$lang,$a_footer_tags,'oneweb');

		}
		$this->set('a_footer_tags', $a_footer_tags);
	}


	/**
	 * @Description : Lấy thông tin từ mục cấu hình
	 *
	 * @throws 	: NotFoundException
	 * @param 	: string $prefix
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function _getConfig($prefix){
		$lang = $this->params['lang'];
		$result = Cache::read('config_'.$prefix.'_'.$lang,'oneweb');
		if(!$result){
			$a_configs = $this->Config->find('all',array('conditions'=>array('name like'=>$prefix.'_%')));

			$result = array();
			foreach ($a_configs as $val){
				$item = $val['Config'];
				$key = substr($item['name'], stripos($item['name'], '_')+1);

				if(@unserialize($item['value'])==false){			//Gía trị ko là chuỗi đặc biệt
					$result[$key] = $item['value'];
				}else{												//Giá trị là chuỗi đặc biệt
					$tmp = unserialize($item['value']);
					if ( ! empty($tmp[$lang])) $result[$key] = $tmp[$lang];
				}
			}

			Cache::write('config_'.$prefix.'_'.$lang,$result,'oneweb');
		}
		return $result;
	}

	/**
	 * @Description : Lấy sản phẩm hiển thị ra vị trí truyền vào
	 *
	 * @throws 	: NotFoundException
	 * @param 	: string $position
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function _productDisplay($position=null){
		if($position==null) throw new NotFoundException(__('Invalid'));
		$lang = $this->params['lang'];
		$a_products = Cache::read('product_'.$position.'_'.$lang,'oneweb');
		if(!$a_products){
			$oneweb_product = Configure::read('Product');
			//Ngay hien tai
			$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			$a_conditions = array('Product.lang'=>$lang,'ProductCategory.status'=>1,'ProductCategory.trash'=>0,'Product.status'=>1,'Product.trash'=>0,'Product.public <='=>$date_current,'Product.'.$position.' !='=>0);

			if(!empty($oneweb_product['maker'])) $a_conditions = array_merge($a_conditions,array('ProductMaker.status'=>1,'ProductMaker.trash'=>0));

			$this->Product->unbindModel(array(
											'belongsTo'=>array('ProductTax','User'),
											'hasMany'=>array('ProductImage','ProductOption','ProductProperty','Comment')
										));
			$a_products = $this->Product->find('all',array(
				'conditions'=>$a_conditions,
				'fields'=>array('Product.id','Product.lang','Product.hot','Product.new','Product.price_new','Product.name','Product.name_en','Product.count_buyed','Product.slug','Product.meta_title','Product.rel','Product.price','Product.image','Product.quantity','Product.warranty','Product.unit','Product.price','Product.discount','Product.discount_unit','Product.promotion','Product.summary','Product.star_rate','Product.star_rate_count','Product.description','Product.public','Product.target','Product.'.$position,
								'ProductCategory.id','ProductCategory.name','ProductCategory.slug','ProductCategory.meta_title','ProductCategory.path',
								'ProductMaker.id','ProductMaker.name'
								),
				'limit'=>  30,
				'order'=>array($position=>'asc','Product.created'=>'desc')
			));

			Cache::write('product_'.$position.'_'.$lang,$a_products,'oneweb');
		}

		return $a_products;
	}
		/**
		 * hiển thị sản phẩm đã xem
		 * @return [type] [description]
		 */
		protected function productViewedShow() {
			if($this->Cookie->check('cookie_product_viewed')) {
	    	$cookie_product_viewed = $this->Cookie->read('cookie_product_viewed');
	    	$cookie_product_viewed = array_unique($cookie_product_viewed);
	    	// debug($cookie_product_viewed);die;

	    	$this->loadModel('Product');
		    $lang = $this->params['lang'];
		    $oneweb_product = Configure::read('Product');
				//Ngay hien tai
				$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

				$a_conditions = array('Product.lang'=>$lang,'ProductCategory.status'=>1,'ProductCategory.trash'=>0,'Product.status'=>1,'Product.trash'=>0,'Product.public <='=>$date_current,'Product.id'=>$cookie_product_viewed);

				if(!empty($oneweb_product['maker'])) $a_conditions = array_merge($a_conditions,array('ProductMaker.status'=>1,'ProductMaker.trash'=>0));

				$this->Product->unbindModel(array(
												'belongsTo'=>array('ProductTax','User'),
												'hasMany'=>array('ProductImage','ProductOption','ProductProperty','Comment')
											));
				$a_product_viewed = $this->Product->find('all',array(
					'conditions'=>$a_conditions,
					'fields'=>array('Product.id','Product.lang','Product.hot','Product.new','Product.price_new','Product.name','Product.name_en','Product.count_buyed','Product.slug','Product.meta_title','Product.rel','Product.price','Product.image','Product.quantity','Product.warranty','Product.unit','Product.price','Product.discount','Product.discount_unit','Product.promotion','Product.summary','Product.star_rate','Product.star_rate_count','Product.description','Product.public','Product.target',
									'ProductCategory.id','ProductCategory.name','ProductCategory.slug','ProductCategory.meta_title','ProductCategory.path',
									'ProductMaker.id','ProductMaker.name'
									),
					'limit'=>  30,
					// 'order'=>array('Product.created'=>'desc')
				));
				$this->set('a_product_viewed', $a_product_viewed);
			}
	  }

	/**
	 * @Description : Lấy bài viết hiển thị theo vị trí truyền vào
	 *
	 * @throws 	: NotFoundException
	 * @param 	: string $position
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function _postDisplay($position=null,$limit=null){
		if($position==null) throw new NotFoundException(__('Invalid'));
		$lang = $this->params['lang'];

		$a_posts = Cache::read('post_'.$position.'_'.$lang,'oneweb');
		$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		if(!$a_posts){
			$oneweb_post = Configure::read('Post');

			$a_conditions = array('Post.lang'=>$lang,'Post.status'=>1,'Post.trash'=>0,'Post.public <='=>$date_current,'PostCategory.status'=>1,'PostCategory.trash'=>0,'Post.'.$position.' !='=>0);

			$this->Post->unbindModel(array(
											'belongsTo'=>array('User'),
											'hasMany'=>array('Comment')
										));
			$a_posts = $this->Post->find('all',array(
				'conditions'=>$a_conditions,
				'fields'=>array('Post.id','Post.lang','Post.name','Post.slug','Post.meta_title','Post.rel','Post.image','Post.summary','Post.target','Post.'.$position,'Post.created','Post.public','Post.summary',
								'PostCategory.id','PostCategory.name','PostCategory.slug','PostCategory.meta_title','PostCategory.path,PostCategory.position',
								),
				'limit'=>$limit,
				'order'=>array('Post.created'=>'desc')
			));
			Cache::write('post_'.$position.'_'.$lang,$a_posts,'oneweb');
		}

		return $a_posts;
	}


	/**
	 * @Description : Lấy tài liệu theo vị trí hiển thị được kích hoạt
	 *
	 * @throws 	: NotFoundException
	 * @param 	: string $position
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function _documentDisplay($position=null){
		if($position==null) throw new NotFoundException(__('Invalid'));
		$lang = $this->params['lang'];

		$a_documents = Cache::read('document_'.$position.'_'.$lang,'oneweb');

		if(!$a_documents){

			$a_documents = $this->Document->find('all',array(
				'conditions'=>array('Document.status'=>1,'Document.trash'=>0,'DocumentCategory.status'=>1,'DocumentCategory.trash'=>0,'Document.lang'=>$lang,$position.' !='=>0),
				'fields'=>array('Document.id','Document.name','Document.file','Document.link','DocumentCategory.slug'),
				'limit'=>  10,
				'order'=>array('Document.'.$position=>'asc','Document.name'=>'asc')
			));

			Cache::write('document_'.$position.'_'.$lang,$a_documents,'oneweb');
		}

		return $a_documents;
	}


	/**
	 * @Description : Lấy video theo vị trí hiển thị được kích hoạt
	 *
	 * @throws 	: NotFoundException
	 * @param 	: string $position
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function _videoDisplay($position=null){
		if($position==null) throw new NotFoundException(__('Invalid'));
		$lang = $this->params['lang'];

		$a_videos = Cache::read('video_'.$lang,'oneweb');

		if(!$a_videos){
			$a_videos = $this->Video->find('all',array(
				'conditions'=>array('Video.status'=>1,'Video.trash'=>0,'VideoCategory.status'=>1,'VideoCategory.trash'=>0,'Video.lang'=>$lang,$position.' !='=>0),
				'fields'=>array('id','name','youtube','slug','meta_title','rel','target'),
				'order'=>array($position=>'asc','name'=>'asc'),
				'limit'=>  10,
				'recursive'=>0
			));

			Cache::write('video_'.$lang,$a_videos,'oneweb');
		}
		return $a_videos;
	}



	/**
	 * @Description : Lấy gallery theo vị trí hiển thị được kích hoạt  (bao gồm toàn bộ ảnh thuộc album)
	 *
	 * @throws 	: NotFoundException
	 * @param 	: string $position
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function _galleryDisplay($position=null){
		if($position==null) throw new NotFoundException(__('Invalid'));
		$lang = $this->params['lang'];

		$a_galleries = Cache::read('gallery_'.$position.'_'.$lang,'oneweb');

		if(!$a_galleries){

			$this->Gallery->unbindModel(array('hasMany'=>array('Comment')));
			$a_galleries = $this->Gallery->find('all',array(
				'conditions'=>array('Gallery.status'=>1,'Gallery.trash'=>0,'GalleryCategory.status'=>1,'GalleryCategory.trash'=>0,'Gallery.lang'=>$lang,$position.' !='=>0),
				'fields'=>array('Gallery.id','Gallery.name','Gallery.slug','Gallery.image','Gallery.meta_title','Gallery.rel','Gallery.target','GalleryCategory.id','GalleryCategory.slug'),
				'order'=>array($position=>'asc','Gallery.name'=>'asc'),
				'limit'=>  10,
				'recursive'=>1
			));

			Cache::write('gallery_'.$position.'_'.$lang,$a_galleries,'oneweb');
		}
		return $a_galleries;
	}


	/**
	 * @Description : Lấy banner
						'1'=>'Tất cả',
						'2'=>'Trang chủ',
						'3'=>'Danh mục sản phẩm',
						'18'=>'Hãng sản xuất',
						'14'=>'Chi tiết sản phẩm',
						'7'=>'Tìm kiếm sản phẩm',
						'4'=>'Danh mục bài viết',
						'15'=>'Chi tiết bài viết',
						'8'=>'Tìm kiếm bài viết',
						'6'=>'Các trang thông tin',
						'10'=>'Trang hiển thị Tag',
						'11'=>'Video',
						'16'=>'Chi tiết video',
						'12'=>'Hình ảnh',
						'17'=>'Chi tiết hình ảnh',
						'13'=>'Tài liệu',
						'9'=>'Faqs',
						'5'=>'Liên hệ'
	 * @throws 	: NotFoundException
	 * @param 	: string $position
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function _banner($position=null,$limit=null){
		if($position==null) throw new NotFoundException(__('Invalid'));
		$lang = $this->params['lang'];

		$controller = $this->params['controller'];
		$action = $this->params['action'];
		if(empty($limit)) $limit = 30;
		$page_display = '';
		switch ($controller){
			case 'pages':
				$page_display.='2';
				break;
			case 'products':
				if($action=='maker') $page_display.='18';
				else{
					if(empty($this->params['ext'])) $page_display.='3';			//Danh sách sản phẩm
					else $page_display.='14';									//Chi tiết sản phẩm
				}

				break;
			case 'posts':
				if(empty($this->params['ext'])) $page_display.='4';			//Danh sách bài viết
				else $page_display.='15';									//Chi tiết bài viết

				break;
			case 'contacts':
				$page_display.='5';
				break;
			case 'information':
				$page_display.='6';
				break;
			case 'searches':
				$page_display.='7';
				break;
			case 'maps':
				$page_display.='8';
				break;
			case 'faqs':
				$page_display.='9';
				break;
			case 'tags':
				$page_display.='10';
				break;
			case 'videos':
				if(empty($this->params['ext'])) $page_display.='11';
				else $page_display.='16';

				break;
			case 'galleries':
				if(empty($this->params['ext'])) $page_display.='12';
				else $page_display.='17';

				break;
			case 'documents':
				$page_display.='13';
				break;
		}

		$a_banners = Cache::read('banner_'.$page_display.'_'.$position.'_'.$lang,'oneweb');

		if(!$a_banners){
			$a_banners = $this->Banner->find('all',array(
				'conditions'=>array('lang'=>$lang,'status'=>1,'trash'=>0,$position.' !='=>0,'or'=>array(array('page like'=>'%-1-%'),array('page like'=>'%-'.$page_display.'-%'))),
				'fields'=>array('id','lang','name','image','link','rel','target','description'),
				'order'=>array('sort'=>'asc',$position=>'asc','name'=>'asc'),
				'limit'=>$limit
			));
			Cache::write('banner_'.$page_display.'_'.$position.'_'.$lang,$a_banners,'oneweb');
		}

		return $a_banners;
	}


	/**
	 * @Description : Thay đổi trạng thái
	 *
	 * @param 	: int $id
	 * @param	: string $field
	 * @return 	: array($add: addClass; $remove: removeClass)
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function _changeStatus($field,$id){
		//Lay thong tin
		$model = $this->modelClass;
		$this->$model->recursive = -1;
		$a_item = $this->$model->read($field,$id);

		//Thay đổi trạng thái
		$this->$model->id = $id;
		$this->$model->set(array($field=>(!empty($a_item[$model][$field]))?'0':'1'));
		$this->$model->save();

		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache

		if($a_item[$model][$field]) $return = array('add'=>'unactive','remove'=>'active');
		else $return = array('add'=>'active','remove'=>'unactive');

		return $return;
	}

	/**
	 * @Description : Tăng lượt xem
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function _increaseView($id=null){
		if($id == null) throw new NotFoundException(__('Trang này không tồn tại',true));
		$model = $this->modelClass;

		//Kiểm tra thời gian tăng lượt xem
		$item = array();
		if($this->Session->check('Increase.'.$model)) $item = $this->Session->read('Increase.'.$model);

		$current_time = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		if(empty($item[$id]) || (!empty($item[$id]) && ($current_time-$item[$id])>=(5*60))){
			$a_item = $this->$model->read('view',$id);
			$view = ++$a_item[$model]['view'];

			$this->$model->id = $id;
			$this->$model->set(array('view'=>$view));
			$this->$model->save();

			//Lưu lại session
			$item[$id] = $current_time;
			$this->Session->write('Increase.'.$model,$item);
		}
	}

	// Lấy slug của tag
	function _getSlugForTag($tag){
		$a_tags = $this->_getTag($tag,'arr');

		$tmp = array();
		foreach($a_tags as $val){
			$tag = $this->_getIdOfTag($val);
			if ( ! empty($tag['Tag'])) {
				$tag = $tag['Tag'];
				$tmp[] = array(
					'name'				=> $this->capitalFirstLetterVietnamese($val),
					'id'					=> $tag['id'],
					'slug'				=> $tag['slug'],
					'meta_title'	=> $val
				);
			}
		}
		return $tmp;
	}

	// Chuyển tất cả ký tự tiếng Việt thành không dấu và nối bằng dấu -
	public function converToSlug($str){
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);

		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		$str = strtolower($str);

		return Inflector::slug($str,'-');
	}

	// Viết hoa chữ cái đầu tiên tiếng Việt
	function capitalFirstLetterVietnamese($string, $encoding='utf8') {
		$strlen = mb_strlen($string, $encoding);
		$firstChar = mb_substr($string, 0, 1, $encoding);
		$then = mb_substr($string, 1, $strlen - 1, $encoding);
		return mb_strtoupper($firstChar, 'utf8').mb_strtolower($then, 'utf8');
	}

    public function getDateRange($startDate, $endDate, $format = "d/m/y")
    {
        $datesArray = array();
        $days = 0;
        $total_days = round(abs(strtotime($endDate) - strtotime($startDate)) / 86400, 0) + 1;
        if ($days < 0) {
            return false;
        }
        for ($day = 0; $day < $total_days; $day++) {
            $datesArray[] = date($format, strtotime("{$startDate} + {$day} days"));
        }
        return $datesArray;
    }

	function _getTag($tag,$type='str'){
		$tag = explode(',', $tag);
		$str_tag = '';
		if(!empty($tag)){
			$tmp=array();
			foreach($tag as $val){
				$val = trim($val);
				if(!empty($val)){
					$tmp[] = $this->capitalFirstLetterVietnamese($val);
				}
			}
			$tag = array_unique($tmp);
			if($type=='str'){
				for($i=0;$i<count($tag);$i++){
					$str_tag.=$tag[$i];
					if(!empty($tag[$i+1])) $str_tag.=', ';
				}
				$tag = $str_tag;
			}
		}
		return $tag;
	}

	/**
	* @Description	: Kiểm tra tag đã có trong bảng tags chưa, nếu chưa có thì thêm vào, nếu có rồi thì tăng number lên 1
	*
	* @param		: string $tag, $tag_old = null (tag cũ: sdung khi edit)
	* @Author		: Hoang Tuan Anh - tuananh@url.vn
	*/
	function _checkTag($tag,$tag_old = null){
		$tag = $this->_getTag($tag,'arr');

		if(!empty($tag_old)){
			$tag_old = $this->_getTag($tag_old,'arr');
			$this->_checkTagEdit($tag, $tag_old);
		}else $this->_addTag($tag);
	}


	/**
	* @Description	: Cập nhật lại tag khi xóa sản phẩm hoặc bài viết
	* 				  Kiểm tra nếu number <=1 thì xóa, >1 thì giảm đi 1
	*
	* @param		: string $tag
	* @param		: int	$item_id
	* @Author		: Hoang Tuan Anh - tuananh@url.vn
	*/
	function _checkDelTag($tag,$item_id){
		$a_tags = $this->_getTag($tag,'arr');
		if(!empty($a_tags)){
			$this->_editTag($a_tags);
		}

		//Xoa toan bo tag_priorities cua ban ghi nay
		$this->_delAllTagPriority($item_id);
	}



	/**
	* @Description	: Cập nhật lại tag khi sửa sản phẩm hoặc bài viết, hàm này được gọi thông qua hàm _checkTag() ở trên
	*
	* @param		: array $tag, $tag_old
	* @Author		: Hoang Tuan Anh - tuananh@url.vn
	*/
	function _checkTagEdit($tag,$tag_old){
		//Kiểm tra đã xóa bỏ những tag nào
		$a_delete_tags = array();
		foreach($tag_old as $val){
			if(!in_array($val, $tag)) $a_delete_tags[]=$val;
		}

		if(!empty($a_delete_tags)){
			$this->_editTag($a_delete_tags);
		}

		//Them tag moi
		$a_new_tags = array();
		foreach($tag as $val){
			if(!in_array($val, $tag_old)) $a_new_tags[]=$val;
		}
		if(!empty($a_new_tags)) $this->_addTag($a_new_tags);
	}


	/**
	* @Description	: Kiểm tra tag tryền vào, nếu chưa có thì thêm vào, nếu có rồi thì tăng number lên 1
	*
	* @param		: array $tag
	* @Author		: Hoang Tuan Anh - tuananh@url.vn
	*/
	function _addTag($tag){
		$lang = $this->Session->read('lang');
		$this->loadModel('Tag');

		// Chuẩn hoá tag đầu vào thành chữ cái đầu tiên viết hoa
		$tmp = array();
		foreach($tag as $val){
			$tmp[]=$this->capitalFirstLetterVietnamese($val);
		}
		$tag = $tmp;

		//Kiểm tra, nếu đã tồn tại tag ở bảng tag, thì tăng number lên 1.
		$a_get_tags = $this->Tag->find('all',array('conditions'=>array('name'=>$tag,'lang'=>$lang),'fields'=>array('id','name','number')));
		if(!empty($a_get_tags)){
			$tmp = array();
			foreach($a_get_tags as $val){
				$this->Tag->id = $val['Tag']['id'];
				$this->Tag->set(array(
									'name_base64'=>base64_encode($this->capitalFirstLetterVietnamese($val['Tag']['name'])),
									'number'=>($val['Tag']['number']+1)
								));
				$this->Tag->save();
				$tmp[]=$val['Tag']['name'];
			}
			$tag = array_diff($tag, $tmp);
		}

		//Nếu chưa có thì thêm mới vào tags
		if(!empty($tag)){
			foreach($tag as $val){
				if(!empty($val)){
					$data['Tag']['name']=$this->capitalFirstLetterVietnamese($val);
					$data['Tag']['name_base64']=base64_encode($data['Tag']['name']);
					$data['Tag']['slug']=$this->converToSlug($data['Tag']['name']);
					$data['Tag']['meta_title']=$val;
					$data['Tag']['lang']=$lang;
					$data['Tag']['number']=1;
					$data['Tag']['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
					$this->Tag->create();
					$this->Tag->save($data);
				}
			}
		}
	}

	// Lấy id của tag
	function _getIdOfTag($name) {
		$this->loadModel('Tag');
		$tag = $this->Tag->find('first', array(
			'conditions' => array('name_base64' => base64_encode($this->capitalFirstLetterVietnamese($name))),
			'fields'=>array('id', 'name', 'slug')
		));
		return $tag;
	}

	/**
	* @Description	: Kiem tra tag truyen vao, neu tag có number <=1 thì xóa, >1 thì giảm đi 1
	*
	* @param		: array $tag
	* @Author		: Hoang Tuan Anh - tuananh@url.vn
	*/
	function _editTag($tag){
		$lang = $this->Session->read('lang');
		$this->loadModel('Tag');
		$a_get_tags = $this->Tag->find('all',array('conditions'=>array('name'=>$tag,'lang'=>$lang),'fields'=>array('id','name','number')));

		if(!empty($a_get_tags)){
			foreach($a_get_tags as $val){
				if($val['Tag']['number']<=1){	//Xóa tag có number <=1
					$this->Tag->delete($val['Tag']['id']);
				}else{
					$this->Tag->id = $val['Tag']['id'];
					$this->Tag->set('number',$val['Tag']['number']-1);
					$this->Tag->save();
				}
			}
		}
	}


	/**
	* @Description	: Thiết lập lại tham số ưu tiên của tag, hàm được gọi khi edit hoặc thêm mới sp
	*
	* @param	: str 		$tag
	* @param	: int		$item_id
	* @return	: array
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	function _setTagPriority($tag,$item_id,$model=null,$action=null){
		if(empty($item_id)) return ;
		$lang = $this->Session->read('lang');

		if($action==null) $action = $this->params['action'];
		if($model==null) $model=$this->modelClass;   // goi ten model dau tien trong mang model su dung cua controller hien tai

		if(!empty($tag)){
			$this->loadModel('Tag');

			$a_tags = $this->_getTag($tag,'arr');
			//Tim id cua tag trong ban tags
			$a_tag_ids = $this->Tag->find('all',array('conditions'=>array('name'=>$a_tags,'lang'=>$lang),'fields'=>array('id','name')));
			$tmp = array();
			$i=0;
			foreach($a_tags as $key=>$val){
				$flag = false;
				$j=0;

				while (!$flag && $j<count($a_tag_ids)){
					if($val==$a_tag_ids[$j]['Tag']['name']){
						$tmp[$i]['model']=$model;
						$tmp[$i]['item_id']=$item_id;
						$tmp[$i]['tag_id']=$a_tag_ids[$j]['Tag']['id'];
						$tmp[$i]['lang']=$lang;
						$tmp[$i]['position']=$i+1;
						$flag = true;
					}
					$j++;
				}
				$i++;
			};
			$a_tags = $tmp;

			if($action=='admin_add'){
				$this->_addTagPriority($a_tags);
			}
		}else{
			$a_tags=array();
		}

		if($action=='admin_edit'){
			$this->_editTagPriority($a_tags,$item_id,$model);
		}
	}

	/**
	* @Description	:
	*
	* @param	: array $data
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	function _addTagPriority($data){
		$this->loadModel('TagPriority');
		$this->TagPriority->saveAll($data);
	}

	/**
	* @Description	:
	*
	* @param	: array $data
	* @param	: int	$item_id
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	function _editTagPriority($data,$item_id,$model){
		if(empty($item_id)) return ;
		$lang = $this->Session->read('lang');

		if(empty($data[0])){
			$this->_delAllTagPriority($item_id);
			return ;
		}
		$this->loadModel('TagPriority');
		$a_tag_priorities = $this->TagPriority->find('all',array('conditions'=>array('item_id'=>$data[0]['item_id'],'TagPriority.lang'=>$lang,'TagPriority.model'=>$model),'fields'=>array('id','item_id','tag_id')));	//Danh sach tag da luu trong table tag_priorities

		$a_tag_ids = array();	//Mang id tag;
		foreach($data as $val){
			$a_tag_ids[]=$val['tag_id'];
			$i=0;
			$flag = false;
			if(!empty($a_tag_priorities)){
				do{
					if($val['item_id']==$a_tag_priorities[$i]['TagPriority']['item_id'] && $val['tag_id']==$a_tag_priorities[$i]['TagPriority']['tag_id']){
						//Neu da tồn tại thì sửa lại vị trí ưu tiên
						$this->TagPriority->id = $a_tag_priorities[$i]['TagPriority']['id'];
						$this->TagPriority->set('position',$val['position']);
						$this->TagPriority->save();
						$flag=true;
					}
					$i++;
				}while(!$flag && $i<count($a_tag_priorities));
			}


			//Trường họp chưa có trong tag_positions thì ghi mới
			if(!$flag){
				$this->TagPriority->create();
				$this->TagPriority->save($val);
			}
		}

		//Kiểm tra đã có tag nào được xóa chưa, nếu xóa rồi thì phải xóa trong table tag_priorities
		foreach($a_tag_priorities as $val){
			if(!in_array($val['TagPriority']['tag_id'], $a_tag_ids)){
				$this->TagPriority->deleteAll(array('tag_id'=>$val['TagPriority']['tag_id'],'item_id'=>$val['TagPriority']['item_id'],'model'=>$model));
			}
		}
	}

	/**
	* @Description	: Xoa toan bo tag theo item_id, model trong table: tag_priorities
	*
	* @param	: int	$item_id
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	function _delAllTagPriority($item_id){
		$model=$this->modelClass;
		$this->loadModel('TagPriority');
		$this->TagPriority->deleteAll(array('item_id'=>$item_id,'model'=>$model));
	}


	/**
	 * @Description :	Lấy slug sử dụng ajax
	 *
	 * @params 	:
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxGetSlug(){
		$this->layout = false;
		$this->autoRender = false;

		if(empty($_POST['val'])) throw new NotFoundException(__('Invalid',true));

		return $this->Oneweb->slug($_POST['val'],array());
	}


	/*
	* @Description	: Ghi lại ip truy cập vào web
	*
	* @param	: int 	$total (Tổng link),
	* 					$continuity (Tổng link liên tục của 1 ip),
	*			 		$time (Thời gian reset lại - phút),
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	private function _logIp($total,$continuity,$time){
		//Danh sach cac link loai bo, ko can kiem tra
		$linkRemove = array('/www.URL.vn/pages/checkOrderAndContact','/products/ajChangeTime/request:ajax');
		$link = $_SERVER['REQUEST_URI'];

		if(!in_array($link, $linkRemove)){
			$list = file('log_ip.log',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

			//Kiểm tra thời gian, nếu quá 5ph thì reset lại toàn bộ
			$current_time = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			if($current_time-@$list[0]>$time*60){
				$log = fopen('log_ip.log','w+');
				fwrite($log, $current_time."\n");
				fclose($log);

				$log_lock = fopen('lock_ip.log','w+');
				fwrite($log_lock, "");
				fclose($log_lock);
			}

			//Get Ip or Proxy
			if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}else{
				$ip = $_SERVER['REMOTE_ADDR'];
			}

			$log = fopen('log_ip.log','a');
			fwrite($log, $ip." - ".$link."\n");
			fclose($log);

			$lock = 1;
			$last = count($list) - 1;

			if($last >$total){		//Nếu số truy cập liên tiếp tới tất cả các link vượt quá 1000 lần sẽ bị khóa (Deny from all)
				//Khóa toan bo ip truy cap den web
				$log_ip = fopen('lock_ip.log','a');

				fwrite($log_ip, "all\n");
				fclose($log_ip);
			}elseif($last > $continuity){
				for($i = 1; $i < $continuity; $i++) if($list[$last - $i] != $list[$last]) $lock = 0;
			}else{
				$lock = 0;
			}


			if($lock){
				//Khóa ip hiện tại
				$log_ip = fopen('lock_ip.log','a');

				fwrite($log_ip, $ip."\n");
				fclose($log_ip);
			}
		}
	}

	/**
	 * @Description : Kiểm tra ip có bị khóa không
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _lockIp(){
		//Get Ip or Proxy
		if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$list_lock = file('lock_ip.log',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		if(in_array($ip, $list_lock) || in_array('all', $list_lock)) $lock = true;
		else $lock = false;
		return $lock;
	}


	/**
	 * @Description : Reset lại file htaccess
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int $time: Số phút để reset lại
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _resetHtaccess($time){
		$current_time = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		//Lock htaccess - level 2      ---- Reset lại file log2_ip sau $time2 phút
		$list = file('log2_ip.log',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if($current_time-@$list[0]>$time*60){
			$htaccess = file('.htaccess',FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			$in = '';
			$flag = true;
			$i=0;
			do{
				$in.= $htaccess[$i]."\n";
				if($htaccess[$i]=='</IfModule>') $flag = false;
				$i++;
			}while($flag && $i<count($htaccess));

			$reset = fopen('.htaccess','w+');
			fwrite($reset, $in);
			fclose($reset);

			$in = $current_time."\n";
			$reset = fopen('log2_ip.log','w+');
			fwrite($reset, $in);
			fclose($reset);
		}
	}
	/**
	 * Lấy thông tin quảng cáo
	 *
	 */
	function _getAdv($position,$lang){
		$result = $this->Advertisement->find('all',array(
				'conditions'=>array('status'=>1,'position'=>$position,'trash'=>0,'lang'=>$lang),
				'fields'=>array('position','content')
		));
		return $result;
	}
}
