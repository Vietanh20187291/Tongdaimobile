<?php
App::uses('AppController', 'Controller');
/**
 * VideoCategories Controller
 *
 * @property VideoCategory $VideoCategory
 */
class VideoCategoriesController extends AppController {
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
						$this->VideoCategory->id = $val;
						$this->VideoCategory->set(array('status'=>1));
						$this->VideoCategory->save();
					}
					$message = __('Danh mục đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->VideoCategory->id = $val;
						$this->VideoCategory->set(array('status'=>0));
						$this->VideoCategory->save();
					}
					$message = __('Danh mục đã được bỏ kích hoạt');
					break;
				case 'delete':
					foreach ($_POST['chkid'] as $val){
						$this->VideoCategory->delete($val);
					}
					$message = __('Danh mục đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success')); 
		}
		
		//Danh sach danh mục phan trang
		$a_conditions = array('lang'=>$lang,'trash'=>0);
		$this->VideoCategory->recursive = -1;
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array('id','name','lang','slug','sort','status','counter','created'),
			'order'=>array('sort'=>'asc','name'=>'asc'),
			'limit'=>$this->limit_ad
		);
		$a_categories = $this->paginate();
		$this->set('a_categories_c',$a_categories);
		
		$counter = $this->VideoCategory->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);
		
		//Danh sach danh muc
		$a_list_categories = $this->VideoCategory->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>'sort asc'));
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
		
		$this->VideoCategory->id = $_POST['id'];
		$this->VideoCategory->set(array('sort'=>$_POST['val']));
		$this->VideoCategory->save();
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
			$data = $this->request->data['VideoCategory'];
			
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
			$a_all_slugs = $this->VideoCategory->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			//Sắp xếp
			if(empty($data['sort'])) $data['sort'] = $this->VideoCategory->find('count',array('conditions'=>array('lang'=>$lang),'recursive'=>-1))+1;
			
			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			//Ngôn ngữ
			$data['lang'] = $lang;
			
			$this->VideoCategory->create();
			if ($this->VideoCategory->save($data)) {
				$id = $this->VideoCategory->getLastInsertID();
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
		$this->VideoCategory->id = $id;
		if (!$this->VideoCategory->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['VideoCategory'];
			
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
			$a_all_slugs = $this->VideoCategory->find('list',array('conditions'=>array('lang'=>$lang,'id !='=>$id),'fields'=>'slug','recursive'=>-1));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			//Sắp xếp
			if(empty($data['sort'])){
				$this->VideoCategory->recursive = -1;
				$a_category = $this->VideoCategory->read('sort',$id);
				$data['sort'] = $a_category['VideoCategory']['sort'];
			}
			
			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			if ($this->VideoCategory->save($data)) {
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
			$this->request->data = $this->VideoCategory->read(null, $id);
		}
	}
	
	
	/**
	 * @Description : Xóa danh mục và video của nó
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
	 * @Description : Xóa danh mục và video của nó
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function trashItem($id) {
		if(empty($id)) throw new NotFoundException(__('Invalid'));
		
		//Đọc thông tin hãng sx
		$this->VideoCategory->bindModel(array(
			'hasMany'=>array(
				'Video' => array(
				'className' => 'Video',
				'foreignKey' => 'video_category_id',
				'dependent' => false,
				'conditions' => array('trash'=>0),
				'fields' => array('id'),
			))
		));
		
		$a_category = $this->VideoCategory->find('first',array(
			'conditions'=>array('VideoCategory.id'=>$id,'VideoCategory.trash'=>0),
			'fields'=>array('id','name'),
			'recursive'=>1
		));
		
		//Đưa vào thùng rác
		if(!empty($a_category)){
			$a_child_id = array();
			
			foreach ($a_category['Video'] as $val) $a_child_id[] = $val['id'];
			
			$data['name'] = $a_category['VideoCategory']['name'];
			$data['item_id'] = $a_category['VideoCategory']['id'];
			$data['model'] = 'VideoCategory';
			$data['child_id'] = implode(',', $a_child_id);
			$data['child_model'] = 'Video';
			$description = 'Danh mục video';
			if(!empty($a_child_id)) $description.=' (Có '.(count($a_child_id)).' video)';
			
			$data['description'] = $description;
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			$this->loadModel('Trash');
			$this->Trash->create();
			if($this->Trash->save($data)){
				$this->VideoCategory->Video->updateAll(array('Video.trash'=>1),array('Video.id'=>$a_child_id));		
				
				$this->VideoCategory->id = $a_category['VideoCategory']['id'];
				$this->VideoCategory->set(array('trash'=>1));
				$this->VideoCategory->save();
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		
		return false;
	}
}
