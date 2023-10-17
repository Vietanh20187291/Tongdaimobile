<?php
App::uses('AppController', 'Controller');
/**
 * Videos Controller
 *
 * @property Video $Video
 */
class VideosController extends AppController {
	
	public $helpers = array('CkEditor');
	private  $limit_admin = 50;
	private $limit = 10;

	
	/**
	 * @Description : Điều hướng xem danh sách video hay chi tiết video
	 *
	 * @throws 	: NotFoundException
	 * @param 	: string slug
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function index($slug=null) {
		if($slug==null){								//Danh sách danh mục
			
			$this->_listCategory();
			$this->render('list_category');
		}elseif(empty($this->params['ext'])){			//Điều hướng xem danh sách video
			
			$this->_list($slug);
			$this->render('list');
		}else{ 										//Điều hướng xem chi tiết video
			
			$this->_view($slug);
			$this->render('view');
		}
	}
	
	
	/**
	 * @Description : Danh sách danh mục video
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _listCategory(){
		$this->set('class','list_video_category');
		
		$lang = $this->params['lang'];
		
		//Đọc cấu hình video
		$a_configs = $this->_getConfig('video');
		$this->set('a_configs_c',$a_configs);
		
		//Breadcrumb
		$a_breadcrumb[] = array(
								'name'=>__('Video'),
								'meta_title'=>__('Video'),
								'url'=>'',
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);
		
		//SEO
		$this->set('title_for_layout',$a_configs['meta_title']);
		$this->set('meta_keyword_for_layout',$a_configs['meta_keyword']);
		$this->set('meta_description_for_layout',$a_configs['meta_description']);
		$this->set('meta_robots_for_layout',$a_configs['meta_robots']);
		
		//Canonical
		$a_canonical = array('controller'=>'videos','action' => 'index','lang'=>$lang);
		$this->set('a_canonical',$a_canonical);
	}
	
	
	/**
	 * @Description : Danh sách video
	 *
	 * @throws 	: NotFoundException
	 * @param 	: str $slug
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _list($slug=null){
		$this->set('class','list_video');
		
		$a_params = $this->params;
		$lang = $a_params['lang'];
		
		//Đọc thông tin danh mục
		$a_category = $this->Video->VideoCategory->find('first',array(
			'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'slug'=>$slug),
			'fields'=>array('id','name','lang','slug','description','meta_title','meta_keyword','meta_description','meta_robots'),
			'recursive'=>-1
		));
		if(empty($a_category)) throw new NotFoundException(__('Trang này không tồn tại',true));
		$a_category = $a_category['VideoCategory'];
		
		$a_orders = array();
		if(empty($a_params['sort']) && empty($a_params['direction'])){
			$a_orders = array('sort'=>'asc','created'=>'desc','name'=>'asc');
		}else{
			$a_orders = array($a_params['sort']=>$a_params['direction']);
		}
		
		//Danh sách video
		$this->Video->unbindModel(array('hasMany'=>array('Comment')));
		$this->paginate = array(
			'conditions'=>array('Video.lang'=>$lang,'Video.status'=>1,'Video.trash'=>0,'VideoCategory.status'=>1,'VideoCategory.trash'=>0,'or'=>array(array('video_category_id'=>$a_category['id']),array('category_other like'=>"%-{$a_category['id']}-%"))),
			'fields'=>array('Video.id','Video.name','Video.lang','Video.slug','Video.meta_title','Video.rel','Video.target','youtube',
							'VideoCategory.slug','VideoCategory.status'
						),
			'order'=>$a_orders,
			'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
			'limit'=>$this->limit
		);
		$a_videos = $this->paginate();
		$this->set('a_videos_c',$a_videos);
		
		$this->set('a_category_c',$a_category);
		
		
		//Breadcrumb
		$a_children = $this->Video->VideoCategory->find('all',array(
			'conditions'=>array('status'=>1,'lang'=>$lang,'id !='=>$a_category['id']),
			'fields'=>array('id','name','meta_title','slug'),
			'order'=>'sort asc',
			'recursive'=>-1
		));
		
		$a_breadcrumb[] = array(
								'name'=>__('Video'),
								'meta_title'=>__('Video'),
								'url'=>array('controller'=>'videos','action'=>'index','lang'=>$lang),
							);
							
		$children = array();
		foreach ($a_children as $val){
			$item = $val['VideoCategory'];
			$children[] = array(
								'name'=>$item['name'],
								'meta_title'=>$item['meta_title'],
								'url'=>array('controller'=>'videos','action'=>'index','lang'=>$lang,'slug0'=>$item['slug'])
							);
		}					
		$a_breadcrumb[] = array(
								'name'=>$a_category['name'],
								'meta_title'=>$a_category['meta_title'],
								'url'=>'',
								'child'=>$children
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);
		
		
		//SEO
		$this->set('title_for_layout',$a_category['meta_title']);
		$this->set('meta_keyword_for_layout',$a_category['meta_keyword']);
		$this->set('meta_description_for_layout',$a_category['meta_description']);
		$this->set('meta_robots_for_layout',$a_category['meta_robots']);
		
		//Canonical
		$a_canonical = array('controller'=>'videos','action' => 'index','lang'=>$lang);
        if($slug!=null) $a_canonical=array_merge($a_canonical,array('slug0'=>$slug));
        if(!empty($a_params['page']) && $a_params['page']>1) $a_canonical = array_merge($a_canonical,array('page'=>$a_params['page']));
		if(!empty($a_params['sort']) && !empty($a_params['direction'])) $a_canonical = array_merge($a_canonical,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
        $this->set('a_canonical',$a_canonical);
	}
	
	
	/**
	 * @Description : Chi tiết video
	 *
	 * @throws 	: NotFoundException
	 * @param 	: str $slug
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _view($slug){
		$this->set('class','detail_video');
		$this->set('active_slideshow',false);
		$this->set('column_right',false);		//Thiết lập loại bỏ cột phải
		
		if($slug==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		$lang = $this->params['lang'];
		
		//Đọc chi tiết video
		$a_video = $this->Video->find('first',array(
			'conditions'=>array('Video.lang'=>$lang,'Video.slug'=>$slug,'Video.status'=>1,'Video.trash'=>0,'VideoCategory.status'=>1,'VideoCategory.trash'=>0),
			'fields'=>array('Video.*','VideoCategory.status','VideoCategory.slug','VideoCategory.name','VideoCategory.meta_title'),
			'recursive'=>0
		));
		if(empty($a_video)) throw new NotFoundException(__('Trang này không tồn tại',true));

		//Video khác
		$a_other_videos = $this->Video->find('all',array(
			'conditions'=>array('Video.status'=>1,'Video.trash'=>0,'Video.lang'=>$lang,'Video.slug !='=>$slug,'video_category_id'=>$a_video['Video']['video_category_id']),
			'fields'=>array('Video.id','Video.name','Video.slug','Video.meta_title','Video.rel','Video.target','youtube','VideoCategory.slug'),
			'limit'=>4,
			'order'=>'rand()',
			'recursive'=>0
		));
	
		$this->set('a_video_c',$a_video);
		$this->set('a_other_videos_c',$a_other_videos);
		
		//Tăng lượt xem
		$this->_increaseView($a_video['Video']['id']);

		//Breadcrumb
		$a_children = $this->Video->VideoCategory->find('all',array(
			'conditions'=>array('status'=>1,'lang'=>$lang,'id !='=>$a_video['Video']['video_category_id']),
			'fields'=>array('id','name','meta_title','slug'),
			'order'=>'sort asc',
			'recursive'=>-1
		));
		$a_breadcrumb[] = array(
								'name'=>__('Video'),
								'meta_title'=>__('Video'),
								'url'=>array('controller'=>'videos','action'=>'index','lang'=>$lang),
							);
							
		$children = array();
		foreach ($a_children as $val){
			$item = $val['VideoCategory'];
			$children[] = array(
								'name'=>$item['name'],
								'meta_title'=>$item['meta_title'],
								'url'=>array('controller'=>'videos','action'=>'index','lang'=>$lang,'slug0'=>$item['slug'])
							);
		}					
		$a_breadcrumb[] = array(
								'name'=>$a_video['VideoCategory']['name'],
								'meta_title'=>$a_video['VideoCategory']['meta_title'],
								'url'=>array('controller'=>'videos','action'=>'index','lang'=>$lang,'slug0'=>$a_video['VideoCategory']['slug']),
								'child'=>$children
							);
		$a_breadcrumb[] = array(
								'name'=>$a_video['Video']['name'],
								'meta_title'=>$a_video['Video']['meta_title'],
								'url'=>'',
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);
		
		//SEO
		$this->set('title_for_layout',$a_video['Video']['meta_title']);
		$this->set('meta_keyword_for_layout',$a_video['Video']['meta_keyword']);
		$this->set('meta_description_for_layout',$a_video['Video']['meta_description']);
		$this->set('meta_robots_for_layout',$a_video['Video']['meta_robots']);
		
		//Canonical
		$a_canonical = array('controller'=>'videos','action' => 'index','lang'=>$lang,'slug0'=>$a_video['VideoCategory']['slug'],'slug1'=>$slug,'ext'=>'html');
		$this->set('a_canonical',$a_canonical);
	}
	

	
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/
	
	/**
	 * @Description : Danh sách video
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$lang = $this->Session->read('lang');
		$a_conditions = array('Video.lang'=>$lang,'Video.trash'=>0);
		
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->Video->id = $val;
						$this->Video->set(array('status'=>1));
						$this->Video->save();
					}
					$message = __('Video đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->Video->id = $val;
						$this->Video->set(array('status'=>0));
						$this->Video->save();
					}
					$message = __('Video đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trashItem($val);
					}
					$message = __('Video đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success')); 
		}
		
		if(!empty($_GET['position'])){	//Vi tri hien thi
			$a_conditions = array_merge($a_conditions,array('pos_'.$_GET['position'].' !='=>0));
			$a_order = array('pos_'.$_GET['position']=>'asc');
		}else{
			$a_order = array('sort'=>'asc');
		}
		$a_order = array_merge($a_order,array('created'=>'desc'));
		
		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tìm kiếm')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('Video.name like'=>'%'.$_GET['keyword'].'%'));
		}
		if(!empty($_GET['cate_id'])){	//Danh mục
			$a_conditions = array_merge($a_conditions,array('or'=>array(array('video_category_id'=>$_GET['cate_id']),array('category_other like'=>"%-{$_GET['cate_id']}-%"))));
		}
		
		$this->Video->unbindModel(array(
											'hasMany'=>array('VideoImage')
										));
		$this->Video->bindModel(array('hasMany'=>array(
			'Comment'=>array(
							'className' => 'Comment',
							'foreignKey' => 'item_id',
							'dependent' => true,
							'conditions' => array('Comment.model'=>'Video'),
							'fields' => array('id','status','item_id','model'),
						)
			)));								
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array('id','name','youtube','slug','view','sort','status','lang','category_other','created','pos_1','pos_2','VideoCategory.id','VideoCategory.name','VideoCategory.trash','VideoCategory.slug','VideoCategory.status'),
			'order'=>$a_order,
			'limit'=>$this->limit_admin,
			'recursive'=>1
		);
		
		$a_videos = $this->paginate();
		$this->set('a_videos_c', $a_videos);
		
		$counter = $this->Video->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);
		
		//Danh mục
		$a_categories = $this->Video->VideoCategory->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>array('sort'=>'asc','name'=>'asc')));
		$this->set('a_categories_c',$a_categories);
		
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
			$this->Video->recursive = -1;
			$a_video = $this->Video->read('pos_1,pos_2',$_POST['id']);
			$a_video = array_filter($a_video['Video']);
			
			$return = array_merge($return,array('count'=>count($a_video)));
		}
		
		return json_encode($return);
	}
	
	/**
	 * @Description : Sắp xếp video
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		$this->Video->id = $_POST['id'];
		$this->Video->set(array($_POST['field']=>$_POST['val']));
		$this->Video->save();
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}
	
	
	/**
	 * @Description : Thêm video
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post')) {
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['Video'];
			
			//Ảnh đại diện
			$data['youtube'] = trim(strrchr($data['youtube'], '/watch?v='),'/watch?v=');
			
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
			$a_all_slugs = $this->Video->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			//ID của các danh mục khác
			if(!empty($data['category_other'])){
				$data['category_other'] = '-'.implode('-', array_filter($data['category_other'])).'-';
			}
			
			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			//Ngôn ngữ
			$data['lang'] = $lang;
		
			$this->Video->create();
			if ($this->Video->save($data)) {
				$id = $this->Video->getLastInsertID();
				
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
		
		//Danh sach danh muc
		$a_list_categories = $this->Video->VideoCategory->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0)));
		$this->set('a_list_categories_c',$a_list_categories);
	}

	/**
	 * @Description : Sửa video
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->Video->id = $id;
		if (!$this->Video->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['Video'];
			
			//Ảnh đại diện
			$data['youtube'] = trim(strrchr($data['youtube'], '/watch?v='),'/watch?v=');
			
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
			$a_all_slugs = $this->Video->find('list',array('conditions'=>array('lang'=>$lang,'id !='=>$id),'fields'=>'slug','recursive'=>-1));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			//ID của các danh mục khác
			if(!empty($data['category_other'])){
				$data['category_other'] = '-'.implode('-', array_filter($data['category_other'])).'-';
			}
			
			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			if ($this->Video->save($data)) {
				
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
			$this->request->data = $this->Video->read(null, $id);
			$this->request->data['Video']['category_other'] = array_filter(explode('-', $this->request->data['Video']['category_other']));
		}
		
		//Danh sach danh muc
		$a_list_categories = $this->Video->VideoCategory->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0)));
		$this->set('a_list_categories_c',$a_list_categories);
	}
	
	

	/**
	 * @Description : Cho hình ảnh vào thùng rác
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
	 * @Description : Đưa hình ảnh vào thùng rac
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int data
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function trashItem($id){
		//Thông tin
		$this->Video->recursive = -1;
		$a_video = $this->Video->read('id,name',$id);
		$item_video = $a_video['Video'];
		
		//Ghi vào bảng Trash
		$data['name'] = $item_video['name'];
		$data['item_id'] = $item_video['id'];
		$data['model'] = 'Video';
		$data['description'] = 'Video';
		$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		$this->loadModel('Trash');
		$this->Trash->create();
		if($this->Trash->save($data)){
			$this->Video->id = $id;
			$this->Video->set(array('trash'=>1));
			if($this->Video->save()){
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		return false;
	}
}
