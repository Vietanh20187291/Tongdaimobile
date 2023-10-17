<?php
App::uses('AppController', 'Controller');
/**
 * Tags Controller
 *
 * @property Video $Video
 */
class TagsController extends AppController {

	public $helpers = array('CkEditor');
	private  $limit_admin = 30;
	private $limit_product = 24;
	private $limit_post = 10;

	public function beforeFilter() {
		parent::beforeFilter();
	}
	/**
	 * @Description : Danh sách sản phẩm, bài viết theo Tag
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function index($id=null, $name=null) {
		if($name==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		$oneweb_product = Configure::read('Product');
		$oneweb_post = Configure::read('Post');

		$a_params = $this->params;
		$lang = $a_params['lang'];

		// Thông tin tag truyền vào
		$a_tags = $this->Tag->find('first',array('conditions'=>array('id'=>$id),'fields'=>array('id','name','description','meta_title','meta_description','meta_keyword', 'meta_robots', 'slug'),'recursive'=>-1));
		if(empty($a_tags)) throw new NotFoundException(__('Trang này không tồn tại',true));		//Truong hop ko tim thay
		$this->set('a_tags_c',$a_tags);

		// SEO tag này
		$this->set('title_for_layout',$a_tags['Tag']['meta_title']);
		$this->set('meta_keyword_for_layout',$a_tags['Tag']['meta_keyword']);
		$this->set('meta_description_for_layout',$a_tags['Tag']['meta_description']);
		$this->set('meta_robots_for_layout',$a_tags['Tag']['meta_robots']);
	}

	/**
	 * @Description :
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Huu Quynh - quynh@url.vn
	 */
	function ajaxGetProduct(){
		$this->layout = 'ajax';
		if(empty($_POST['tag_id']) || empty($_POST['page'])) throw new NotFoundException(__('Trang này không tồn tại',true));
		$lang = $this->params['lang'];
		$url_product = Configure::read('Product');


		//Danh mục sản phẩm đang kích hoạt
		$this->loadModel('Product');
		$this->loadModel('ProductCategory');

		$a_pro_categories = $this->Product->ProductCategory->find('all',array('conditions'=>array('lang'=>$lang,'status'=>1),'fields'=>'id,slug,path','recursive'=>-1));

		$a_pro_cate_ids = array();	//Id cua danh muc da kich hoat
		foreach($a_pro_categories as $val) $a_pro_cate_ids[] = $val['ProductCategory']['id'];
		$a_conditions = array('tag_id'=>$_POST['tag_id'],'Product.lang'=>$lang,'Product.status'=>1,'Product.product_category_id'=>$a_pro_cate_ids);

		//Hãng sản xuất
		if(!empty($oneweb_product['maker'])){
			$a_pro_maker_ids = $this->Product->ProductMaker->find('list',array('fields'=>'id','conditions'=>array('lang'=>$lang,'status'=>1)));
			sort($a_pro_maker_ids);
			$a_conditions = array_merge($a_conditions,array('Product.product_maker_id'=>$a_pro_maker_ids));
		}
		$this->loadModel('TagPriority');
		$this->TagPriority->bindModel(array('belongsTo'=>array(
				'Product' => array(
						'className' => 'Product',
						'foreignKey' => 'item_id',
						'conditions' => array('TagPriority.model'=>'Product'),
						'fields' => '',
						'order' => ''
				))));

		//Danh sach san pham phan trang, da sx theo thu tu tag
		$a_products = $this->TagPriority->find('all',array(
				'conditions'=>$a_conditions,
				'contain'=>array('Product'),
				'fields'=>array('Product.id','Product.product_category_id','Product.slug','Product.image','Product.quantity','Product.name','Product.price','Product.price_new','Product.discount','Product.discount_unit','Product.promotion','Product.meta_title','Product.lang','Product.target','Product.rel'),
				'order'=>array('TagPriority.position'=>'asc','Product.created'=>'desc'),
				'recursive'=>0,
				'page'=>$_POST['page'],
				'limit'=>$this->limit_product
		));

		//Gan slug danh muc vao danh sach san pham
		foreach($a_products as $key=>$val){
			$flag = false;
			$i = 0;
			do{
				if($val['Product']['product_category_id']==$a_pro_categories[$i]['ProductCategory']['id']){
					$a_products[$key]['ProductCategory'] = $a_pro_categories[$i]['ProductCategory'];
					$flag = true;
				}
				$i++;
			}while(!$flag && $i<count($a_pro_categories));
		}

		$this->set('a_products_c',$a_products);

		//Tính số trang của sản phẩm
		$this->TagPriority->bindModel(array('belongsTo'=>array(
				'Product' => array(
						'className' => 'Product',
						'foreignKey' => 'item_id',
						'conditions' => array('TagPriority.model'=>'Product'),
						'fields' => '',
						'order' => ''
				))));
		$all_product = $this->TagPriority->find('count',array(		//Tong so san pham
				'conditions'=>$a_conditions,
				'recursive'=>0,
		));
		$all_pages = ceil($all_product/$this->limit_product); //Tong so trang

		$this->set('all_pages_c',$all_pages);
		$this->set('current_page_c',$_POST['page']);
	}
	/**
	 * @Description :
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Huu Quynh - quynh@url.vn
	 */
	function ajaxGetPost(){
		$this->layout = 'ajax';
		if(empty($_POST['tag_id']) || empty($_POST['page'])) throw new NotFoundException(__('Trang này không tồn tại',true));
		$lang = $this->params['lang'];
		$url_product = Configure::read('Product');

		//Danh mục bai viet đang kích hoạt
		$this->loadModel('Post');
		$this->loadModel('PostCategory');
		$a_post_categories = $this->Post->PostCategory->find('all',array('conditions'=>array('lang'=>$lang,'status'=>1),'fields'=>'id,slug,position,path','recursive'=>-1));

		$a_post_cate_ids = array();	//Id cua danh muc da kich hoat
		foreach($a_post_categories as $val) $a_post_cate_ids[] = $val['PostCategory']['id'];

		$a_conditions = array('tag_id'=>$_POST['tag_id'],'Post.lang'=>$lang,'Post.status'=>1,'Post.post_category_id'=>$a_post_cate_ids);

		$this->loadModel('TagPriority');
		$this->TagPriority->bindModel(array('belongsTo'=>array(
				'Post' => array(
						'className' => 'Post',
						'foreignKey' => 'item_id',
						'conditions' => array('TagPriority.model'=>'Post'),
						'fields' => '',
						'order' => ''
				))));
		//Danh sach bai viet, da sx theo thu tu tag
		$a_posts = $this->TagPriority->find('all',array(
				'conditions'=>$a_conditions,
				'contain'=>array('Post'),
				'fields'=>array('Post.id','Post.post_category_id','Post.created','Post.summary','Post.slug','Post.image','Post.name','Post.meta_title','Post.lang','Post.target','Post.rel'),
				'order'=>array('TagPriority.position'=>'asc','Post.created'=>'desc'),
				'recursive'=>0,
				'page'=>$_POST['page'],
				'limit'=>$this->limit_post
		));

		//Gan slug danh muc vao danh sach san pham
		foreach($a_posts as $key=>$val){
			$flag = false;
			$i = 0;
			do{
				if($val['Post']['post_category_id']==$a_post_categories[$i]['PostCategory']['id']){
					$a_posts[$key]['PostCategory'] = $a_post_categories[$i]['PostCategory'];
					$flag = true;
				}
				$i++;
			}while(!$flag && $i<count($a_post_categories));
		}
		$this->set('a_posts_c',$a_posts);

		//Tính số trang của sản phẩm
		$this->TagPriority->bindModel(array('belongsTo'=>array(
				'Post' => array(
						'className' => 'Post',
						'foreignKey' => 'item_id',
						'conditions' => array('TagPriority.model'=>'Post'),
						'fields' => '',
						'order' => ''
				))));
		$all_post = $this->TagPriority->find('count',array(		//Tong so san pham
				'conditions'=>$a_conditions,
				'recursive'=>0,
		));
		$all_pages = ceil($all_post/$this->limit_post); //Tong so trang

		$this->set('all_pages_c',$all_pages);
		$this->set('current_page_c',$_POST['page']);
	}

	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description :
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Huu Quynh - quynh@url.vn
	 */
	public function admin_index(){
		$lang = $this->Session->read('lang');
		$this->paginate = array(
				'conditions'=>array('lang'=>$lang),
				'fields'=>array('id','name','number','description','lang','slug'),
				'order'=>'number desc',
				'limit'=>$this->limit_admin,
				'recursive'=>-1
		);
		$a_tags = $this->paginate();

		$this->set('a_tags_c',$a_tags);

		//Đồng bộ Tag
		if(!$this->Session->check('synTag')){
			$this->_synTag();
			$this->Session->write('synTag',true);
		}
	}
	/**
	 * @Description :
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Huu Quynh - quynh@url.vn
	 */
	public function admin_edit($id=null){
		$this->Tag->id = $id;
		if (!$this->Tag->exists()) throw new NotFoundException(__('Invalid'));

		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_seo = Configure::read('Seo');

			$data = $this->request->data['Tag'];


			if($oneweb_seo){
				//Slug
				if(empty($data['slug'])) $data['slug'] = $this->converToSlug($data['name']);
				//Meta title
				if(empty($data['meta_title'])) $data['meta_title'] = $data['name'];
			}else{

				//Meta title
				$data['meta_title'] = $data['name'];
				$data['meta_keyword'] = '';
				$data['meta_description'] = '';
			}

			//Ngày sửa
			if(!empty($data['modified'])){
				$data['modified'] = mktime($data['modified']['hour'],$data['modified']['min'],0,$data['modified']['month'],$data['modified']['day'],$data['modified']['year']);
			}else{
				$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			}
			if ($this->Tag->save($data)) {
				$this->Session->setFlash('<span>'.__('Thông tin đã được cập nhật').'</span>','default',array('class'=>'success'));
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				if (!empty($_POST['save'])){
					$this->redirect($this->referer());
				}else{
					$url = (!empty($_GET['url']))?urldecode($_GET['url']):array('action'=>'index');
					$this->redirect($url);
				}
			} else {
				$this->Session->setFlash(__('<span class="error">Có lỗi, vui lòng thử lại.</span>', true));
			}
		}
		$this->request->data = $this->Tag->read(null, $id);
	}

	/**
	 * @Description :
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Huu Quynh - quynh@url.vn
	 */
	public function admin_synTag(){
		$this->layout = false;
		$this->autoRender = false;
		$this->_synTag();
		$this->Session->setFlash('<span>'.__('Thông tin đã được đồng bộ lại').'</span>','default',array('class'=>'success'));
		$this->redirect($this->referer());
	}
	/**
	 * @Description :
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Huu Quynh - quynh@url.vn
	 */
	private function _synTag(){
		$lang = $this->lang;
		$oneweb_product = Configure::read('Product');
		$a_tags = array(); //Danh sách tag cần cập nhật lại
		$this->loadModel('Product','ProductCategory','ProductMaker');
		$a_conditions = array('Product.lang'=>$lang,'Product.status'=>1,'ProductCategory.status'=>1);

		if($oneweb_product['maker']) $a_conditions = array_merge($a_conditions,array('ProductMaker.status'=>1));

		//Danh sách sản phẩm

		$a_products = $this->Product->find('all',array(
				'conditions'=>$a_conditions,
				'contain'=>array('ProductCategory','ProductMaker'),
				'fields'=>array('id','tag'),
		));

// 		debug($a_products);die;

		$i=0;
		foreach($a_products as $val){
			$a_tags[$i]['id'] = $val['Product']['id'];
			$a_tags[$i]['model'] = 'Product';
			$tmp = array();
			foreach(explode(',', $val['Product']['tag']) as $val2){
				$tmp[] = trim($val2);
			}
			$a_tags[$i]['tag']= $tmp;
			$a_tags[$i]['tag_str'] = $val['Product']['tag'];
			$i++;
		}


		//Danh sách bài viết
		$this->loadModel('Post','PostCategory');
		$a_posts = $this->Post->find('all',array(
				'conditions'=>array('Post.lang'=>$lang,'Post.status'=>1,'PostCategory.status'=>1),
				'fields'=>array('id','tag')
		));
		foreach($a_posts as $key=>$val){
			$a_tags[$i]['id'] = $val['Post']['id'];
			$a_tags[$i]['model'] = 'Post';
			$tmp = array();
			foreach(explode(',', $val['Post']['tag']) as $val2){
				$tmp[] = trim($val2);
			}
			$a_tags[$i]['tag']= $tmp;
			$a_tags[$i]['tag_str'] = $val['Post']['tag'];
			$i++;
		}


		//Xóa các bản ghi thừa trong bảng tag_priorities
		$this->loadModel('TagPriority');
		$a_tag_priorities = $this->TagPriority->find('all',array('conditions'=>array('lang'=>$lang),'order'=>'item_id asc','recursive'=>-1));

		$tmp = array();
		foreach($a_tag_priorities as $val){		//Sắp xếp theo nhóm
			$item = $val['TagPriority'];
			$tmp[$item['item_id']][] = $item;
		}
		$a_tag_priorities = $tmp;

		$a_del_ids = array();		//Mảng id loại bỏ
		foreach($a_tag_priorities as $val){		//Loại bỏ các trường hợp trùng lặp
			$tmp = array();
			foreach($val as $val2){
				if(!in_array($val2['tag_id'], $tmp)) $tmp[] = $val2['tag_id'];
				else $a_del_ids[] = $val2['id'];
			}
		}

		$this->TagPriority->deleteAll(array('TagPriority.id'=>$a_del_ids));

		//Thiết lập trường number trong bảng tag = 0;
		$this->Tag->updateAll(array('number'=>0),array('lang'=>$this->lang));

		//Thiết lập lại number
		foreach($a_tags as $val){
			$this->_addTag($val['tag']);
			$this->_setTagPriority($val['tag_str'],$val['id'],$val['model'],'admin_edit');
		}

		//Xóa các bản ghi có number = 0 trong bảng tag
		$this->Tag->deleteAll(array('number'=>0));
	}

	// Xoá tag
	public function admin_ajaxDeleteItem() {
		$this->Tag->id = $_POST['id'];
		if (!$this->Tag->exists()) throw new NotFoundException(__('Invalid'));

		$this->layout = false;
		$this->autoRender = false;

		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		$this->loadModel('TagPriority');
		$this->TagPriority->deleteAll(array('tag_id'=>$_POST['id']));
		return $this->Tag->deleteAll(array('id'=>$_POST['id']));
	}
}
