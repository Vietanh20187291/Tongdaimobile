<?php
App::uses('AppController', 'Controller');
/**
 * Galleries Controller
 *
 * @property Gallery $Gallery
 */
class GalleriesController extends AppController {
	
	public $helpers = array('CkEditor');
	public $components = array('Upload');
	private $limit_admin = 50;
	private $limit = 2;

	/**
	 * @Description : Điều hướng xem danh sách gallery hay chi tiết gallery
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
		}elseif(empty($this->params['ext'])){			//Điều hướng xem danh sách gallery
			
			$this->_list($slug);
			$this->render('list');
		}else{ 										//Điều hướng xem chi tiết gallery
			
			$this->_view($slug);
			$this->render('view');
		}
	}
	
	
	/**
	 * @Description : Danh sách danh mục gallery
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _listCategory(){
		$this->set('class','list_gallery_category');
		
		$lang = $this->params['lang'];
		
		//Đọc cấu hình gallery
		$a_configs = $this->_getConfig('gallery');
		$this->set('a_configs_c',$a_configs);
		
		//Breadcrumb
		$a_breadcrumb[] = array(
								'name'=>__('Hình ảnh'),
								'meta_title'=>__('Hình ảnh'),
								'url'=>'',
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);
		
		//SEO
		$this->set('title_for_layout',$a_configs['meta_title']);
		$this->set('meta_keyword_for_layout',$a_configs['meta_keyword']);
		$this->set('meta_description_for_layout',$a_configs['meta_description']);
		$this->set('meta_robots_for_layout',$a_configs['meta_robots']);
		
		//Canonical
		$a_canonical = array('controller'=>'galleries','action' => 'index','lang'=>$lang);
		$this->set('a_canonical',$a_canonical);
	}
	
	
	/**
	 * @Description : Danh sách gallery
	 *
	 * @throws 	: NotFoundException
	 * @param 	: str $slug
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _list($slug=null){
		$this->set('class','list_gallery');
		
		$a_params = $this->params;
		$lang = $a_params['lang'];
		
		//Đọc thông tin danh mục
		$a_category = $this->Gallery->GalleryCategory->find('first',array(
			'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang,'slug'=>$slug),
			'fields'=>array('id','name','lang','slug','description','meta_title','meta_keyword','meta_description','meta_robots'),
			'recursive'=>-1
		));
		if(empty($a_category)) throw new NotFoundException(__('Trang này không tồn tại',true));
		$a_category = $a_category['GalleryCategory'];
		
		$a_orders = array();
		if(empty($a_params['sort']) && empty($a_params['direction'])){
			$a_orders = array('sort'=>'asc','created'=>'desc','name'=>'asc');
		}else{
			$a_orders = array($a_params['sort']=>$a_params['direction']);
		}
		
		//Danh sách gallery
		$this->paginate = array(
			'conditions'=>array('Gallery.lang'=>$lang,'Gallery.status'=>1,'Gallery.trash'=>0,'GalleryCategory.status'=>1,'GalleryCategory.trash'=>0,'or'=>array(array('gallery_category_id'=>$a_category['id']),array('category_other like'=>"%-{$a_category['id']}-%"))),
			'fields'=>array('Gallery.id','Gallery.name','Gallery.image','Gallery.lang','Gallery.slug','Gallery.meta_title','Gallery.rel','Gallery.target',
							'GalleryCategory.slug','GalleryCategory.status'
						),
			'order'=>$a_orders,
			'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
			'limit'=>$this->limit,
			'recursive'=>0
		);
		$a_galleries = $this->paginate();
		$this->set('a_galleries_c',$a_galleries);
		$this->set('a_category_c',$a_category);
		
		//Breadcrumb
		$a_children = $this->Gallery->GalleryCategory->find('all',array(
			'conditions'=>array('status'=>1,'lang'=>$lang,'id !='=>$a_category['id']),
			'fields'=>array('id','name','meta_title','slug'),
			'order'=>'sort asc',
			'recursive'=>-1
		));
		
		$a_breadcrumb[] = array(
								'name'=>__('Hình ảnh'),
								'meta_title'=>__('Hình ảnh'),
								'url'=>array('controller'=>'galleries','action'=>'index','lang'=>$lang),
							);
							
		$children = array();
		foreach ($a_children as $val){
			$item = $val['GalleryCategory'];
			$children[] = array(
								'name'=>$item['name'],
								'meta_title'=>$item['meta_title'],
								'url'=>array('controller'=>'galleries','action'=>'index','lang'=>$lang,'slug0'=>$item['slug'])
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
		$a_canonical = array('controller'=>'galleries','action' => 'index','lang'=>$lang);
        if($slug!=null) $a_canonical=array_merge($a_canonical,array('slug0'=>$slug));
        if(!empty($a_params['page']) && $a_params['page']>1) $a_canonical = array_merge($a_canonical,array('page'=>$a_params['page']));
		if(!empty($a_params['sort']) && !empty($a_params['direction'])) $a_canonical = array_merge($a_canonical,array('sort'=>$a_params['sort'],'direction'=>$a_params['direction']));
        $this->set('a_canonical',$a_canonical);
	}
	
	
	/**
	 * @Description : Chi tiết gallery
	 *
	 * @throws 	: NotFoundException
	 * @param 	: str $slug
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _view($slug){
		$this->set('class','detail_gallery');
		$this->set('active_slideshow',false);
		
		if($slug==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		$lang = $this->params['lang'];
		
		//Đọc chi tiết gallery
		$this->Gallery->unbindModel(array('hasMany'=>array('Comment')));
		$a_gallery = $this->Gallery->find('first',array(
			'conditions'=>array('Gallery.status'=>1,'Gallery.trash'=>0,'GalleryCategory.status'=>1,'GalleryCategory.trash'=>0,'Gallery.lang'=>$lang,'Gallery.slug'=>$slug),
			'fields'=>array('Gallery.*','GalleryCategory.status','GalleryCategory.slug','GalleryCategory.name','GalleryCategory.meta_title'),
			'recursive'=>1
		));
		if(empty($a_gallery)) throw new NotFoundException(__('Trang này không tồn tại',true));

		//Gallery khác
		$a_other_galleries = $this->Gallery->find('all',array(
			'conditions'=>array('Gallery.lang'=>$lang,'Gallery.slug !='=>$slug,'gallery_category_id'=>$a_gallery['Gallery']['gallery_category_id'],'Gallery.trash'=>0),
			'fields'=>array('Gallery.id','Gallery.name','Gallery.slug','Gallery.meta_title','Gallery.rel','Gallery.target','GalleryCategory.id','GalleryCategory.slug'),
			'limit'=>4,
			'order'=>'rand()',
			'recursive'=>0
		));
		$this->set('a_gallery_c',$a_gallery);
		$this->set('a_other_galleries_c',$a_other_galleries);
		
		//Tăng lượt xem
		$this->_increaseView($a_gallery['Gallery']['id']);
		
		//Breadcrumb
		$a_children = $this->Gallery->GalleryCategory->find('all',array(
			'conditions'=>array('status'=>1,'lang'=>$lang,'id !='=>$a_gallery['Gallery']['gallery_category_id']),
			'fields'=>array('id','name','meta_title','slug'),
			'order'=>'sort asc',
			'recursive'=>-1
		));
		$a_breadcrumb[] = array(
								'name'=>__('Hình ảnh'),
								'meta_title'=>__('Hình ảnh'),
								'url'=>array('controller'=>'galleries','action'=>'index','lang'=>$lang),
							);
							
		$children = array();
		foreach ($a_children as $val){
			$item = $val['GalleryCategory'];
			$children[] = array(
								'name'=>$item['name'],
								'meta_title'=>$item['meta_title'],
								'url'=>array('controller'=>'galleries','action'=>'index','lang'=>$lang,'slug0'=>$item['slug'])
							);
		}					
		$a_breadcrumb[] = array(
								'name'=>$a_gallery['GalleryCategory']['name'],
								'meta_title'=>$a_gallery['GalleryCategory']['meta_title'],
								'url'=>array('controller'=>'galleries','action'=>'index','lang'=>$lang,'slug0'=>$a_gallery['GalleryCategory']['slug']),
								'child'=>$children
							);
		$a_breadcrumb[] = array(
								'name'=>$a_gallery['Gallery']['name'],
								'meta_title'=>$a_gallery['Gallery']['meta_title'],
								'url'=>'',
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);
		
		//SEO
		$this->set('title_for_layout',$a_gallery['Gallery']['meta_title']);
		$this->set('meta_keyword_for_layout',$a_gallery['Gallery']['meta_keyword']);
		$this->set('meta_description_for_layout',$a_gallery['Gallery']['meta_description']);
		$this->set('meta_robots_for_layout',$a_gallery['Gallery']['meta_robots']);
		
		//Canonical
		$a_canonical = array('controller'=>'galleries','action' => 'index','lang'=>$lang,'slug0'=>$a_gallery['GalleryCategory']['slug'],'slug1'=>$slug,'ext'=>'html');
		$this->set('a_canonical',$a_canonical);
	}
	
	

	
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/
	
	/**
	 * @Description : Danh sách bộ ảnh
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$lang = $this->Session->read('lang');
		$a_conditions = array('Gallery.lang'=>$lang,'Gallery.trash'=>0);
		
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->Gallery->id = $val;
						$this->Gallery->set(array('status'=>1));
						$this->Gallery->save();
					}
					$message = __('Bộ ảnh đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->Gallery->id = $val;
						$this->Gallery->set(array('status'=>0));
						$this->Gallery->save();
					}
					$message = __('Bộ ảnh đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trashItem($val);
					}
					$message = __('Bộ ảnh đã được xóa');
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
			$a_conditions = array_merge($a_conditions,array('Gallery.name like'=>'%'.$_GET['keyword'].'%'));
		}
		
		if(!empty($_GET['cate_id'])){	//Danh mục
			$a_conditions = array_merge($a_conditions,array('or'=>array(array('gallery_category_id'=>$_GET['cate_id']),array('category_other like'=>'%-'.$_GET['cate_id'].'-%'))));
		}
		
		$this->Gallery->unbindModel(array(
											'hasMany'=>array('GalleryImage')
										));
		$this->Gallery->bindModel(array('hasMany'=>array(
			'Comment'=>array(
							'className' => 'Comment',
							'foreignKey' => 'item_id',
							'dependent' => true,
							'conditions' => array('Comment.model'=>'Gallery'),
							'fields' => array('id','status','item_id','model'),
						)
			)));												
	
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array('name','image','slug','view','sort','status','lang','category_other','created','pos_1','pos_2','GalleryCategory.id','GalleryCategory.name','GalleryCategory.trash','GalleryCategory.slug','GalleryCategory.status'),
			'order'=>$a_order,
			'limit'=>$this->limit_admin,
			'recursive'=>1
		);
		
		$a_galleries = $this->paginate();
		$this->set('a_galleries_c', $a_galleries);
		
		$counter = $this->Gallery->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);
		
		//Danh mục
		$a_categories = $this->Gallery->GalleryCategory->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>array('sort'=>'asc','name'=>'asc')));
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
			$this->Gallery->recursive = -1;
			$a_gallery = $this->Gallery->read('pos_1,pos_2',$_POST['id']);
			$a_gallery = array_filter($a_gallery['Gallery']);
			
			$return = array_merge($return,array('count'=>count($a_gallery)));
		}
		
		return json_encode($return);
	}
	
	/**
	 * @Description : Sắp xếp gallery
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		$this->Gallery->id = $_POST['id'];
		$this->Gallery->set(array($_POST['field']=>$_POST['val']));
		$this->Gallery->save();
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}
	
	
	/**
	 * @Description : Sửa tên ảnh của gallery trong bảng gallery_images
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxChangeNameImage(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['name']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		$this->Gallery->GalleryImage->id = $_POST['id'];
		$this->Gallery->GalleryImage->set(array('name'=>$_POST['name']));
		if($this->Gallery->GalleryImage->save()){
			$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
			return true;
		}else return false;
	}

	
	/**
	 * @Description : Thêm bộ ảnh
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post')) {
			$oneweb_media = Configure::read('Media');
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['Gallery'];
			
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
			$a_all_slugs = $this->Gallery->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			//ID của các danh mục khác
			if(!empty($data['category_other'])){
				$data['category_other'] = '-'.implode('-', array_filter($data['category_other'])).'-';
			}
			
			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			//Ngôn ngữ
			$data['lang'] = $lang;
			$this->Gallery->create();
			if ($this->Gallery->save($data)) {
				$id = $this->Gallery->getLastInsertID();
				
				$path = realpath($oneweb_media['path']['gallery']).DS;		//Đường dẫn file ảnh
				//Upload image
				if(!empty($file['name'])){
					$result = $this->Upload->upload($file, $path, null, array('type' => 'resizemax', 'size' => $oneweb_media['size']['gallery'], 'output' => 'jpg'));
					if($result){
						$image = $this->Upload->result;
						
						//Luu ten anh vao ban ghi vua duoc them vao bang galleries
						$this->Gallery->id = $id;
						$this->Gallery->set('image',$image);
						$this->Gallery->save();
					}else{
						//Hien thi loi
						$errors=$this->Upload->errors;
						// piece together errors
						if(is_array($errors)){ $errors = implode("<br />",$errors); }
						$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error')); 
						$this->redirect(array('action'=>'edit',$id)); 
					}
				}

				//Upload nhieu anh
				if(!empty($this->request->data['GalleryImage'])){
					$tmp=array();
					foreach($this->request->data['GalleryImage'] as $key=>$val){
						if(!empty($val['name'])){
							$tmp['gallery_id']=$id;
						
							$result = $this->Upload->upload($val, $path, null, array('type' => 'resizemax', 'size' => $oneweb_media['size']['gallery'], 'output' => 'jpg'));
							if($result){
								$tmp['image'] = $this->Upload->result;
								$tmp['name'] =substr($tmp['image'], 0, (strlen($tmp['image'])-4)); 
								
								//Luu vao csdl
								$this->Gallery->GalleryImage->create();
								$this->Gallery->GalleryImage->save($tmp);
							}
							else{
								//Hien thi loi
								$errors=$this->Upload->errors;
								// piece together errors
								if(is_array($errors)){ $errors = implode("<br />",$errors); }
								$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error')); 
								$this->redirect(array('action'=>'edit',$id)); 
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
		
		//Danh sach danh muc
		$a_list_categories = $this->Gallery->GalleryCategory->find('list',array('conditions'=>array('lang'=>$lang),'order'=>'sort asc'));
		$this->set('a_list_categories_c',$a_list_categories);
	}

	/**
	 * @Description : Sửa bộ ảnh
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->Gallery->id = $id;
		if (!$this->Gallery->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_media = Configure::read('Media');
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['Gallery'];
			
			$this->Gallery->recursive = -1;
			$a_gallery = $this->Gallery->read('image',$id);
			$a_gallery = $a_gallery['Gallery'];
			
			//Ảnh đại diện
			if(!empty($data['image']['name'])){		//Up ảnh khác
				$file = $data['image'];
			}
			$data['image'] = $a_gallery['image'];
			
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
			$a_all_slugs = $this->Gallery->find('list',array('conditions'=>array('lang'=>$lang,'id !='=>$id),'fields'=>'slug','recursive'=>-1));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			//ID của các danh mục khác
			if(!empty($data['category_other'])){
				$data['category_other'] = '-'.implode('-', array_filter($data['category_other'])).'-';
			}
			
			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			if ($this->Gallery->save($data)) {
				
				$path = realpath($oneweb_media['path']['gallery']).DS;		//Đường dẫn file ảnh
				//Upload image
				if(!empty($file['name'])){
					//Xóa ảnh cũ
					if(!empty($a_gallery['image']) && file_exists($path.$a_gallery['image'])) unlink($path.$a_gallery['image']);
					
					//Up ảnh mới
					$result = $this->Upload->upload($file, $path, null, array('type' => 'resizemax', 'size' => $oneweb_media['size']['gallery'], 'output' => 'jpg'));
					if($result){
						$image = $this->Upload->result;
						
						//Luu ten anh vao ban ghi vua duoc them vao bang galleries
						$this->Gallery->id = $id;
						$this->Gallery->set('image',$image);
						$this->Gallery->save();
					}else{
						//Hien thi loi
						$errors=$this->Upload->errors;
						// piece together errors
						if(is_array($errors)){ $errors = implode("<br />",$errors); }
						$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error')); 
						$this->redirect($this->referer()); 
					}
				}
				
				
				//Upload nhieu anh
				if(!empty($this->request->data['GalleryImage'])){
					$tmp=array();
					foreach($this->request->data['GalleryImage'] as $key=>$val){
						if(!empty($val['name'])){
							$tmp['gallery_id']=$id;
						
							$result = $this->Upload->upload($val, $path, null, array('type' => 'resizemax', 'size' => $oneweb_media['size']['gallery'], 'output' => 'jpg'));
							if($result){
								$tmp['image'] = $this->Upload->result;
								$tmp['name'] =substr($tmp['image'], 0, (strlen($tmp['image'])-4)); 
								
								//Luu vao csdl
								$this->Gallery->GalleryImage->create();
								$this->Gallery->GalleryImage->save($tmp);
							}
							else{
								//Hien thi loi
								$errors=$this->Upload->errors;
								// piece together errors
								if(is_array($errors)){ $errors = implode("<br />",$errors); }
								$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error')); 
								$this->redirect($this->referer()); 
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
			$this->request->data = $this->Gallery->read(null, $id);
			$this->request->data['Gallery']['category_other'] = array_filter(explode('-', $this->request->data['Gallery']['category_other']));
		}
		
		//Danh sach danh muc
		$a_list_categories = $this->Gallery->GalleryCategory->find('list',array('conditions'=>array('lang'=>$lang),'order'=>'sort asc'));
		$this->set('a_list_categories_c',$a_list_categories);
	}

	
	/**
	 * @Description : Xóa 1 ảnh trong bảng gallery_images
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
		$this->Gallery->GalleryImage->recursive = -1;
		$a_gallery_image = $this->Gallery->GalleryImage->read('image',$_POST['id']);
		$a_gallery_image = $a_gallery_image['GalleryImage'];
		
		if($this->Gallery->GalleryImage->delete($_POST['id'])){
			$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
			
			//Xoa ảnh
			$oneweb_media = Configure::read('Media');
			$path = realpath($oneweb_media['path']['gallery']).DS;		//Đường dẫn file ảnh
		
			if(!empty($a_gallery_image['image']) && file_exists($path.$a_gallery_image['image']))
				unlink($path.$a_gallery_image['image']);
			return true;
		}else return false;
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
		//Thông tin hình ảnh
		$this->Gallery->recursive = -1;
		$a_gallery = $this->Gallery->read('id,name',$id);
		$item_gallery = $a_gallery['Gallery'];
		
		//Ghi vào bảng Trash
		$data['name'] = $item_gallery['name'];
		$data['item_id'] = $item_gallery['id'];
		$data['model'] = 'Gallery';
		$data['description'] = 'Hình ảnh';
		$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		$this->loadModel('Trash');
		$this->Trash->create();
		if($this->Trash->save($data)){
			$this->Gallery->id = $id;
			$this->Gallery->set(array('trash'=>1));
			if($this->Gallery->save()){
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		return false;
	}
}
