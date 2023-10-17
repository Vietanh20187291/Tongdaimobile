<?php
App::uses('AppController', 'Controller');
/**
 * Products Controller
 *
 * @property Product $Product
 */
class ProductsController extends AppController {

	public $helpers = array('CkEditor');
	public $components = array('Upload');
	private $limit_admin = 50;
	private $limit = 100;
	private $limit_readmore = 100;
	/**
	 * @Description : Điều hướng xem danh sách sản phẩm hay chi tiết sản phẩm
	 *
	 * @throws 	: NotFoundException
	 * @param 	: string slug
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function index($slug=null) {
		if($slug==null || empty($this->params['lang'])) throw new  NotFoundException(__('Trang này không tồn tại',true));
		if($this->params['slug0'] == 'khuyen-mai-hot') {
			$this->_promotion();
			$this->render('promotion');
		}elseif($this->params['slug0'] == 'san-pham-ban-chay') {
			$this->_bestsell();
			$this->render('bestsell');
		}elseif($this->params['slug0'] == 'san-pham-moi-nhat') {
			$this->_newest();
			$this->render('newest');
		}elseif(empty($this->params['ext'])){			//Điều hướng xem danh sách sản phẩm
			$this->_list($slug);
			$this->render('list');
		}else{			//Điều hướng xem chi tiết sản phẩm
			$this->_view($slug);
			$this->render('view');
		}
	}


	/**
	 * @Description : Danh sách sản phẩm
	 *
	 * @throws 	: NotFoundException
	 * @param 	: str $slug
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _list($slug=null){
		$this->set('class','list_product');
		$oneweb_product = Configure::read('Product');

		$a_params = $this->params;
		$lang = $a_params['lang'];

//        $slug1 = preg_replace('/-thuong-hieu-(.*)/', '', $slug);
//        $get_url = explode('/', $this->request->url);
//        if(!empty($get_url[0])){
//            $url = explode("-thuong-hieu-", $get_url[0]);
//            if(!empty($url[1])){
//                $branch = explode('&', $url[1]);
//            }
//        }
		//Đọc thông tin danh mục
		$a_category = Cache::read("product_list_$slug".'_'.$lang,'oneweb');
		if(empty($a_category)){
			$a_category = $this->Product->ProductCategory->find('first',array(
				'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'slug like'=>$slug,'or'=>array(array('link'=>null),array('link'=>''))),
				'fields'=>array('id','name','lang','banner','banner_link','slug','path','description', 'description2','image', 'meta_title','meta_keyword','meta_description','meta_robots'),
				'recursive'=>-1
			));

			Cache::write("product_list_$slug".'_'.$lang,$a_category,'oneweb');
		}

		//Set ảnh chia sẻ
		
		$oneweb_product = Configure::read('Product');
		$path = realpath($oneweb_product['path']['category']).DS;		//Đường dẫn file ảnh
		if(!empty($a_category['ProductCategory']['image']) && file_exists($path.$a_category['ProductCategory']['image']))
			$this->set('og_image', '/images/product_categories/'.$a_category['ProductCategory']['image']);

		if(empty($a_category)) throw new NotFoundException(__('Trang này không tồn tại',true));
		$a_category = $a_category['ProductCategory'];
		$a_ids = array($a_category['id']);		//Id của mục này và các mục con của nó
		$a_ids2 = $a_ids;

		//Tìm các danh mục con trực tiếp
		$a_child_direct_categories = $this->Product->ProductCategory->find('all',array(
				'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'parent_id'=>$a_category['id']),
				'fields'=>array('id','name','slug','lang','path','meta_title','rel','target','link','image','status','counter'),
				'order'=>array('lft'=>'asc','name'=>'asc'),
				'recursive'=>-1
		));
		//Tìm tất cả id danh mục con, bao gồm cả danh mục ko trực tiếp
		if(!empty($a_child_direct_categories)){		//Tồn tại danh mục con

			//Tìm id của các danh mục con (bao gồm cả trực tiếp và ko trực tiếp)
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

		$this->paginate = array(
				'conditions'=>array('Product.lang'=>$lang,'Product.status'=>1,'Product.trash'=>0,'Product.product_category_id'=>$a_category['id']),
				'contain'=>array('ProductCategory','ProductMaker'),
				'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.price_new','Product.slug','Product.meta_title','Product.rel','Product.target','Product.image','Product.price','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.public','Product.summary',
						'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
				),
				'order'=>array('Product.sort'=>'asc'),
				'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
				'limit'=>$this->limit
		);
		$a_listsidebar  = $this->paginate();
		$this->set('a_listsidebar',$a_listsidebar);

		$a_conditions2[] = array('product_category_id'=>$a_ids);
		for ($i=0;$i<count($a_ids);$i++){
			$a_conditions2[] = array('category_other like'=>'%-'.$a_ids[$i].'-%');
		}

		//Danh sách sản phẩm
		//Ngay hien tai
		$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		$a_conditions = array('Product.lang'=>$lang,'Product.status'=>1,'Product.trash'=>0,'or'=>$a_conditions2,'Product.public <='=>$date_current);
		if(!empty($oneweb_product['maker'])) {
			$a_conditions = array_merge($a_conditions,array('ProductMaker.status'=>1,'ProductMaker.trash'=>0));
        if (!empty($branch)){
            $a_branch = $this->Product->ProductMaker->find('list',array(
                'conditions'=>array('slug'=>$branch),
                'fields'=>array('id'),
                'recursive'=>-1
            ));
            $a_conditions = array_merge($a_conditions,array('product_maker_id'=>$a_branch));
            $this->set('branch', $branch);

        }
		// Lọc sản phẩm danh mục theo nhiều thương hiệu
		if ( ! empty($_GET['maker_id'])) {
			$maker_temp = explode("-", $_GET['maker_id']);
            $a_makers = $this->Product->ProductMaker->find('list',array(
                'conditions'=>array('slug'=>$maker_temp),
                'fields'=>array('id'),
                'recursive'=>-1
            ));
            $last_key = count($a_makers);
            $maker_slug = '';
			// print_r($a_makers);die();
            foreach ($a_makers as $key=>$item){
				if(!empty($item['ProductMaker']['slug'])){
					$maker_slug = $maker_slug.$item['ProductMaker']['slug'];
				}                if (++$key != $last_key){
                    $maker_slug = $maker_slug.'&';
                }
            }
			$a_conditions = array_merge($a_conditions,array('product_maker_id'=>$a_makers));
            $this->set('maker_arr', $maker_temp);
		}

		// Lọc sản phẩm danh mục theo khoảng giá
		if ( ! empty($_GET['price_range_id'])) {
			$price_range_id = $_GET['price_range_id'];

				if ($price_range_id == '1') {
					$min_a = 0;
					$max_a = 1000000;
				}
				if ($price_range_id == '2') {
					$min_a = 1000000;
					$max_a = 2000000;
				}
				if ($price_range_id == '3') {
					$min_a = 2000000;
					$max_a = 5000000;
				}
				if ($price_range_id == '4') {
					$min_a = 5000000;
					$max_a = 10000000;
				}
				if ($price_range_id == '5') {
					$min_a = 10000000;
					$max_a = 20000000;
				}
				if ($price_range_id == '6') {
					$min_a = 20000000;
					$max_a = 999999999999;
				}

				$a_conditions = array_merge($a_conditions,array("Product.price < "=>$max_a, "Product.price >="=>$min_a));
			$this->set('price_range_id', $price_range_id);
		}

			//Tìm danh sách hãng sản xuât theo danh mục
			$a_makers = Cache::read('product_maker_'.$slug.'_'.$lang);
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
				Cache::write('product_maker_'.$slug.'_'.$lang,$a_makers,'oneweb');
			}
			$this->set('a_product_makers_list',$a_makers);
		}

		$a_orders = array();
		if(empty($a_params['sort']) && empty($a_params['direction'])){
			$a_orders = array('Product.sort'=>'asc','created'=>'desc','name'=>'asc');
		}else{
			$a_orders = array($a_params['sort']=>$a_params['direction'],'Product.sort'=>'asc','created'=>'desc');
		}
		$limit = $this->limit;

		// lấy parent id danh mục hiển tại
		$a_category_current = $this->Product->ProductCategory->find('first',array(
				'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'slug'=>$slug),
				'fields'=>array('id','name','parent_id'),
				'recursive'=>-1
		));
		//lấy tên danh mục cha
		if(!empty($a_category_current)){
			$a_category_parent_current = $this->Product->ProductCategory->find('first',array(
					'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'id'=>$a_category_current['ProductCategory']['parent_id']),
					'fields'=>array('id','name','parent_id'),
					'recursive'=>-1
			));
		}
		//lấy list danh mục theo parent id trên
		$a_child_direct_categories_parent = $this->Product->ProductCategory->find('all',array(
				'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'parent_id'=>$a_category_current['ProductCategory']['parent_id']),
				'fields'=>array('id','name','slug','lang','path','meta_title','rel','target','link','image','status','counter'),
				'order'=>array('lft'=>'asc','name'=>'asc'),
				'recursive'=>-1
		));
		$this->paginate = array(
				'conditions'=>$a_conditions,
				'contain'=>array('ProductCategory','ProductMaker'),
				'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.price_new','Product.slug','Product.meta_title','Product.rel','Product.target','Product.image','Product.price','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.public','Product.like','Product.summary',
						'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
				),
				'order'=>$a_orders,
				'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
				'limit'=>$this->limit_readmore
		);
		$a_products = $this->paginate();

        //Tìm bài viết liên quan (Liên quan theo danh mục)
        $a_related_posts = Cache::read("product_category_view_relate_$slug",'oneweb');
        if(!$a_related_posts){

            $this->loadModel('Post');
            $this->loadModel('PostCategory');
            $a_child_categories = $this->Post->PostCategory->children($a_category['id'],false,array('id','status','trash'));

            foreach ($a_child_categories as $val){
                $item_cate = $val['PostCategory'];
                if($item_cate['status'] && !$item_cate['trash']) $a_ids[] = $item_cate['id'];
            }

            $a_related_posts = $this->Post->find('all',array(
                'conditions'=>array('product_category_id'=>$a_category['id'],'Post.status'=>1,'Post.trash'=>0,'PostCategory.status'=>1,'PostCategory.trash'=>0,'Post.lang'=>$lang,'Post.slug !='=>$slug,'Post.public <='=>$date_current),
                'fields'=>array('Post.id','Post.lang','Post.name','Post.slug','Post.meta_title','Post.rel','Post.image','Post.summary','Post.target','Post.created',
                    'PostCategory.id','PostCategory.name','PostCategory.slug','PostCategory.meta_title','PostCategory.path,PostCategory.position',
                ),
                'order'=>'Post.view desc',
                'limit'=>6,
                'recursive'=>0
            ));

            Cache::write("product_category_view_relate_$slug",$a_related_posts,'oneweb');
        }

		$this->set('a_products_c',$a_products);
        $this->set('a_related_posts_c',$a_related_posts);

		$this->set('a_category_parent_current',$a_category_parent_current);
		$this->set('a_child_direct_categories_parent',$a_child_direct_categories_parent);

		$this->set('a_category_c',$a_category);
		$this->set('a_child_direct_categories',$a_child_direct_categories);


		//Breadcrumb

		$this->set('a_breadcrumb_c',$this->_getBreadcrumbCategory($a_category['id']));

		if(!empty($_GET['maker_id'])) $a_category['meta_robots'] = 'noindex,nofollow';

		//SEO
		$this->set('title_for_layout',$a_category['meta_title']);
		$this->set('meta_keyword_for_layout',$a_category['meta_keyword']);
		$this->set('meta_description_for_layout',$a_category['meta_description']);
		$this->set('meta_robots_for_layout',$a_category['meta_robots']);

        $current_url = $this->Oneweb->curPageURL();
        if(strpos($current_url,'?')) $current_url = substr($current_url,0,strpos($current_url,'?'));
        $current_url = preg_replace('/:443/', '', $current_url);

        // Tùy chỉnh SEO cho các link

        if(!empty($a_products)) {
            $this->loadModel('SeoLink');
            $current_url = preg_replace('/\/page-(.*)/', '', rtrim($current_url, '/'));
            $current_url = preg_replace('/&(.*)_dia-diem-(.*)/', '_dia-diem-$2', $current_url);
            $current_url = explode('&', $current_url)[0];
            $a_seo_link = $this->SeoLink->find('first', array(
                'conditions' => array('link' => $current_url)
            ));
            if(empty($a_seo_link)) {
                if(!empty($current_url)) {
                    $data_seo_link['link'] = $current_url;
                    $data_seo_link['model'] = 'Product';
                    $data_seo_link['name'] = 'Product';
                    $data_seo_link['status'] = 1;
                    $data_seo_link['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

                    $this->SeoLink->create();
                    $this->SeoLink->save($data_seo_link);
                }
            } else {
                //visit
                $this->SeoLink->id = $a_seo_link['SeoLink']['id'];
                $this->SeoLink->saveField('visit', ($a_seo_link['SeoLink']['visit']+1));

                if(!empty($a_seo_link['SeoLink']['status'])) {
                    $title_for_layout = $a_seo_link['SeoLink']['meta_title'];
                    $meta_keyword = $a_seo_link['SeoLink']['meta_keyword'];
                    $meta_description = $a_seo_link['SeoLink']['meta_description'];
                    $meta_robots = $a_seo_link['SeoLink']['meta_robots'];
                }

                if(empty($a_seo_link['SeoLink']['meta_title'])) {
                    if(!empty($a_menu)) $title_for_layout = $a_menu['Menu']['meta_title'];
                    else $title_for_layout = __('Tìm dự án');
                }

                $this->set('a_seo_link', $a_seo_link);
            }
        }

        //Canonical
		$a_canonical = array('controller'=>'products','action' => 'index','lang'=>$lang);
				$tmp = explode(',', $a_category['path']);
				$a_canonical = array_merge($a_canonical,array('slug0'=>$tmp[count($tmp)-1]));
				if(!empty($a_params['page']) && $a_params['page']>1) $a_canonical = array_merge($a_canonical,array('page'=>$a_params['page']));
				if(!empty($a_params['sort']) && !empty($a_params['direction'])) $a_canonical = array_merge($a_canonical,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
				if(!empty($_GET['maker_id'])) $a_canonical=array_merge($a_canonical,array('?'=>array('maker_id'=>$_GET['maker_id'])));
				if(!empty($branch)) $a_canonical = array_merge($a_canonical,'-thuong-hieu-'.$branch);
//                var_dump($a_canonical);exit;

		if(!empty($_GET['price_range_id'])) $a_canonical=array_merge($a_canonical,array('?'=>array('price_range_id'=>$_GET['price_range_id'])));

		$this->set('a_canonical',$a_canonical);		
	}
	



	/**
	 * @Description : Chi tiết sản phẩm
	 *
	 * @throws 	: NotFoundException
	 * @param 	: str $slug
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _view($slug=null){
		$this->set('column_right',false);		//Thiết lập loại bỏ cột phải
		$this->set('column_left',false);		//Thiết lập loại bỏ cột trái
		$this->set('class','detail_product');	//Class rieng cua trang
		$this->set('active_slideshow',false);	//Khong cho hien thi slideshow o day

		$oneweb_product = Configure::read('Product');
		$lang = $this->params['lang'];

		$a_product = Cache::read("product_view_$slug".'_'.$lang,'oneweb');

		if(empty($a_product)){
			$a_conditions = array('Product.status'=>1,'Product.trash'=>0,'ProductCategory.status'=>1,'ProductCategory.trash'=>0,'Product.slug'=>$slug,'Product.lang'=>$lang);

			if(!empty($oneweb_product['maker'])) $a_conditions = array_merge($a_conditions,array('ProductMaker.status'=>1,'ProductMaker.trash'=>0));

			$this->Product->unbindModel(array(
				'belongsTo'=>array('ProductTax','User'),
				'hasMany'=>array('Comment','ProductOption','ProductProperty')
			));
			$a_product = $this->Product->find('first',array(
				'conditions'=>$a_conditions,
				'contain' => array('ProductMaker', 'ProductCategory', 'ProductImage'),
				'fields'=>array('Product.*','ProductCategory.id','ProductCategory.name','ProductCategory.path','ProductMaker.name', 'ProductMaker.slug', 'ProductMaker.target', 'ProductMaker.meta_title')
			));

			if(empty($a_product)) throw new NotFoundException(__('Trang này không tồn tại',true));
			$a_product['Product']['tag'] = $this->_getSlugForTag($a_product['Product']['tag']);
			$a_product['Product']['description'] = @unserialize($a_product['Product']['description']);

			Cache::write("product_view_$slug".'_'.$lang,$a_product,'oneweb');
		}

		$this->set('a_product_c',$a_product);

		//Đọc thông tin cấu hình
		$a_product_configs = $this->_getConfig('product');
		$this->set('a_product_configs_c',$a_product_configs);

		//Sản phẩm liên quan (liên quan theo danh mục)

		$a_related_products = Cache::read("product_view_re_$slug".'_'.$lang,'oneweb');
		//Ngay hien tai
		$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		if(empty($a_related_products)){
			$a_ids = array($a_product['ProductCategory']['id']);		//Id của danh mục này và các danh mục con của nó

			$a_child_categories = $this->Product->ProductCategory->children($a_product['ProductCategory']['id'],false,array('id','status','trash'));
			foreach ($a_child_categories as $val){
				$item_cate = $val['ProductCategory'];
				if($item_cate['status'] && !$item_cate['trash']) $a_ids[] = $item_cate['id'];
			}
            $this->loadModel('ProductCategory');
            $a_parent_id = $this->ProductCategory->find('first',array(
                'conditions'=>array('ProductCategory.id'=>$a_ids),
                'fields'=>array('ProductCategory.parent_id'),
                'recursive'=>0
            ));


            $parent_id = $a_parent_id['ProductCategory']['parent_id'];
            if(!empty($parent_id)){	//Danh muc

                $a_cate_ids = array($parent_id);

                    //Tìm các danh mục con
                    $a_child_categories = $this->Product->ProductCategory->children($parent_id,false,array('id'));
                    foreach ($a_child_categories as $val){
                        $item = $val['ProductCategory'];
                        $a_cate_ids[] = $item['id'];
                    }


                $cate_conditions = array('product_category_id'=>$a_cate_ids);
                foreach($a_cate_ids as $val){
                    $cate_conditions[] = array('category_other like'=>'%-'.$val.'-%');
                }
                $a_related_conditions_parent = array('Product.status'=>1,'Product.trash'=>0,'ProductCategory.status'=>1,'ProductCategory.trash'=>0,'Product.lang'=>$lang,'Product.slug !='=>$slug,'Product.public <='=>$date_current);
                $a_related_conditions_parent = array_merge($a_related_conditions_parent,array('or'=>$cate_conditions));
            }

			$a_related_conditions = array('product_category_id'=>$a_ids,'Product.status'=>1,'Product.trash'=>0,'ProductCategory.status'=>1,'ProductCategory.trash'=>0,'Product.lang'=>$lang,'Product.slug !='=>$slug,'Product.public <='=>$date_current);
		
			if(!empty($oneweb_product['maker'])) $a_related_conditions = array_merge($a_related_conditions,array('ProductMaker.status'=>1,'ProductMaker.trash'=>0));

			$a_related_products = $this->Product->find('all',array(
				'conditions'=>$a_related_conditions,
				'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.price_new','Product.slug','Product.meta_title','Product.rel','Product.target','Product.description','Product.image','Product.price','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.public','Product.summary',
								'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
							),
				'order'=>'rand()',
				// 'limit'=>1,
				'recursive'=>0
			));

//             $a_related_conditions_parent = $this->Product->find('all',array(
//                 'conditions'=>$a_related_conditions_parent,
//                 'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.price_new','Product.slug','Product.meta_title','Product.rel','Product.target','Product.description','Product.image','Product.price','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.public','Product.summary',
//                     'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
//                 ),
//                 'order'=>array('Product.sort'=>'desc'),
// //                'limit'=>15,
//                 'recursive'=>0
//             ));
			
//             $a_related_products = array_merge($a_related_products,$a_related_conditions_parent);
		
			Cache::write("product_view_re_$slug".'_'.$lang,$a_related_products,'oneweb');
		}

		$this->set('a_related_products_c',$a_related_products);


		//Danh sách sản phẩm, phụ kiện liên quan tùy chọn

		$a_option_related = Cache::read("product_view_re2_$slug".'_'.$lang,'oneweb');

		if(empty($a_option_related)){
			$a_option_related_code = explode(',', $a_product['Product']['related']);
			$a_option_related = $this->Product->find('all',array(
				'conditions'=>array('Product.code'=>$a_option_related_code,'Product.status'=>1,'Product.trash'=>0,'ProductCategory.status'=>1,'ProductCategory.trash'=>0,'Product.lang'=>$lang,'Product.public <='=>$date_current),
				'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.slug','Product.meta_title','Product.rel','Product.target','Product.image','Product.price','Product.quantity','Product.discount','Product.discount_unit','Product.public','Product.promotion','Product.summary',
						'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
				),
				'order'=>'rand()',
				'recursive'=>0
			));
			Cache::write("product_view_re2_$slug".'_'.$lang,$a_option_related,'oneweb');
		}
		$this->set('a_option_related_c',$a_option_related);

		//sản phẩm đã xem
		$this->_productViewed($a_product);
		$this->productViewedShow();//hiển thị sản phẩm đã xem
		//Lấy thông tin giỏ hàng cho form đặt hàng nhanh phía dưới cùng
		$order_info = $this->getDetailCart();
		$this->set('order_info_c', $order_info);

		//Set ảnh chia sẻ
		$oneweb_product = Configure::read('Product');
		$path = realpath($oneweb_product['path']['product']).DS;		//Đường dẫn file ảnh
		if(!empty($a_product['Product']['image']) && file_exists($path.$a_product['Product']['image']))
			$this->set('og_image', '/images/products/'.$a_product['Product']['image']);

		//Tăng lượt xem
		$this->_increaseView($a_product['Product']['id']);
		//plugin attribute product color, size
		if(!empty($oneweb_product['atribute'])){
			$this->loadModel('ProductAttribute');
			$product_attributes = $this->ProductAttribute->find('all', array(
			    	'contain'=>array('ProductColor'),
			    	'conditions'=>array('ProductAttribute.product_id'=>$a_product['Product']['id']),
			    	'fields'=>array('ProductAttribute.*', 'ProductColor.*','ProductSize.*'),
			    	'recursive'=>0
			    ));
			$list_product_color = array();
			$list_product_size = array();
			if(!empty($product_attributes)) {
				foreach ($product_attributes as $key => $value) {
					$color_id = $value['ProductColor']['slug'];
					$size_id = $value['ProductSize']['id'];
					$list_product_color[$color_id] = $value['ProductColor']['color'];
					$list_product_size[$size_id] = $value['ProductSize']['size'];
				}
			}
			// $list_product_color = $this->ProductAttribute->find('list', array(
			//     	'contain'=>array('ProductColor'),
			//     	'conditions'=>array('ProductAttribute.product_id'=>$a_product['Product']['id']),
			//     	'fields'=>array('ProductAttribute.*', 'ProductColor.*','ProductSize.*'),
			//     	'recursive'=>0
			//     ));
			$this->set(compact('product_attributes', 'list_product_color', 'list_product_size'));
		}
		//Breadcrumb
		$a_breadcrumb = Cache::read("product_view_bc_$slug".'_'.$lang,'oneweb');
		if(empty($a_breadcrumb)){
			$a_breadcrumb = $this->_getBreadcrumbCategory($a_product['ProductCategory']['id']);
			$a_breadcrumb[] = array(
				'name'=>$a_product['Product']['name'],
				'meta_title'=>$a_product['Product']['meta_title'],
				'url'=>''
			);
			Cache::write("product_view_bc_$slug".'_'.$lang,$a_breadcrumb,'oneweb');
		}
		$this->set('a_breadcrumb_c',$a_breadcrumb);


		//SEO
		$this->set('title_for_layout',$a_product['Product']['meta_title']);
		$this->set('meta_keyword_for_layout',$a_product['Product']['meta_keyword']);
		$this->set('meta_description_for_layout',$a_product['Product']['meta_description']);
		$this->set('meta_robots_for_layout',$a_product['Product']['meta_robots']);

		//Canonical
		$a_canonical = array('controller'=>'products','action' => 'index','lang'=>$lang);
		$a_canonical = array_merge($a_canonical,array('slug0'=>$a_product['Product']['slug'],'ext'=>'html'));
		$this->set('a_canonical',$a_canonical);

	}

 /**
     * scroll hiển thị san pham lien quan
     */
    public function ajaxScrollLoadProductRelated(){
		$this->layout = 'ajax';
        $product_id = $_POST['product_id'];

			$a_conditions = array('Product.status'=>1,'Product.trash'=>0,'ProductCategory.status'=>1,'ProductCategory.trash'=>0,'Product.id'=>$product_id);

			if(!empty($oneweb_product['maker'])) $a_conditions = array_merge($a_conditions,array('ProductMaker.status'=>1,'ProductMaker.trash'=>0));

			$this->Product->unbindModel(array(
				'belongsTo'=>array('ProductTax','User'),
				'hasMany'=>array('Comment','ProductOption','ProductProperty')
			));
			$a_product = $this->Product->find('first',array(
				'conditions'=>$a_conditions,
				'contain' => array('ProductMaker', 'ProductCategory', 'ProductImage'),
				'fields'=>array('Product.*','ProductCategory.id','ProductCategory.name','ProductCategory.path','ProductMaker.name', 'ProductMaker.slug', 'ProductMaker.target', 'ProductMaker.meta_title')
			));

			$a_product['Product']['tag'] = $this->_getSlugForTag($a_product['Product']['tag']);
			$a_product['Product']['description'] = @unserialize($a_product['Product']['description']);

		$slug = $a_product['Product']['slug'];
		$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		if(empty($a_related_products)){
			$this->loadModel('ProductCategory');
			$a_ids = array($a_product['ProductCategory']['id']);		//Id của danh mục này và các danh mục con của nó
			
			$a_child_categories = $this->Product->ProductCategory->children($a_product['ProductCategory']['id'],false,array('id','status','trash'));
			
			foreach ($a_child_categories as $val){
				$item_cate = $val['ProductCategory'];
				if($item_cate['status'] && !$item_cate['trash']) $a_ids[] = $item_cate['id'];
			}
           
            $a_parent_id = $this->ProductCategory->find('first',array(
                'conditions'=>array('ProductCategory.id'=>$a_ids),
                'fields'=>array('ProductCategory.parent_id'),
                'recursive'=>0
            ));
            $parent_id = $a_parent_id['ProductCategory']['parent_id'];
            if(!empty($parent_id)){	//Danh muc

                $a_cate_ids = array($parent_id);

                    //Tìm các danh mục con
                    $a_child_categories = $this->Product->ProductCategory->children($parent_id,false,array('id'));
                    foreach ($a_child_categories as $val){
                        $item = $val['ProductCategory'];
                        $a_cate_ids[] = $item['id'];
                    }


                $cate_conditions = array('product_category_id'=>$a_cate_ids);
                foreach($a_cate_ids as $val){
                    $cate_conditions[] = array('category_other like'=>'%-'.$val.'-%');
                }
			
                $a_related_conditions_parent = array('Product.status'=>1,'Product.trash'=>0,'ProductCategory.status'=>1,'ProductCategory.trash'=>0,'Product.slug !='=>$slug,'Product.public <='=>$date_current);
                $a_related_conditions_parent = array_merge($a_related_conditions_parent,array('or'=>$cate_conditions));
            }

			$a_related_conditions = array('product_category_id'=>$a_ids,'Product.status'=>1,'Product.trash'=>0,'ProductCategory.status'=>1,'ProductCategory.trash'=>0,'Product.slug !='=>$slug,'Product.public <='=>$date_current);
		
			if(!empty($oneweb_product['maker'])) $a_related_conditions = array_merge($a_related_conditions,array('ProductMaker.status'=>1,'ProductMaker.trash'=>0));

			$a_related_products = $this->Product->find('all',array(
				'conditions'=>$a_related_conditions,
				'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.price_new','Product.slug','Product.meta_title','Product.rel','Product.target','Product.description','Product.image','Product.price','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.public','Product.summary',
								'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
							),
				'order'=>'rand()',
				// 'limit'=>1,
				'recursive'=>0
			));
            $a_related_conditions_parent = $this->Product->find('all',array(
                'conditions'=>$a_related_conditions_parent,
                'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.price_new','Product.slug','Product.meta_title','Product.rel','Product.target','Product.description','Product.image','Product.price','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.public','Product.summary',
                    'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
                ),
                'order'=>array('Product.sort'=>'desc'),
//                'limit'=>15,
                'recursive'=>0
            ));
			
