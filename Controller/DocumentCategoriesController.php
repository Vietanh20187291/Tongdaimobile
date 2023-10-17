<?php
App::uses('AppController', 'Controller');
/**
 * DocumentCategories Controller
 *
 * @property DocumentCategory $DocumentCategory
 */
class DocumentCategoriesController extends AppController {
	public $limit_ad = 50;
	

	
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/
	
	/**
	 * @Description : Danh sách danh mục
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$lang = $this->Session->read('lang');
		
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->DocumentCategory->id = $val;
						$this->DocumentCategory->set(array('status'=>1));
						$this->DocumentCategory->save();
					}
					$message = __('Danh mục đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->DocumentCategory->id = $val;
						$this->DocumentCategory->set(array('status'=>0));
						$this->DocumentCategory->save();
					}
					$message = __('Danh mục đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trashItem($val);
					}
					$message = __('Danh mục đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success')); 
		}
		
		//Danh sach danh mục phan trang
		$a_conditions = array('lang'=>$lang,'trash'=>0);
		$this->DocumentCategory->recursive = -1;
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array('id','name','lang','slug','sort','status','counter','created'),
			'order'=>array('sort'=>'asc','name'=>'asc'),
			'limit'=>$this->limit_ad
		);
		$a_categories = $this->paginate();
		$this->set('a_categories_c',$a_categories);
		
		$counter = $this->DocumentCategory->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);
		
		//Danh sach danh muc
		$a_list_categories = $this->DocumentCategory->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>'sort asc'));
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
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		$return = $this->_changeStatus('status', $_POST['id']);
		
		return json_encode($return);
	}
	
	
	/**
	 * @Description : Sắp xếp danh mục
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		$this->DocumentCategory->id = $_POST['id'];
		$this->DocumentCategory->set(array('sort'=>$_POST['val']));
		$this->DocumentCategory->save();
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}
	
	
	/**
	 * @Description : Thêm danh mục
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');
		$oneweb_seo = Configure::read('Seo');
		
		if ($this->request->is('post')) {
			$data = $this->request->data['DocumentCategory'];
			
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
			$a_all_slugs = $this->DocumentCategory->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			//Sắp xếp
			if(empty($data['sort'])) $data['sort'] = $this->DocumentCategory->find('count',array('conditions'=>array('lang'=>$lang),'recursive'=>-1))+1;
			
			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			//Ngôn ngữ
			$data['lang'] = $lang;
			
			$this->DocumentCategory->create();
			if ($this->DocumentCategory->save($data)) {
				$id = $this->DocumentCategory->getLastInsertID();
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
	}

	/**
	 * @Description : Sửa danh mục
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->DocumentCategory->id = $id;
		if (!$this->DocumentCategory->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['DocumentCategory'];
			
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
			$a_all_slugs = $this->DocumentCategory->find('list',array('conditions'=>array('lang'=>$lang,'id !='=>$id),'fields'=>'slug','recursive'=>-1));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			//Sắp xếp
			if(empty($data['sort'])){
				$this->DocumentCategory->recursive = -1;
				$a_category = $this->DocumentCategory->read('sort',$id);
				$data['sort'] = $a_category['DocumentCategory']['sort'];
			}
			
			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			if ($this->DocumentCategory->save($data)) {
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
			$this->request->data = $this->DocumentCategory->read(null, $id);
		}
	}
	
	
	/**
	 * @Description : Xóa danh mục và tài liệu
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxTrashItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		return $this->trashItem($_POST['id']);
	}
	
	
	/**
	 * @Description : Xóa danh mục và tài liệu của nó
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function trashItem($id) {
		if(empty($id)) throw new NotFoundException(__('Invalid'));
		
		//Đọc thông tin hãng sx
		$this->DocumentCategory->bindModel(array(
			'hasMany'=>array(
				'Document' => array(
				'className' => 'Document',
				'foreignKey' => 'document_category_id',
				'dependent' => false,
				'conditions' => array('trash'=>0),
				'fields' => array('id'),
			))
		));
		
		$a_category = $this->DocumentCategory->find('first',array(
			'conditions'=>array('DocumentCategory.id'=>$id,'DocumentCategory.trash'=>0),
			'fields'=>array('id','name'),
			'recursive'=>1
		));
		
		//Đưa vào thùng rác
		if(!empty($a_category)){
			$a_child_id = array();
			
			foreach ($a_category['Document'] as $val) $a_child_id[] = $val['id'];
			
			$data['name'] = $a_category['DocumentCategory']['name'];
			$data['item_id'] = $a_category['DocumentCategory']['id'];
			$data['model'] = 'DocumentCategory';
			$data['child_id'] = implode(',', $a_child_id);
			$data['child_model'] = 'Document';
			$description = 'Danh mục tài liêu';
			if(!empty($a_child_id)) $description.=' (Có '.(count($a_child_id)).' tài liệu)';
			
			$data['description'] = $description;
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			$this->loadModel('Trash');
			$this->Trash->create();
			if($this->Trash->save($data)){
				$this->DocumentCategory->Document->updateAll(array('Document.trash'=>1),array('Document.id'=>$a_child_id));		
				
				$this->DocumentCategory->id = $a_category['DocumentCategory']['id'];
				$this->DocumentCategory->set(array('trash'=>1));
				$this->DocumentCategory->save();
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		
		return false;
	}
}
