<?php
App::uses('AppController', 'Controller');
/**
 * Documents Controller
 *
 * @property Document $Document
 */
class DocumentsController extends AppController {
	
	public $components = array('Upload');
	public $helpers = array('CkEditor');
	public $uses = array('Document','DocumentCategory');
	private $limit_admin = 50;
	private $limit = 18;
	private $limit_view = 20;


	/**
	 * @Description : Danh sách danh mục tài liệu
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function index() {
		$this->set('class','list_document');
		
		$a_params = $this->params;
		$lang = $a_params['lang'];
		
		//Danh sach danh muc
		$this->paginate = array(
			'conditions'=>array('status'=>1,'trash'=>0,'lang'=>$lang),
			'fields'=>array('id','name','slug','meta_title','rel','target'),
			'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
			'limit'=>$this->limit,
			'order'=>array('sort'=>'asc','name'=>'asc'),
			'recursive'=>-1
		);
		$a_document_categories = $this->paginate('DocumentCategory');

		//Đọc cấu hình
		$a_configs = $this->_getConfig('document');
		$this->set('a_configs_c',$a_configs);
		
		
		//Breadcrumb
		$a_breadcrumb[] = array(
								'name'=>__('Tài liệu'),
								'meta_title'=>__('Tài liệu'),
								'url'=>'',
							);
		$this->set('a_breadcrumb_c',$a_breadcrumb);
		
		//SEO
		$this->set('title_for_layout',$a_configs['meta_title']);
		$this->set('meta_keyword_for_layout',$a_configs['meta_keyword']);
		$this->set('meta_description_for_layout',$a_configs['meta_description']);
		$this->set('meta_robots_for_layout',$a_configs['meta_robots']);
		
		//Canonical
		$a_canonical = array('controller'=>'documents','action' => 'index','lang'=>$lang);
        if(!empty($a_params['page']) && $a_params['page']>1) $a_canonical = array_merge($a_canonical,array('page'=>$a_params['page']));
		$this->set('a_canonical',$a_canonical);
	}
	
	
	/**
	 * @Description : Danh sách tài liệu theo danh mục
	 *
	 * @throws 	: NotFoundException
	 * @param 	: string $slug_cate
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function view($slug_cate=null) {
		$this->set('class','detail_document');
		
		if($slug_cate==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		$a_params = $this->params;
		$lang = $a_params['lang'];
		
		//Thông tin danh mục
		$a_category = $this->Document->DocumentCategory->find('first',array(
			'conditions'=>array('slug'=>$slug_cate,'status'=>1,'trash'=>0,'lang'=>$lang),
			'fields'=>array('id','name','slug','meta_title','meta_keyword','meta_description','meta_robots','description'),
			'recursive'=>-1
		));
		if(empty($a_category)) throw new NotFoundException(__('Trang này không tồn tại',true));
		$item_cate = $a_category['DocumentCategory'];
		
		//Danh sách tài liệu
		$this->paginate = array(
			'conditions'=>array('lang'=>$lang,'or'=>array(array('document_category_id'=>$item_cate['id']),array('category_other like'=>"%-{$item_cate['id']}-%")),'status'=>1,'trash'=>0),
			'fields'=>array('id','name','lang','file','link','description'),
			'page'=>(!empty($a_params['page'])?$a_params['page']:'1'),
			'limit'=>$this->limit_view,
			'order'=>array('sort'=>'asc','name'=>'asc'),
			'recursive'=>-1
		);
		$a_documents = $this->paginate('Document');

		$this->set('a_category_c',$a_category);
		$this->set('a_documents_c',$a_documents);
		
		//Breadcrumb
		$a_breadcrumb[] = array(
								'name'=>__('Tài liệu'),
								'meta_title'=>__('Tài liệu'),
								'url'=>array('controller'=>'documents','action'=>'index','lang'=>$lang),
							);
		
		$a_children = $this->Document->DocumentCategory->find('all',array(
			'conditions'=>array('status'=>1,'lang'=>$lang,'id !='=>$item_cate['id']),
			'fields'=>array('id','name','meta_title','slug'),
			'order'=>'sort asc',
			'recursive'=>-1
		));
		$children = array();
		foreach($a_children as $val){
			$item = $val['DocumentCategory'];
			$children[] = array(
								'name'=>$item['name'],
								'meta_title'=>$item['meta_title'],
								'url'=>array('controller'=>'documents','action'=>'view','lang'=>$lang,'slug_cate'=>$item['slug']),
							);
		}
		
		$a_breadcrumb[] = array(
								'name'=>$item_cate['name'],
								'meta_title'=>$item_cate['name'],
								'url'=>'',
								'child'=>$children
							);
		
		$this->set('a_breadcrumb_c',$a_breadcrumb);
		
		//SEO
		$this->set('title_for_layout',$item_cate['meta_title']);
		$this->set('meta_keyword_for_layout',$item_cate['meta_keyword']);
		$this->set('meta_description_for_layout',$item_cate['meta_description']);
		$this->set('meta_robots_for_layout',$item_cate['meta_robots']);
		
		//Canonical
		$a_canonical = array('controller'=>'documents','action' => 'view','lang'=>$lang,'slug_cate'=>$slug_cate);
        if(!empty($a_params['page']) && $a_params['page']>1) $a_canonical = array_merge($a_canonical,array('page'=>$a_params['page']));
		$this->set('a_canonical',$a_canonical);
	}
	
	
	/**
	 * @Description : Tăng số lượng download lên 1, và redirect đến link download
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function download($id=null){
		if($id==null) throw new NotFoundException(__('Trang này không tồn tại',true));
		
		//Đọc thông tin
		$this->Document->recursive = -1;
		$a_document = $this->Document->read('link,file,download',$id);
		$item = $a_document['Document'];
		
		//Tăng donwload lên 1
		$this->Document->id = $id;
		$this->Document->set(array('download'=>$item['download']+1));
		$this->Document->save();
		
		if(!empty($item['link'])) $this->redirect($item['link']);
		else $this->redirect('/img/files/documents/'.$item['file']);
	}


	
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/
	
	/**
	 * @Description : Danh sách tài liệu
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$lang = $this->Session->read('lang');
		$a_conditions = array('Document.lang'=>$lang,'Document.trash'=>0);
		
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->Document->id = $val;
						$this->Document->set(array('status'=>1));
						$this->Document->save();
					}
					$message = __('Tài liệu đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->Document->id = $val;
						$this->Document->set(array('status'=>0));
						$this->Document->save();
					}
					$message = __('Tài liệu đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trashItem($val);
					}
					$message = __('Tài liệu đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success')); 
		}
		
		if(!empty($_GET['category_id'])){	//Danh muc
			$this->request->data['Document']['category_id'] = $_GET['category_id'];
			$a_conditions = array_merge($a_conditions,array('or'=>array(array('document_category_id'=>$_GET['category_id']),array('category_other like'=>"%-{$_GET['category_id']}-%"))));
		}
		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tìm kiếm')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('Document.name like'=>'%'.$_GET['keyword'].'%'));
		}
		if(!empty($_GET['position'])){	//Vi tri hien thi
			$a_conditions = array_merge($a_conditions,array('pos_'.$_GET['position'].' !='=>0));
			$a_order = array('DocumentCategory.sort'=>'asc','pos_'.$_GET['position']=>'asc');
		}else{
			$a_order = array('DocumentCategory.sort'=>'asc','Document.sort'=>'asc');
		}
		
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array(
							'id','name','file','link','download','sort','pos_1','pos_2','status','lang','category_other','created',
							'DocumentCategory.id','DocumentCategory.name','DocumentCategory.trash','DocumentCategory.status'
						),
			'order'=>$a_order,
			'limit'=>$this->limit_admin,
			'recursive'=>1
		);
		
		$a_documents = $this->paginate();
		$this->set('a_documents_c', $a_documents);
		
		$counter = $this->Document->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);
		
		//Danh sach danh muc
		$a_list_categories = $this->Document->DocumentCategory->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>array('sort'=>'asc','name'=>'asc')));
		$this->set('a_list_categories_c',$a_list_categories);
		
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
			$this->Document->recursive = -1;
			$a_post = $this->Document->read('pos_1,pos_2',$_POST['id']);
			$a_post = array_filter($a_post['Document']);
			
			$return = array_merge($return,array('count'=>count($a_post)));
		}
		
		return json_encode($return);
	}
	
	/**
	 * @Description : Sắp xếp tài liệu
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['field'])|| empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		$this->Document->id = $_POST['id'];
		$this->Document->set(array($_POST['field']=>$_POST['val']));
		$this->Document->save();
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}
	
	
	/**
	 * @Description : Thêm tài liệu
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');
		$a_exts = array('pdf','doc','docx','xls','xlsx','ppt','pptx','txt','zip','rar');		//Dinh dang ho tro
		
		if ($this->request->is('post')) {
			$data = $this->request->data['Document'];
			
			//link
			if($data['link']=='http://') $data['link'] = '';
			$a_errors = array();
			//Upload file
			if($data['type']=='file'){
				$a_file = $data['file'];
				if(empty($data['file']['name'])) $a_errors['file'] = true;
				else{
					$ext = explode('.', $data['file']['name']);
					if(!in_array(strtolower($ext[count($ext)-1]), $a_exts)) $a_errors['ext'] = true;
				}
			}elseif(empty($data['link'])) $a_errors['link'] = true;
			$data['file'] = '';
			
			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			//Sắp xếp
			if(empty($data['sort'])) $data['sort'] = $this->Document->find('count',array('conditions'=>array('lang'=>$lang),'recursive'=>-1))+1;
			
			//ID của các danh mục khác
			if(!empty($data['category_other'])){
				$data['category_other'] = '-'.implode('-', array_filter($data['category_other'])).'-';
			}
			
			//Ngôn ngữ
			$data['lang'] = $lang;
			
			if(empty($a_errors)){
				$this->Document->create();
				if ($this->Document->save($data)) {
					$id = $this->Document->getLastInsertID();
					
					if(!empty($a_file['name'])){
						$destination = realpath(Configure::read('Media.path.document')).DS;
						
						$result = $this->Upload->upload($a_file, $destination, null, null);
						if($result){
							$file_name = $this->Upload->result;
							
							//Sua lai ten file
							$this->Document->id = $id;
							$this->Document->set('file',$file_name);
							$this->Document->save();
						}
						else{
							//Hien thi loi
							$errors=$this->Upload->errors;
							// piece together errors
							if(is_array($errors)){ $errors = implode("<br />",$errors); }
							$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
							$this->redirect(array('action'=>'add'));
							exit();
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
			}else{
				$this->set('a_errors_c',$a_errors);
			}
		}
		
		//Danh sach danh muc
		$a_list_categories = $this->Document->DocumentCategory->find('list',array('conditions'=>array('lang'=>$lang)));
		$this->set('a_list_categories_c',$a_list_categories);
		
		$this->set(compact('a_categories_c', 'a_makers_c', 'a_taxes_c'));
		$this->set('a_exts_c',$a_exts);
	}

	/**
	 * @Description : Sửa tài liệu
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->Document->id = $id;
		if (!$this->Document->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
		$a_exts = array('pdf','doc','docx','xls','xlsx','ppt','pptx','txt','zip','rar');		//Dinh dang ho tro
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$destination = realpath(Configure::read('Media.path.document')).DS;
			$data = $this->request->data['Document'];
			$a_errors = array();
			
			if($data['link']=='http://') $data['link'] = '';
			
			//Xóa file cũ nếu thỏa mãn đk: up file mới lên hoặc ban đầu là file trên hosting bây giờ thay bằng link ngoài hosting
			$this->Document->recursive = -1;
			$a_document = $this->Document->read('file,link',$id);
			$file_old = $a_document['Document']['file'];
			
			$flag_del_file_old = false;		//Xoa file cu tren server
			
			if($data['type']=='link'){
				if(!empty($file_old) && file_exists($destination.$file_old)) $flag_del_file_old = true;
				if(empty($data['link'])) $a_errors['link'] = true;
			}else{
				if(!empty($data['file']['name'])){		//Xoa file cu de up file moi
					if(!empty($file_old) && file_exists($destination.$file_old)) $flag_del_file_old = true;
					if(empty($data['file']['name'])) $a_errors['file'] = true;
					else{
						$ext = explode('.', $data['file']['name']);
						if(!in_array(strtolower($ext[count($ext)-1]), $a_exts)) $a_errors['ext'] = true;
					}
					
					$a_file = $data['file'];
				}
			}
			
			$data['file'] = ($flag_del_file_old)?'':$file_old;
			
			//ID của các danh mục khác
			if(!empty($data['category_other'])){
				$data['category_other'] = '-'.implode('-', array_filter($data['category_other'])).'-';
			}
			
			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			if(empty($a_errors)){
				if ($this->Document->save($data)) {
					
					//Xoa file cu
					if($flag_del_file_old && file_exists($destination.$file_old)) unlink($destination.$file_old);
					
					//Up file moi
					if(!empty($a_file['name'])){
						$result = $this->Upload->upload($a_file, $destination, null, null);
						if($result){
							$file_name = $this->Upload->result;
							
							//Sua lai ten file
							$this->Document->id = $id;
							$this->Document->set('file',$file_name);
							$this->Document->save();
						}
						else{
							//Hien thi loi
							$errors=$this->Upload->errors;
							// piece together errors
							if(is_array($errors)){ $errors = implode("<br />",$errors); }
							$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
							$this->redirect(array('action'=>'add'));
							exit();
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
			}else{
				$this->set('a_errors_c',$a_errors);
				$data_old = $this->Document->read('file', $id);
				$this->request->data['Document']['file'] = $data_old['Document']['file'];
			}
			
		} else {
			$this->request->data = $this->Document->read(null, $id);
			$this->request->data['Document']['category_other'] = array_filter(explode('-', $this->request->data['Document']['category_other']));
		}
		
		//Danh sach danh muc
		$a_list_categories = $this->Document->DocumentCategory->find('list',array('conditions'=>array('lang'=>$lang)));
		$this->set('a_list_categories_c',$a_list_categories);
		
		$this->set(compact('a_categories_c'));
		$this->set('a_exts_c',$a_exts);
	}
	
	
	/**
	 * @Description : Cho hình tài liệu vào thùng rác
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
		$this->Document->recursive = -1;
		$a_document = $this->Document->read('id,name',$id);
		$item_document = $a_document['Document'];
		
		//Ghi vào bảng Trash
		$data['name'] = $item_document['name'];
		$data['item_id'] = $item_document['id'];
		$data['model'] = 'Document';
		$data['description'] = 'Tài liệu';
		$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		$this->loadModel('Trash');
		$this->Trash->create();
		if($this->Trash->save($data)){
			$this->Document->id = $id;
			$this->Document->set(array('trash'=>1));
			if($this->Document->save()){
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		return false;
	}
}