            $a_related_products = array_merge($a_related_products,$a_related_conditions_parent);
		
		
		}

		$this->set('a_related_products_c',$a_related_products);
	}

	private function _productViewed($a_product) {
		$cookie_product_viewed = array();
    if($this->Cookie->check('cookie_product_viewed')) {
    	$cookie_product_viewed = $this->Cookie->read('cookie_product_viewed');
    	foreach ($cookie_product_viewed as $key => $value) {
    		if($value != $a_product['Product']['id']) array_push($cookie_product_viewed, $a_product['Product']['id']);
    	}
    } else {
    	array_push($cookie_product_viewed, $a_product['Product']['id']);
    }
    $this->Cookie->write('cookie_product_viewed', $cookie_product_viewed, '+10 days');
	}

	// Sản phẩm khuyến mại
	private function _promotion(){
		$lang = $this->params['lang'];
		$this->set('class','list_post');
		$a_params = $this->params;
		$a_conditions = array('Product.lang'=>$lang,'Product.status'=>1,'Product.trash'=>0,'pos_1'=>1);
		$this->paginate = array(
					'conditions'=>$a_conditions,
					'contain'=>array('ProductCategory','ProductMaker'),
					'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.slug','Product.meta_title','Product.rel','Product.target','Product.image','Product.price','Product.price_new','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.public','Product.like','Product.summary',
							'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
					),
					'order'=>array('Product.sort'=>'asc'),
					'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
					'limit'=>$this->limit
			);
		$a_promotions = $this->paginate();
		if(empty($a_promotions)) throw new NotFoundException(__('Trang này không tồn tại',true));
		$this->set('a_promotions_c',$a_promotions);

		$a_breadcrumb[] = array(
				'name'=>__('Sản phẩm khuyến mại'),
				'meta_title'=>__('Sản phẩm khuyến mại'),
				'url'=>'',
		);
		$this->set('a_breadcrumb_c',$a_breadcrumb);

		//SEO
		$this->set('title_for_layout',__('Sản phẩm khuyến mại'));
		$this->set('meta_keyword_for_layout',__('Sản phẩm khuyến mại'));
		$this->set('meta_description_for_layout',__('Sản phẩm khuyến mại'));
		//Canonical
		$a_canonical = array('controller'=>'products','action' => 'index','lang'=>$lang,'slug0'=>'khuyen-mai-hot');
		if(!empty($a_params['page']) && $a_params['page']>1) $a_canonical = array_merge($a_canonical,array('page'=>$a_params['page']));
		if(!empty($a_params['sort']) && !empty($a_params['direction'])) $a_canonical = array_merge($a_canonical,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
		$this->set('a_canonical',$a_canonical);
	}

	// Sản phẩm bán chạy
	private function _bestsell(){
		$lang = $this->params['lang'];
		$this->set('class','list_post');
		$a_params = $this->params;
		$a_conditions = array('Product.lang'=>$lang,'Product.status'=>1,'Product.trash'=>0,'pos_3'=>1);
		$this->paginate = array(
					'conditions'=>$a_conditions,
					'contain'=>array('ProductCategory','ProductMaker'),
					'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.slug','Product.meta_title','Product.rel','Product.target','Product.image','Product.price','Product.price_new','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.public','Product.like','Product.summary',
							'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
					),
					'order'=>array('Product.sort'=>'asc'),
					'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
					'limit'=>$this->limit
			);
		$a_promotions = $this->paginate();
		if(empty($a_promotions)) throw new NotFoundException(__('Trang này không tồn tại',true));
		$this->set('a_promotions_c',$a_promotions);

		$a_breadcrumb[] = array(
				'name'=>__('Sản phẩm bán chạy'),
				'meta_title'=>__('Sản phẩm bán chạy'),
				'url'=>'',
		);
		$this->set('a_breadcrumb_c',$a_breadcrumb);

		//SEO
		$this->set('title_for_layout',__('Sản phẩm bán chạy'));
		$this->set('meta_keyword_for_layout',__('Sản phẩm bán chạy'));
		$this->set('meta_description_for_layout',__('Sản phẩm bán chạy'));
		//Canonical
		$a_canonical = array('controller'=>'products','action' => 'index','lang'=>$lang,'slug0'=>'san-pham-ban-chay');
		if(!empty($a_params['page']) && $a_params['page']>1) $a_canonical = array_merge($a_canonical,array('page'=>$a_params['page']));
		if(!empty($a_params['sort']) && !empty($a_params['direction'])) $a_canonical = array_merge($a_canonical,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
		$this->set('a_canonical',$a_canonical);
	}

	// Sản phẩm mới nhất
	private function _newest(){
		$lang = $this->params['lang'];
		$this->set('class','list_post');
		$a_params = $this->params;
		$a_conditions = array('Product.lang'=>$lang,'Product.status'=>1,'Product.trash'=>0,'pos_2'=>1);
		$this->paginate = array(
					'conditions'=>$a_conditions,
					'contain'=>array('ProductCategory','ProductMaker'),
					'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.slug','Product.meta_title','Product.rel','Product.target','Product.image','Product.price','Product.price_new','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.public','Product.like','Product.summary',
							'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
					),
					'order'=>array('Product.sort'=>'asc'),
					'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
					'limit'=>$this->limit
			);
		$a_promotions = $this->paginate();
		if(empty($a_promotions)) throw new NotFoundException(__('Trang này không tồn tại',true));
		$this->set('a_promotions_c',$a_promotions);

		$a_breadcrumb[] = array(
				'name'=>__('Sản phẩm mới nhất'),
				'meta_title'=>__('Sản phẩm mới nhất'),
				'url'=>'',
		);
		$this->set('a_breadcrumb_c',$a_breadcrumb);

		//SEO
		$this->set('title_for_layout',__('Sản phẩm mới nhất'));
		$this->set('meta_keyword_for_layout',__('Sản phẩm mới nhất'));
		$this->set('meta_description_for_layout',__('Sản phẩm mới nhất'));
		//Canonical
		$a_canonical = array('controller'=>'products','action' => 'index','lang'=>$lang,'slug0'=>'san-pham-moi-nhat');
		if(!empty($a_params['page']) && $a_params['page']>1) $a_canonical = array_merge($a_canonical,array('page'=>$a_params['page']));
		if(!empty($a_params['sort']) && !empty($a_params['direction'])) $a_canonical = array_merge($a_canonical,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
		$this->set('a_canonical',$a_canonical);
	}

	private function getDetailCart(){
		$lang = $this->params['lang'];

		$a_products = $this->Session->read("Order_$lang");

		if(!empty($a_products)){
			$a_ids = array();
			foreach($a_products as $val) $a_ids[] = $val['id'];

			$this->loadModel('Product');
			$a_list_products = $this->Product->find('all',array(
					'conditions'=>array('status'=>1,'lang'=>$lang,'id'=>$a_ids,'trash'=>0),
					'fields'=>array('id','name','image','price','discount','discount_unit','promotion','quantity'),
					'recursive'=>-1
			));

			$total = 0;
			foreach($a_list_products as $key=>$val){
				$item = $val['Product'];
				$qty = 1;
				foreach($a_products as $val2)
					if($val2['id']==$item['id']) $qty = $val2['qty'];
				$a_list_products[$key]['Product']['qty'] = $qty;

				//Tính giá
				$price = $item['price'];
				if(!empty($item['discount'])){
					if($item['discount_unit'])	$price = $price-($price*$item['discount']/100);		//Giảm giá theo %
					else $price = $price - $item['discount'];										//Giảm số tiền nhập vao
				}

				$total+=($price*$qty);
			}
		}

		$result = array(
					'detail'=>!empty($a_list_products)?$a_list_products:'0',
					'total'=>!empty($total)?$total:0
				);
		return $result;
	}

	private function _getBreadcrumbCategory($id){
		$lang = $this->params['lang'];
		$a_path = $this->Product->ProductCategory->getPath($id,'id,name,slug,meta_title,path,link,parent_id,slug,status');
		$a_breadcrumb = array();
		foreach($a_path as $val){
			$item = $val['ProductCategory'];

			if(empty($item['link'])){
				$url = array('controller'=>'products','action' => 'index','lang'=>$lang);
						// $tmp = explode(',', $item['path']);
						// for($i=0;$i<count($tmp);$i++){
						// 	$url = array_merge($url,array('slug'.$i=>$tmp[$i]));
						// }
						$url = array_merge($url,array('slug0'=>$item['slug']));

			}else $url = $item['link'];

			// print_r($val['ProductCategory']);die();
					$children = array();
			$a_children = $this->Product->ProductCategory->find('all',array(
				'conditions'=>array('parent_id'=>$item['parent_id'],'id !='=>$item['id'],'status'=>1,'trash'=>0,'lang'=>$lang),
				'fields'=>array('id','name','meta_title','slug','path','link','parent_id','counter'),
				'order'=>'lft asc',
				'recursive'=>-1
			));

			foreach($a_children as $val2){
				$item2 = $val2['ProductCategory'];

				$a_counter_child = unserialize($item2['counter']);

				if(empty($item2['link'])){
					$url2 = array('controller'=>'products','action' => 'index','lang'=>$lang);
							// $tmp2 = explode(',', $item2['path']);
							// for($i=0;$i<count($tmp2);$i++){
							// 	$url2 = array_merge($url2,array('slug'.$i=>$tmp2[$i]));
							// }
							$url2 = array_merge($url2,array('slug0'=>$item2['slug']));

				}else $url2 = $item2['link'];

				if($url2!='javascript:;'){
							$children[] = array(
											'name'=>$item2['name'].' ('.$a_counter_child['item_active'].')',
									'meta_title'=>$item2['meta_title'],
									'url'=>$url2
										 );
				}
			}

			$a_breadcrumb[] = array(
							'name'=>$item['name'],
							'meta_title'=>$item['meta_title'],
							'url'=>$url,
							'child'=>$children
						);
		}

		return $a_breadcrumb;

	}


	/**
	 * @Description : Danh sách sản phẩm theo hãng sx
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int $slug
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function maker($slug=null){
		$this->set('class','list_product');

		if($slug==null) throw new NotFoundException(__('Trang này không tồn tại',true));

		$a_params = $this->params;
		$lang = $a_params['lang'];
		$oneweb_product = Configure::read('Product');

		//Đọc thông tin hãng sx
		$a_maker = $this->Product->ProductMaker->find('first',array(
			'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'slug'=>$slug,'or'=>array(array('link'=>null),array('link'=>''))),
			'fields'=>array('id','name','lang','banner','banner_link','slug','description','meta_title','meta_keyword','meta_description','meta_robots'),
			'recursive'=>-1
		));

		if(empty($a_maker)) throw new NotFoundException(__('Trang này không tồn tại',true));
		$a_maker = $a_maker['ProductMaker'];

		//Danh sách sản phẩm
		$a_conditions = array('Product.lang'=>$lang,'Product.status'=>1,'Product.trash'=>0,'ProductCategory.status'=>1,'ProductCategory.trash'=>0,'Product.product_maker_id'=>$a_maker['id']);

		$a_orders = array();
		if(empty($a_params['sort']) && empty($a_params['direction'])){
			$a_orders = array('sort'=>'asc','created'=>'desc','name'=>'asc');
		}else{
			$a_orders = array($a_params['sort']=>$a_params['direction']);
		}

		$this->paginate = array(
			'conditions'=>$a_conditions,
			'contain'=>array('ProductCategory','ProductMaker'),
			'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.slug','Product.meta_title','Product.rel','Product.target','Product.image','Product.price','Product.price_new','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.summary',
							'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
						),
			'order'=>$a_orders,
			'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
			'limit'=>$this->limit
		);
		$a_products = $this->paginate();

		$this->set('a_products_c',$a_products);
		$this->set('a_maker_c',$a_maker);


		//Breadcrumb
		$a_children = $this->Product->ProductMaker->find('all',array(
			'conditions'=>array('status'=>1,'lang'=>$lang,'id !='=>$a_maker['id']),
			'fields'=>array('id','name','meta_title','slug'),
			'order'=>array('sort'=>'asc','name'=>'asc'),
			'recursive'=>-1
		));
		$children = array();
		foreach($a_children as $val){
			$item = $val['ProductMaker'];
			$url = array('controller'=>'products','action'=>'maker','lang'=>$lang,'slug'=>$item['slug']);
			$children[] = array(
								'name'=>$item['name'],
								'meta_title'=>$item['meta_title'],
								'url'=>$url
							);
		}
		$a_breadcrumb[] = array(
								'name'=>__('Hãng').': '.$a_maker['name'],
								'meta_title'=>$a_maker['meta_title'],
								'url'=>'',
								'child'=>$children
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);


		//SEO
		$this->set('title_for_layout',$a_maker['meta_title']);
		$this->set('meta_keyword_for_layout',$a_maker['meta_keyword']);
		$this->set('meta_description_for_layout',$a_maker['meta_description']);
		$this->set('meta_robots_for_layout',$a_maker['meta_robots']);

		//Canonical
		$a_canonical = array('controller'=>'products','action' => 'maker','lang'=>$lang,'slug'=>$slug);
				if(!empty($a_params['page']) && $a_params['page']>1) $a_canonical = array_merge($a_canonical,array('page'=>$a_params['page']));
				if(!empty($a_params['sort']) && !empty($a_params['direction'])) $a_canonical = array_merge($a_canonical,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
		$this->set('a_canonical',$a_canonical);
	}



	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description : Danh sách sản phẩm
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$admin = $this->Auth->user();
		if ($admin['role'] != 'admin') throw new NotFoundException(__('Trang này không tồn tại',true));

		$lang = $this->Session->read('lang');
		$oneweb_product = Configure::read('Product');
		$a_conditions = array('Product.lang'=>$lang,'Product.trash'=>0);

		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->Product->id = $val;
						$this->Product->set(array('status'=>1));
						$this->Product->save();
					}
					$message = __('Sản phẩm đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->Product->id = $val;
						$this->Product->set(array('status'=>0));
						$this->Product->save();
					}
					$message = __('Sản phẩm đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trashItem($val);
					}
					$message = __('Sản phẩm đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		if(!empty($_GET['maker_id'])){	//Hang sx
			$this->request->data['Product']['maker_id'] = $_GET['maker_id'];
			$a_conditions = array_merge($a_conditions,array('product_maker_id'=>$_GET['maker_id']));
		}
		if(!empty($_GET['category_id'])){	//Danh muc
			$this->request->data['Product']['category_id'] = $_GET['category_id'];

			$a_cate_ids = array($_GET['category_id']);

			if(!empty($oneweb_product['pro_child']) || !empty($_GET['keyword'])){
				//Tìm các danh mục con
				$a_child_categories = $this->Product->ProductCategory->children($_GET['category_id'],false,array('id'));
				foreach ($a_child_categories as $val){
					$item = $val['ProductCategory'];
					$a_cate_ids[] = $item['id'];
				}
			}

			$cate_conditions = array('product_category_id'=>$a_cate_ids);
			foreach($a_cate_ids as $val){
				$cate_conditions[] = array('category_other like'=>'%-'.$val.'-%');
			}

			$a_conditions = array_merge($a_conditions,array('or'=>$cate_conditions));
		}
		if(!empty($_GET['position'])){	//Vi tri hien thi
			$a_conditions = array_merge($a_conditions,array('pos_'.$_GET['position'].' !='=>0));
			$a_order = array('pos_'.$_GET['position']=>'asc');
		}else{
			$a_order = array('sort'=>'asc');
		}
		if(isset($_GET['stock'])) $a_conditions = array_merge($a_conditions,array('quantity'=>0));

		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tên sản phẩm, Mã sản phẩm')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('or'=>array(array('Product.name like'=>'%'.$_GET['keyword'].'%'),array('Product.code like'=>'%'.$_GET['keyword'].'%'))));
		}

		$a_order = array_merge(array('created'=>'desc'),$a_order);

		$this->paginate = array(
			'conditions'=>$a_conditions,
			'contain'=>array('ProductMaker','ProductCategory','Comment.id','Comment.status'),
			'fields'=>array(
							'name','image','slug','view','price','code','price_new','quantity','product_maker_id','product_category_id','category_other','sort','pos_1','pos_2','pos_3','pos_4','pos_5','pos_6','pos_7','pos_8','discount','promotion','discount_unit','status','lang','created',
							'ProductCategory.name','ProductCategory.slug','ProductCategory.path','ProductCategory.status',
							'ProductMaker.name'
						),
			'order'=>$a_order,
			'limit'=>$this->limit_admin
		);


		$a_products = $this->paginate();
		$this->set('a_products_c', $a_products);

		$counter = $this->Product->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);

		//Tìm danh mục con trực tiếp
		if(empty($_GET['keyword'])){
			$a_list_children = $this->Product->ProductCategory->find('all',array(
				'conditions'=>array('trash'=>0,'parent_id'=>(!empty($_GET['category_id'])?$_GET['category_id']:null),'lang'=>$lang),
				'fields'=>array('id','slug','name','status','counter'),
				'order'=>'lft asc',
				'recursive'=>-1
			));
			$this->set('a_list_children_c',$a_list_children);
		}

		//Danh sach danh muc
		$a_product_categories = $this->Product->ProductCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));
		$this->set('a_product_categories_c',$a_product_categories);

		//Danh sach hang sx
		$a_product_makers = $this->Product->ProductMaker->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>array('sort'=>'asc','name'=>'asc')));
		$this->set('a_product_makers_c',$a_product_makers);

		//Đọc đơn vị tiền tệ mặc định
		$a_currency = $this->_getUnitCurrency();
		$this->set('a_currency_c',$a_currency);

		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}

	/**
	 * @Description : Thay đổi trạng thái
	 *
	 * @throws 	: NotFoundException
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxChangeStatus(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$return = $this->_changeStatus($_POST['field'], $_POST['id']);
		if($_POST['field']){	//Đếm vị trí hiển thị trong trg hợp nó là vị trí hiển thị
			$this->Product->recursive = -1;
			$a_product = $this->Product->read('pos_1,pos_2,pos_3,pos_4,pos_5,pos_6,pos_7,pos_8',$_POST['id']);
			$a_product = array_filter($a_product['Product']);

			$return = array_merge($return,array('count'=>count($a_product)));
		}

		return json_encode($return);
	}

	/**
	 * @Description : Sắp xếp sp
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$this->Product->id = $_POST['id'];
		$this->Product->set(array($_POST['field']=>$_POST['val']));
		$this->Product->save();
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}


	/**
	 * @Description : Sửa tên ảnh của sp trong bảng product_images
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxChangeNameImage(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['name']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$this->Product->ProductImage->id = $_POST['id'];
		$this->Product->ProductImage->set(array('name'=>$_POST['name']));
		if($this->Product->ProductImage->save()) return true;
		else return false;
	}


	/**
	 * @Description : Thêm sản phẩm
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$admin = $this->Auth->user();
		if ($admin['role'] != 'admin') throw new NotFoundException(__('Trang này không tồn tại',true));

		$lang = $this->Session->read('lang');

		if ($this->request->is('post')) {
			$oneweb_product = Configure::read('Product');
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['Product'];
			//Ảnh đại diện
			$file = $data['image'];
			$data['image'] = '';

			//Slug - meta title
			if($oneweb_seo){
				//Slug
				if(empty($data['slug'])) $data['slug'] = $data['name'];

				//Meta title
				if(empty($data['meta_title'])) $data['meta_title'] = $data['name'];
			}else{
				//Slug
				$data['slug'] = $data['name'];

				//Meta title
				$data['meta_title'] = $data['name'];
			}

			//Lấy danh sách slug đã tồn tại
			$a_all_slugs = $this->Product->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));

			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
// 			$data['warranty'] = nl2br($data['warranty']);
			//Ngay tao
			if(!empty($data['created'])){
				$data['created'] = mktime($data['created']['hour'],$data['created']['min'],0,$data['created']['month'],$data['created']['day'],$data['created']['year']);
			}else{
				$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			}

			//Ngay hien thi
			if(!empty($data['public'])){
				$data['public'] = mktime($data['public']['hour'],$data['public']['min'],0,$data['public']['month'],$data['public']['day'],$data['public']['year']);
			}else{
				$data['public'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			}

			//Kiểm tra mã sản phẩm đã tồn tại chưa, nếu đã tồn tại thêm số 1 vào sau
			$check_code = $this->Product->find('count',array('conditions'=>array('code'=>$data['code'],'lang'=>$lang),'fields'=>'id','recursive'=>-1));
			if($check_code>0) $data['code'].='1';

			//Tag
			if($oneweb_product['tag'] && !empty($data['tag'])){
				$data['tag'] = $this->_getTag($data['tag']);
			}

			//ID của các danh mục khác
			if(!empty($data['category_other'])){
				$data['category_other'] = '-'.implode('-', array_filter($data['category_other'])).'-';
			}

			//Thiết lập mặc định hãng sản xuất
// 			if(!$oneweb_product['maker']) $data['product_maker_id'] = 1;

			//Mô tả
			$data['description'] = array_filter($data['description']);
			$data['description'] = (!empty($data['description']))?serialize($data['description']):'';

			//User
			$admin = $this->Auth->user();
			$data['user_id'] = $admin['id'];

			//Ngôn ngữ
			$data['lang'] = $lang;
			$this->Product->create();
			if ($this->Product->save($data)) {
				$id = $this->Product->getLastInsertID();

				//Kiem tra tag
				if($oneweb_product['tag'] && !empty($data['tag'])){
					$this->_checkTag($data['tag']);
					$this->_setTagPriority($data['tag'], $id);
				}

				$path = realpath($oneweb_product['path']['product']).DS;		//Đường dẫn file ảnh
				//Upload image
				if(!empty($file['name'])){
					$ext = substr(strtolower(strrchr($file['name'], '.')), 1);

					$arr_ext = array('jpg', 'jpeg', 'gif','png');
					if (in_array($ext, $arr_ext)) {
						$file_name = explode('.',$file['name']);
						$file_name['0'] = $this->Oneweb->slug($file_name['0'],null);
						$file_name = implode('.',$file_name);
						//Up ảnh mới
						move_uploaded_file($file['tmp_name'], $path . $file_name);
						//prepare the filename for database entry
						$this->request->data['Product']['image'] = $file_name;

						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->Product->id = $id;
						$this->Product->set('image',$file_name);
						$this->Product->save();
					}
// 					$result = $this->Upload->upload($file, $path, null, array('type' => 'resizemax', 'size' => $oneweb_product['size']['product'], 'output' => 'jpg'));
// 					if($result){
// 						$image = $this->Upload->result;

// 						//Luu ten anh vao ban ghi vua duoc them vao bang products
// 						$this->Product->id = $id;
// 						$this->Product->set('image',$image);
// 						$this->Product->save();
// 					}else{
// 						//Hien thi loi
// 						$errors=$this->Upload->errors;
// 						// piece together errors
// 						if(is_array($errors)){ $errors = implode("<br />",$errors); }
// 						$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
// 						$this->redirect(array('action'=>'edit',$id));
// 					}
				}

				//Upload nhieu anh
				if(!empty($this->request->data['ProductImage'])){
					$tmp=array();
					foreach($this->request->data['ProductImage'] as $key=>$val){
						if(!empty($val['name'])){
							$tmp['product_id']=$id;

// 							$result = $this->Upload->upload($val, $path, null, array('type' => 'resizemax', 'size' => $oneweb_product['size']['product'], 'output' => 'jpg'));
// 							if($result){
// 								$tmp['image'] = $this->Upload->result;
// 								$tmp['name'] =substr($tmp['image'], 0, (strlen($tmp['image'])-4));

// 								//Luu vao csdl
// 								$this->Product->ProductImage->create();
// 								$this->Product->ProductImage->save($tmp);
// 							}
// 							else{
// 								//Hien thi loi
// 								$errors=$this->Upload->errors;
// 								// piece together errors
// 								if(is_array($errors)){ $errors = implode("<br />",$errors); }
// 								$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
// 								$this->redirect(array('action'=>'edit',$id));
// 							}
							$arr_ext = array('jpg', 'jpeg', 'gif','png');
							$ext_multi = substr(strtolower(strrchr($val['name'], '.')), 1);
							if (in_array($ext_multi, $arr_ext)) {
								$file_name_multi = explode('.',$val['name']);
								$file_name_multi['0'] = $this->Oneweb->slug($file_name_multi['0'],null);
								$file_name_multi = implode('.',$file_name_multi);
								//Up ảnh
								move_uploaded_file($val['tmp_name'], $path . $file_name_multi);
								//prepare the filename for database entry
// 								$this->request->data['Product']['image'] = $file_name_multi;

								//Luu vao csdl
								$tmp['image'] = $file_name_multi;
								$tmp['name'] =substr($tmp['image'], 0, (strlen($tmp['image'])-4));
								$this->Product->ProductImage->create();
								$this->Product->ProductImage->save($tmp);
							}
						}
					}
				}

				$this->Session->setFlash('<span>'.__('Thêm mới thành công').'</span>','default',array('class'=>'success'));
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				if (!empty($_POST['save'])){
					$this->redirect(array('action'=>'edit',$id));
				}elseif (!empty($_POST['save_add'])){
					$this->redirect(array('action'=>'add'));
				}else $this->redirect(array('action'=>'index'));

			} else {
				$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
			}
		}
		//Ma sp
// 		$this->request->data['Product']['code'] = rand(1000,9999).'-'.rand(1000,9999);	//Mã sản phẩm;

		//Danh sach danh muc
		$a_categories_c = $this->Product->ProductCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));

		//Danh sach hang sx
		$a_makers_c = $this->Product->ProductMaker->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>array('sort'=>'asc','name'=>'asc')));

		//Danh sach thue
		$a_taxes_c = $this->Product->ProductTax->find('list',array('conditions'=>array('lang'=>$lang)));

		//Danh sách tab sản phẩm
		$this->set('a_tabs_c',$this->_getTabProduct());

		$a_currency = $this->_getUnitCurrency();
		$this->set('currency_c',$a_currency['unit']);

		$this->set(compact('a_categories_c', 'a_makers_c', 'a_taxes_c'));
	}

	/**
	 * @Description : Lấy thông tin đơn vị tiền mặc định
	 *
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _getUnitCurrency(){
		$lang = $this->Session->read('lang');
		$this->loadModel('Currency');

		//Đơn vị tiền mặc định
		$a_currency_id = $this->Config->find('first',array('conditions'=>array('name'=>'site_currency')));
		$a_currency_id = unserialize($a_currency_id['Config']['value']);

		$a_currency = $this->Currency->find('first',array('conditions'=>array('id'=>$a_currency_id[$lang]),'fields'=>array('unit','name')));
		return $a_currency['Currency'];
	}

	/**
	 * @Description : Lấy danh sách tab sản phẩm
	 *
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _getTabProduct(){
		$lang = $this->Session->read('lang');
		$a_tabs = $this->Config->find('first',array('conditions'=>array('name'=>'product_tab')));
		$a_tabs = unserialize($a_tabs['Config']['value']);
		$a_tabs = explode(',', $a_tabs[$lang]);
		return $a_tabs;
	}

	/**
	 * @Description : Sửa sản phẩm
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$admin = $this->Auth->user();
		if ($admin['role'] != 'admin') throw new NotFoundException(__('Trang này không tồn tại',true));

		$oneweb_product = Configure::read('Product');
		$oneweb_seo = Configure::read('Seo');

		$this->Product->id = $id;
		if (!$this->Product->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');

		//plugin product attribute
    $this->loadModel('ProductAttribute');

    $product_attributes = $this->ProductAttribute->find('all', array(
    	'contain'=>array('ProductColor'),
    	'conditions'=>array('ProductAttribute.product_id'=>$id),
    	'fields'=>array('ProductAttribute.*', 'ProductColor.*'),
    	'recursive'=>0
    ));

    $list_product_color = $this->ProductAttribute->ProductColor->find('list', array(
    	'conditions'=>array('ProductColor.status'=>true),
    	'fields'=>array('id', 'color'),
    	'recursive'=>-1
    ));
    $list_product_size = $this->ProductAttribute->ProductSize->find('list', array(
    	'conditions'=>array('ProductSize.status'=>true),
    	'fields'=>array('id', 'size'),
    	'recursive'=>-1
    ));
    $this->set(compact('product_attributes', 'list_product_color', 'list_product_size'));

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['Product'];

			$this->Product->recursive = -1;
			$a_product = $this->Product->read('image,tag',$id);
			$a_product = $a_product['Product'];

			//Ảnh đại diện
			if(!empty($data['image']['name'])){		//Up ảnh khác
				$file = $data['image'];
			}
			$data['image'] = $a_product['image'];

			//Slug - meta title
			if($oneweb_seo){
				//Slug
				if(empty($data['slug'])) $data['slug'] = $data['name'];

				//Meta title
				if(empty($data['meta_title'])) $data['meta_title'] = $data['name'];
			}else{
				//Slug
				$data['slug'] = $data['name'];

				//Meta title
				$data['meta_title'] = $data['name'];
				$data['meta_keyword'] = '';
				$data['meta_description'] = '';
			}

			//Lấy danh sách slug đã tồn tại
			$a_all_slugs = $this->Product->find('list',array('conditions'=>array('lang'=>$lang,'id !='=>$id),'fields'=>'slug','recursive'=>-1));

			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
// 			$data['warranty'] = nl2br($data['warranty']);
			//Ngày sửa
			if(!empty($data['modified'])){
				$data['modified'] = mktime($data['modified']['hour'],$data['modified']['min'],0,$data['modified']['month'],$data['modified']['day'],$data['modified']['year']);
			}else{
				$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			}

			//Ngày public
			if(!empty($data['public'])){
				$data['public'] = mktime($data['public']['hour'],$data['public']['min'],0,$data['public']['month'],$data['public']['day'],$data['public']['year']);
			}else{
				$data['public'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			}
			//Kiểm tra mã sản phẩm đã tồn tại chưa, nếu đã tồn tại thêm số 1 vào sau
			$check_code = $this->Product->find('count',array('conditions'=>array('code'=>$data['code'],'lang'=>$lang,'id !='=>$id),'fields'=>'id','recursive'=>-1));
			if($check_code>0) $data['code'].='1';

			//Tag
			if($oneweb_product['tag'] && !empty($data['tag'])){
				$data['tag'] = $this->_getTag($data['tag']);
			}

			//ID của các danh mục khác
			if(!empty($data['category_other'][0])){
				$data['category_other'] = '-'.implode('-', array_filter($data['category_other'])).'-';
			}else $data['category_other'] = '';

			//Mô tả
			$data['description'] = array_filter($data['description']);
			$data['description'] = (!empty($data['description']))?serialize($data['description']):'';

			if ($this->Product->save($data)) {
				//Kiểm tra, cập nhật lại Tag
				if($oneweb_product['tag']){
					$this->_checkTag($data['tag'],$a_product['tag']);
					$this->_setTagPriority($data['tag'], $id);
				}
				// attribute
				if(!empty($this->request->data['ProductAttribute'])) {
					$data_attributes = $this->request->data['ProductAttribute'];
					// debug($data_attributes);
					if(!empty($data_attributes)) {//trường hợp xóa nếu product_color_id rỗng
						foreach ($data_attributes as $key => $value) {
							if(!empty($value['id'])) {
								if(empty($value['product_color_id'])) {
									$this->Product->ProductAttribute->delete($value['id']);
									unset($data_attributes[$key]);
								}
							}
						}
					}
					$this->Product->ProductAttribute->saveMany($data_attributes);
				}

				$path = realpath($oneweb_product['path']['product']).DS;		//Đường dẫn file ảnh
				//Upload image
				if(!empty($file['name'])){
					//Xóa ảnh cũ
					if(!empty($a_product['image']) && file_exists($path.$a_product['image'])) unlink($path.$a_product['image']);

					$ext = substr(strtolower(strrchr($file['name'], '.')), 1);

					$arr_ext = array('jpg', 'jpeg', 'gif','png');
					if (in_array($ext, $arr_ext)) {
						$file_name = explode('.',$file['name']);
						$file_name['0'] = $this->Oneweb->slug($file_name['0'],null);
						$file_name = implode('.',$file_name);
						//Up ảnh mới
						move_uploaded_file($file['tmp_name'], $path . $file_name);
						//prepare the filename for database entry
						$this->request->data['Product']['image'] = $file_name;

						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->Product->id = $id;
						$this->Product->set('image',$file_name);
						$this->Product->save();
					}
// 					$result = $this->Upload->upload($file, $path, null,null ,null);
// 					if($result){
// 						$image = $this->Upload->result;

// 						//Luu ten anh vao ban ghi vua duoc them vao bang products
// 						$this->Product->id = $id;
// 						$this->Product->set('image',$image);
// 						$this->Product->save();
// 					}else{
// 						//Hien thi loi
// 						$errors=$this->Upload->errors;
// 						// piece together errors
// 						if(is_array($errors)){ $errors = implode("<br />",$errors); }
// 						$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
// 						$this->redirect($this->referer());
// 					}
				}


				//Upload nhieu anh
				if(!empty($this->request->data['ProductImage'])){
					$tmp=array();
					$arr_ext = array('jpg', 'jpeg', 'gif','png');
					foreach($this->request->data['ProductImage'] as $key=>$val){
						if(!empty($val['name'])){
							$tmp['product_id']=$id;

// 							$result = $this->Upload->upload($val, $path, null, array('type' => 'resizemax', 'size' => $oneweb_product['size']['product'], 'output' => 'jpg'));
// 							if($result){
// 								$tmp['image'] = $this->Upload->result;
// 								$tmp['name'] =substr($tmp['image'], 0, (strlen($tmp['image'])-4));

// 								//Luu vao csdl
// 								$this->Product->ProductImage->create();
// 								$this->Product->ProductImage->save($tmp);
// 							}
// 							else{
// 								//Hien thi loi
// 								$errors=$this->Upload->errors;
// 								// piece together errors
// 								if(is_array($errors)){ $errors = implode("<br />",$errors); }
// 								$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
// 								$this->redirect($this->referer());
// 							}
							$ext_multi = substr(strtolower(strrchr($val['name'], '.')), 1);
							if (in_array($ext_multi, $arr_ext)) {
								$file_name_multi = explode('.',$val['name']);
								$file_name_multi['0'] = $this->Oneweb->slug($file_name_multi['0'],null);
								$file_name_multi = implode('.',$file_name_multi);
								//Up ảnh
								move_uploaded_file($val['tmp_name'], $path . $file_name_multi);
								//prepare the filename for database entry
								// 								$this->request->data['Product']['image'] = $file_name_multi;

								//Luu vao csdl
								$tmp['image'] = $file_name_multi;
								$tmp['name'] =substr($tmp['image'], 0, (strlen($tmp['image'])-4));
								$this->Product->ProductImage->create();
								$this->Product->ProductImage->save($tmp);
							}
						}
					}
				}

				$this->Session->setFlash('<span>'.__('Thông tin đã được cập nhật').'</span>','default',array('class'=>'success'));
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				if (!empty($_POST['save'])){
					$this->redirect($this->referer());
				}elseif (!empty($_POST['save_add'])){
					$this->redirect(array('action'=>'add'));
				}else{
					$url = (!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index');
					$this->redirect($url);
				}
			} else {
				$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
			}
		} else {
			$this->request->data = $this->Product->read(null, $id);
			$this->request->data['Product']['category_other'] = array_filter(explode('-', $this->request->data['Product']['category_other']));
			$this->request->data['Product']['description'] = unserialize($this->request->data['Product']['description']);
		}


		//Danh sach danh muc
		$a_categories_c = $this->Product->ProductCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));

		//Danh sach hang sx
		$a_makers_c = $this->Product->ProductMaker->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>array('sort'=>'asc','name'=>'asc')));

		//Danh sach thue
		$a_taxes_c = $this->Product->ProductTax->find('list',array('conditions'=>array('lang'=>$lang)));
		//Danh sách tab sản phẩm
		$this->set('a_tabs_c',$this->_getTabProduct());

		$a_currency = $this->_getUnitCurrency();
		$this->set('currency_c',$a_currency['unit']);

		$this->set(compact('a_categories_c', 'a_makers_c', 'a_taxes_c'));
	}

	public function admin_ajaxAddProductAttribute(){
			$this->layout = 'ajax';

			if(!empty($_POST['product_id'])){
				$this->loadModel('ProductAttribute');

		    $product_attributes = $this->ProductAttribute->find('all', array(
		    	'contain'=>array('ProductColor'),
		    	'conditions'=>array('ProductAttribute.product_id'=>$_POST['product_id']),
		    	'fields'=>array('ProductAttribute.*', 'ProductColor.*'),
		    	'recursive'=>0
		    ));

		    $list_product_color = $this->ProductAttribute->ProductColor->find('list', array(
		    	'conditions'=>array('ProductColor.status'=>true),
		    	'fields'=>array('id', 'color'),
		    	'recursive'=>-1
		    ));
		    $list_product_size = $this->ProductAttribute->ProductSize->find('list', array(
		    	'conditions'=>array('ProductSize.status'=>true),
		    	'fields'=>array('id', 'size'),
		    	'recursive'=>-1
		    ));
		    $this->set('idx', $_POST['idx']);
		    $this->set('product_id', $_POST['product_id']);
		    $this->set(compact('product_attributes', 'list_product_color', 'list_product_size'));
			}
		}
	/**
	 * @Description : Lấy sản phẩm liên quan
	 *
	 * @throws 	: NotFoundException
	 * @return 	: ajax
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxRelated(){
		$this->layout = 'ajax';

		if(!empty($_POST['id'])){
			$a_product = $this->Product->find('first',array('conditions'=>array('id'=>$_POST['id']),'fields'=>'related','recursive'=>-1));

			$code = explode(',', $a_product['Product']['related']);

			//Tìm phụ kiện liên quan
			if($code){
				$a_related = $this->Product->find('all',array(
						'conditions'=>array('code'=>$code,'Product.trash'=>0,'ProductCategory.trash'=>0),
						'contain'=>'ProductCategory',
						'fields'=>array('id','name','slug','image','lang','code','ProductCategory.name','ProductCategory.path'),
						'recursive'=>0
				));

				$this->set('a_related_c',$a_related);
				$this->set('product_id_c',$_POST['id']);
			}
		}
	}


	/**
	 * @Description : Xóa sản phẩm liên quan
	 *
	 * @throws 	: NotFoundException
	 * @return 	: ajax
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDelRelated(){
		$this->layout = false;
		$this->autoRender = false;

		if(!empty($_POST['code']) && !empty($_POST['id'])){
			$a_product = $this->Product->find('first',array('conditions'=>array('id'=>$_POST['id']),'fields'=>array('id','related'),'recursive'=>-1));

			$a_related = explode(',', $a_product['Product']['related']);
			$tmp = '';
			foreach ($a_related as $val) if($val!=$_POST['code']) $tmp[] = $val;
			$a_related = implode(',', $tmp);

			$this->Product->id = $_POST['id'];
			$this->Product->set(array('related'=>$a_related));
			$this->Product->save();
		}
	}

	/**
	 * @Description : Thêm sản phẩm liên quan
	 *
	 * @throws 	: NotFoundException
	 * @return 	: ajax
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxAddRelated(){
		$this->layout = false;
		$this->autoRender = false;

		if(!empty($_POST['code']) && !empty($_POST['id'])){
			$a_product = $this->Product->find('first',array('conditions'=>array('id'=>$_POST['id']),'fields'=>array('id','related'),'recursive'=>-1));

			$a_related = explode(',', $a_product['Product']['related']);
			$a_related = array_merge($a_related,explode(',', $_POST['code']));
			$a_related = implode(',', array_unique($a_related));

			$this->Product->id = $_POST['id'];
			$this->Product->set(array('related'=>$a_related));
			$this->Product->save();
		}
	}

	/**
	 * @Description : Xóa 1 ảnh trong bảng product_images
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDelImg(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		//Doc thong tin
		$this->Product->ProductImage->recursive = -1;
		$a_product_image = $this->Product->ProductImage->read('image',$_POST['id']);
		$a_product_image = $a_product_image['ProductImage'];

		if($this->Product->ProductImage->delete($_POST['id'])){
			//Xoa ảnh
			$oneweb_product = Configure::read('Product');
			$path = realpath($oneweb_product['path']['product']).DS;		//Đường dẫn file ảnh

			if(!empty($a_product_image['image']) && file_exists($path.$a_product_image['image']))
				unlink($path.$a_product_image['image']);

			$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
			return true;
		}else return false;
	}


	/**
	 * @Description : Cho sản phẩm vào thùng rác
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int data
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxTrashItem(){
		$this->layout = false;
		$this->autoRender = false;

		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		return $this->trashItem($_POST['id']);
	}

	/**
	 * @Description : Đưa sản phẩm vào thùng rac
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int data
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function trashItem($id){
		//Thông tin sản phẩm
		$this->Product->recursive = -1;
		$a_product = $this->Product->read('id,name',$id);
		$item_product = $a_product['Product'];

		//Ghi vào bảng Trash
		$data['name'] = $item_product['name'];
		$data['item_id'] = $item_product['id'];
		$data['model'] = 'Product';
		$data['description'] = 'Sản phẩm';
		$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		$this->loadModel('Trash');
		$this->Trash->create();
		if($this->Trash->save($data)){
			$this->Product->id = $id;
			$this->Product->set(array('trash'=>1));
			if($this->Product->save()){
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		return false;
	}
	/*
		* @Description :
		* @param - string :
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
		public function admin_ajaxLoadProductCode(){
		$this->layout = false;
		$this->autoRender = false;
		if(!empty($this->params['named']['lang'])){ $lang = $this->params['named']['lang']; }
		else{
			$lang = 'vi';
		}
		if (empty($_GET['name_startsWith'])) exit ;
		$q = strtolower($_GET["name_startsWith"]);
		// remove slashes if they were magically added
		if (get_magic_quotes_gpc()) $q = stripslashes($q);


		$arr_product = $this->Product->find('all', array(
				'conditions'=>array('Product.lang'=>$lang,'Product.trash'=>0,'or'=>array(array('Product.name like'=>'%'.$q.'%'),array('Product.code like'=>'%'.$q.'%'))),
				'fields'=>array('name','code','lang', 'trash'),
				'recursive'=>-1
				));

		$result = array();
		foreach ($arr_product as $value) {
			$product_item = $value['Product'];
			$label = $product_item['name'].' ('.$product_item['code'].')';
			if (strpos(strtolower($label), $q) !== false) {
				array_push($result, array("label"=>$label, "value" => strip_tags($product_item['code'])));
			}
			if (count($result) > 11)
				break;
		}
		return json_encode($result);
	}

	/*
		* @Description :
		* @param - string :
		* @param - interger:
		* @param - array:
		* @return - array:
		* @Author : HuuQuynh - quynh@url.vn
		*/
	public function admin_ajaxLoadTag(){
		$this->layout = false;
		$this->autoRender = false;
		if(!empty($this->params['named']['lang'])){ $lang = $this->params['named']['lang']; }
		else{
			$lang = 'vi';
		}
		if (empty($_GET['name_startsWith'])) exit ;
		$q = strtolower($_GET["name_startsWith"]);
		// remove slashes if they were magically added
		if (get_magic_quotes_gpc()) $q = stripslashes($q);

		$this->loadModel('Tag');
		$arr_tag = $this->Tag->find('all', array(
				'conditions'=>array('Tag.lang'=>$lang,'Tag.name like'=>'%'.$q.'%'),
				'fields'=>array('name','lang'),
				'recursive'=>-1
		));
		$result = array();
		foreach ($arr_tag as $value) {
			$tag_name = $value['Tag'];
			$label = $tag_name['name'];
			if (strpos(strtolower($label), $q) !== false) {
				array_push($result, array("label"=>$label, "value" => strip_tags($tag_name['name'])));
			}
			if (count($result) > 11)
				break;
		}
		return json_encode($result);
	}

	public function ajaxListMore() {
		$this->layout = false;
		// $this->autoRender = false;

		$category_id = $_POST['cate_id'];
		$page = $_POST['page'];
		$lang = $_POST['lang'];

		//Đọc thông tin danh mục
		$a_category = $this->Product->ProductCategory->find('first',array(
			'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'id'=>$category_id,'or'=>array(array('link'=>null),array('link'=>''))),
			'fields'=>array('id','name','lang','banner','banner_link','slug','path','description','meta_title','meta_keyword','meta_description','meta_robots'),
			'recursive'=>-1
		));

		if(empty($a_category)) throw new NotFoundException(__('Trang này không tồn tại',true));

		$a_category = $a_category['ProductCategory'];
		$slug = $a_category['slug'];
		$a_ids = array($a_category['id']);		//Id của mục này và các mục con của nó
		$a_ids2 = $a_ids;

		//Tìm các danh mục con trực tiếp
		$a_child_direct_categories = $this->Product->ProductCategory->find('all',array(
				'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'parent_id'=>$a_category['id']),
				'fields'=>array('id','name','slug','lang','path','meta_title','rel','target','link','image','status','counter'),
				'order'=>array('lft'=>'asc','name'=>'asc'),
				'recursive'=>-1
		));
		//Tìm tất cả id danh mục con, bao gồm cả danh mục ko trực tiếp
		if(!empty($a_child_direct_categories)){		//Tồn tại danh mục con

			//Tìm id của các danh mục con (bao gồm cả trực tiếp và ko trực tiếp)
			$a_child_categories = $this->Product->ProductCategory->children($a_category['id'],false,array('id','status','trash'));
			$a_ids2 = $a_ids;
			foreach ($a_child_categories as $val){
				$item_cate = $val['ProductCategory'];

				if($item_cate['status'] && !$item_cate['trash']){
					$a_ids[] = $item_cate['id'];
					$a_ids2[] = $item_cate['id'];
				}
			}
		}

		$a_conditions2[] = array('product_category_id'=>$a_ids);
		for ($i=0;$i<count($a_ids);$i++){
			$a_conditions2[] = array('category_other like'=>'%-'.$a_ids[$i].'-%');
		}

		//Danh sách sản phẩm
		//Ngay hien tai
		$date_current = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		$a_conditions = array('Product.lang'=>$lang,'Product.status'=>1,'Product.trash'=>0,'or'=>$a_conditions2,'Product.public <='=>$date_current);

		$a_orders = array();
		if(empty($a_params['sort']) && empty($a_params['direction'])){
			$a_orders = array('Product.sort'=>'asc','Product.created'=>'desc','Product.name'=>'asc');
		}else{
			$a_orders = array($a_params['sort']=>$a_params['direction'],'Product.sort'=>'asc','Product.created'=>'desc');
		}
		$limit = $this->limit;

		$this->paginate = array(
				'conditions'=>$a_conditions,
				'contain'=>array('ProductCategory','ProductMaker'),
				'fields'=>array('Product.id','Product.name','Product.name_en','Product.count_buyed','Product.lang','Product.price_new','Product.slug','Product.meta_title','Product.rel','Product.target','Product.image','Product.price','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.public','Product.like','Product.summary',
						'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
				),
				'order'=>$a_orders,
				'page'=> $page,
				'limit'=>$this->limit_readmore
		);
		$a_products = $this->paginate();
		$this->set('a_products_c',$a_products);
	}
}
