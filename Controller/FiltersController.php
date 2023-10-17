<?php
App::uses('AppController', 'Controller');
/**
 * Filters Controller
 *
 * @property Filter $Filter
 */
class FiltersController extends AppController {
	public $components 	= array('Paginator');
	public 	$uses = array('Product','Post');
	private $limit_product = 36;
	private $limit_post = 12;


	/**
	 * @Description : Ktra xem tìm kiếm sp hay bài viết sau đó redirect đến bộ lọc tương ứng
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function search(){
		$lang = $this->params['lang'];
		if($this->request->is('post')){
			$data = $this->request->data['Filter'];
			$a_filter = array();
			if(!empty($data['product_name'])) $a_filter = array_merge($a_filter,array('key'=>$data['product_name']));
			if(!empty($data['product_category'])) $a_filter = array_merge($a_filter,array('cate_id'=>$data['product_category']));
			if(!empty($data['product_maker'])) $a_filter = array_merge($a_filter,array('product_maker'=>$data['product_maker']));
			if(!empty($data['product_color'])) $a_filter = array_merge($a_filter,array('product_color'=>$data['product_color']));
			$this->redirect(array('action'=>'product','lang'=>$lang,'?'=>$a_filter));
		}
		$this->redirect(array('controller'=>'pages','action'=>'home','lang'=>$lang));
	}

	/**
	 * @Description : Lọc sản phẩm
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function product(){
		$this->set('class','filter filter_product');

		$oneweb_product = Configure::read('Product');
		$a_params = $this->params;
		$lang = $a_params['lang'];

		$a_conditions = array('Product.status'=>1,'Product.trash'=>0,'Product.lang'=>$lang,'ProductCategory.status'=>1);

		if(!empty($oneweb_product['maker'])){
			$a_conditions = array_merge($a_conditions,array('ProductMaker.status'=>1));

			if(!empty($_GET['product_maker'])) $a_conditions = array_merge($a_conditions,array('product_maker_id'=>$_GET['product_maker']));
		}

		if(!empty($_GET['pos'])){		//Lọc theo vị trí hiển thị
			$a_conditions = array_merge($a_conditions,array('pos_'.$_GET['pos'].' !='=>0));
		}
		if(!empty($_GET['cate_id'])){		//Lọc theo danh mục
			$a_cate_ids = array($_GET['cate_id']);
			$a_child_categories = $this->Product->ProductCategory->children($_GET['cate_id'],false,array('id','status'));
			foreach ($a_child_categories as $val){
				$item_cate = $val['ProductCategory'];
				if($item_cate['status']) $a_cate_ids[] = $item_cate['id'];
			}

			$a_conditions = array_merge($a_conditions,array('product_category_id'=>$a_cate_ids));
		}


		if(!empty($_GET['key'])){		//Lọc theo tên sp
			$key_s = explode(' ',strtolower($this->Oneweb->boDau(trim($_GET['key']))));
			$key_s = array_filter($key_s);
			foreach ($key_s as $keyword) {
				$keyword_conditions[] = array('OR'=>array('Product.name like' => '%'.trim($keyword).'%', 'Product.name_en like' =>'%'.trim($keyword).'%'));
			}
			$a_conditions = array_merge($a_conditions,array('OR' => $keyword_conditions));
		}

		// if(!empty($_GET['product_color'])){
		// 	$a_conditions = array_merge($a_conditions,array('Product.product_color'=>$_GET['product_color']));
		// }
		// if(!empty($_GET['product_diameter'])){
		// 	$a_conditions = array_merge($a_conditions,array('Product.product_diameter'=>$_GET['product_diameter']));
		// }
		// if(!empty($_GET['sighted'])){
		// 	if($_GET['sighted'] == 0) $a_conditions = array_merge($a_conditions,array('Product.sighted >'=>0));
		// 	else $a_conditions = array_merge($a_conditions,array('Product.sighted'=>0));
		// }
		$a_orders = array();
		if(empty($_GET['sort']) && empty($_GET['direction'])){
            $a_orders = array_merge($a_orders, array('Product.sort'=>'asc','Product.created'=>'desc','Product.name'=>'asc'));
        }else{
            $a_orders = array($_GET['sort']=>$_GET['direction'],'Product.sort'=>'asc','Product.created'=>'desc');
        }

		$a_products = $this->Product->find('all', array(
			'conditions'=>$a_conditions,
			'contain'=>array('ProductCategory','ProductMaker'),
			'fields'=>array('Product.id','Product.name','Product.name_en','Product.lang','Product.slug','Product.meta_title','Product.rel','Product.target','Product.image','Product.price','Product.price_new','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.summary',
							'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
						),
			'order'=>$a_orders,
		));
		if(!empty($a_products) && !empty($key_s)) {
			foreach ($a_products as $s => $i_product) {
	      $product = $i_product['Product'];
	      $product_name = strtolower($this->Oneweb->boDau($product['name']));
	      $product_name_en = strtolower($this->Oneweb->boDau($product['name_en']));
	      $x = 0;
	      foreach ($key_s as $k_s) {
	      	if(strpos($product_name, trim($k_s)) !== false){
	      		$x++;
	      	}
	      	if(strpos($product_name_en, trim($k_s)) !== false){
	      		$x++;
	      	}
	      }
	      $a_products[$s]['Product']['search_count'] = $x;
	  	}
			usort($a_products,array($this, 'product_sort'));
		}
		//Tổng số sản phẩm tìm thấy
		$total = $this->Product->find('count',array('conditions'=>$a_conditions));
		//Trang
		$limit = $this->limit_product;
		$a_page['total'] = ceil($total/$limit);
		$a_page['current'] = (!empty($_GET['page']))?$_GET['page']:1;
		$this->set('a_products_c',$a_products);
		$this->set('a_page',$a_page);
		$this->set('total_c',$total);
		$this->set('limit',$limit);

		//Breadcrumb
		$a_breadcrumb[] = array(
								'name'=>__('Kết quả tìm kiếm').': '.$total.' '.__('sản phẩm'),
								'meta_title'=>'',
								'url'=>'',
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);

		//SEO
		$this->set('title_for_layout',__('Kết quả',true));
		$this->set('meta_robots_for_layout','noindex,nofollow');
	}

	function product_sort($a,$b)
	{
		if ($a['Product']['search_count'] == $b['Product']['search_count']) return 1;
		return ($a['Product']['search_count'] > $b['Product']['search_count'])?-1:1;
	}
	/**
	 * @Description : Lọc bài viết
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function post(){
		$this->set('class','filter filter_post');

		$oneweb_post = Configure::read('Post');
		$a_params = $this->params;
		$lang = $a_params['lang'];

		$a_conditions = array('Post.status'=>1,'Post.lang'=>$lang,'PostCategory.status'=>1);

		if(!empty($_GET['cate_id'])){		//Lọc theo danh mục
			$a_cate_ids = array($_GET['cate_id']);
			$a_child_categories = $this->Post->PostCategory->children($_GET['cate_id'],false,array('id','status'));
			foreach ($a_child_categories as $val){
				$item_cate = $val['PostCategory'];
				if($item_cate['status']) $a_cate_ids[] = $item_cate['id'];
			}

			$a_conditions = array_merge($a_conditions,array('post_category_id'=>$a_cate_ids));
		}
		if(!empty($_GET['key'])){		//Lọc theo tên bài viết
			$a_conditions = array_merge($a_conditions,array('Post.name like'=>'%'.$_GET['key'].'%'));
		}

		$this->paginate = array(
			'conditions'=>$a_conditions,
			'contain'=>array('PostCategory'),
			'fields'=>array('Post.id','Post.name','Post.lang','Post.slug','Post.meta_title','Post.rel','Post.target','Post.image','summary','Post.created',
							'PostCategory.slug','PostCategory.path','PostCategory.status','PostCategory.position'
						),
			'order'=>array('created'=>'desc','name'=>'asc'),
			'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
			'limit'=>$this->limit_post
		);
		$a_posts = $this->paginate('Post');

		//Tổng số bài viết tìm thấy
		$total = $this->Post->find('count',array('conditions'=>$a_conditions));

		$this->set('a_posts_c',$a_posts);
		$this->set('total_c',$total);

		//Breadcrumb
		$a_breadcrumb[] = array(
								'name'=>__('Kết quả tìm kiếm').': '.$total.' '.__('bài viết'),
								'meta_title'=>'',
								'url'=>'',
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);

		//SEO
		$this->set('title_for_layout',__('Kết quả',true));
		$this->set('meta_robots_for_layout','noindex,nofollow');
	}
	public function ajaxGetSearch() {
		$this->layout = 'ajax';
		$this->autoRender = true;
		if(empty($_POST['inputString'])) throw new NotFoundException(__('Trang này không tồn tại',true));
		$a_conditions = array('Product.status'=>1,'Product.trash'=>0,'ProductCategory.status'=>1);
		$key_s = explode(' ',strtolower($this->Oneweb->boDau(trim($_POST['inputString']))));
		$key_s = array_filter($key_s);
		foreach ($key_s as $keyword) {
			$keyword_conditions[] = array('OR'=>array('Product.name like' => '%'.trim($keyword).'%', 'Product.name_en like' =>'%'.trim($keyword).'%'));
		}
		$a_conditions = array_merge($a_conditions,array('OR' => $keyword_conditions));
		$a_products = $this->Product->find('all', array(
			'conditions'=>$a_conditions,
			'contain'=>array('ProductCategory'),
			'fields'=>array('Product.id','Product.name','Product.name_en','Product.lang','Product.slug','Product.meta_title','Product.rel','Product.target','Product.image','Product.price','Product.price_new','Product.quantity','Product.discount','Product.discount_unit','Product.promotion','Product.summary',
							'ProductCategory.slug','ProductCategory.path','ProductCategory.status'
						),
			'order'=>array('Product.sort'=>'asc','Product.created'=>'desc','Product.name'=>'asc')
		));
		foreach ($a_products as $s => $i_product) {
        $product = $i_product['Product'];
        $product_name = strtolower($this->Oneweb->boDau($product['name']));
        $product_name_en = strtolower($this->Oneweb->boDau($product['name_en']));
        $x = 0;
        foreach ($key_s as $k_s) {
        	if(strpos($product_name, trim($k_s)) !== false){
        		$x++;
        	}
        	if(strpos($product_name_en, trim($k_s)) !== false){
        		$x++;
        	}
        }
        $a_products[$s]['Product']['search_count'] = $x;
    }
		usort($a_products,array($this, 'product_sort'));
		return $this->set('results', $a_products);
	}
}
?>