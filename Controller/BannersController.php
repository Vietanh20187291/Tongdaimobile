<?php
App::uses('AppController', 'Controller');
/**
 * Banners Controller
 *
 * @property Banner $Banner
 */
class BannersController extends AppController {
	
	public $components = array('Upload');
	private  $limit_admin = 50;

	
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/
	
	/**
	 * @Description : Danh sách banner
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$lang = $this->Session->read('lang');
		$a_conditions = array('lang'=>$lang,'trash'=>0);
		
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->Banner->id = $val;
						$this->Banner->set(array('status'=>1));
						$this->Banner->save();
					}
					$message = __('Banner đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->Banner->id = $val;
						$this->Banner->set(array('status'=>0));
						$this->Banner->save();
					}
					$message = __('Banner đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trashItem($val);
					}
					$message = __('Banner đã được xóa');
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
			$a_conditions = array_merge($a_conditions,array('Banner.name like'=>'%'.$_GET['keyword'].'%'));
		}
		
		$this->Banner->unbindModel(array(
											'hasMany'=>array('BannerImage')
										));
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array('id','name','image','link','sort','page','status','lang','created','pos_1','pos_2','pos_3','pos_4','pos_5','pos_6','pos_7','pos_8','pos_9','pos_10','pos_11','pos_12'),
			'order'=>$a_order,
			'limit'=>$this->limit_admin
		);
		
		$a_banners = $this->paginate();
		$this->set('a_banners_c', $a_banners);
		
		$counter = $this->Banner->find('count',array('conditions'=>$a_conditions));
		$this->set('counter_c',$counter);
		
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
			$this->Banner->recursive = -1;
			$a_banner = $this->Banner->read('pos_1,pos_2,pos_3,pos_4,pos_5,pos_6,pos_7,pos_8,pos_9,pos_10,pos_11,pos_12',$_POST['id']);
			$a_banner = array_filter($a_banner['Banner']);
			
			$return = array_merge($return,array('count'=>count($a_banner)));
		}
		
		return json_encode($return);
	}
	
	/**
	 * @Description : Sắp xếp banner
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		$this->Banner->id = $_POST['id'];
		$this->Banner->set(array($_POST['field']=>$_POST['val']));
		$this->Banner->save();
		
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}
		
	
	/**
	 * @Description : Thêm banner
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post')) {
			$oneweb_banner = Configure::read('Banner');
			$data = $this->request->data['Banner'];
			
			//Ảnh đại diện
			if (!empty($data['image'])) $file = $data['image'];
			$data['image'] = '';
			
			$data['page'] = '-'.implode('-', $data['page']).'-';
			
			$data['description'] = nl2br($data['description']);
			
			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			//Ngôn ngữ
			$data['lang'] = $lang;
		
			$this->Banner->create();
			if ($this->Banner->save($data)) {
				$id = $this->Banner->getLastInsertID();
			
				$path = realpath($oneweb_banner['path']).DS;		//Đường dẫn file ảnh
				//Upload image
				if(!empty($file['name'])){
					//Up ảnh mới
					$result = $this->Upload->upload($file, $path, null, null);
					if($result){
						$image = $this->Upload->result;
						
						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->Banner->id = $id;
						$this->Banner->set('image',$image);
						$this->Banner->save();
					}else{
						//Hien thi loi
						$errors=$this->Upload->errors;
						// piece together errors
						if(is_array($errors)){ $errors = implode("<br />",$errors); }
						$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error')); 
						$this->redirect($this->referer()); 
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
	}

	/**
	 * @Description : Sửa banner
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->Banner->id = $id;
		if (!$this->Banner->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_banner = Configure::read('Banner');
			$data = $this->request->data['Banner'];
			
			//Đọc thông tin
			$a_banner = $this->Banner->read('image',$id);
			$a_banner = $a_banner['Banner'];
			
			//Ảnh đại diện
			if (!empty($data['image'])){
				$file = $data['image'];
			}
			$data['image'] = $a_banner['image'];
			
			$data['page'] = (empty($data['page']))?'-1-':'-'.implode('-', $data['page']).'-';
			
			$data['description'] = nl2br($data['description']);
			
			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			if ($this->Banner->save($data)) {
				
				$path = realpath($oneweb_banner['path']).DS;		//Đường dẫn file ảnh
				//Upload image
				if(!empty($file['name'])){
					//Xóa ảnh cũ
					if(!empty($a_banner['image']) && file_exists($path.$a_banner['image'])) unlink($path.$a_banner['image']);
					
					//Up ảnh mới
					$result = $this->Upload->upload($file, $path, null, null);
					if($result){
						$image = $this->Upload->result;
						
						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->Banner->id = $id;
						$this->Banner->set('image',$image);
						$this->Banner->save();
					}else{
						//Hien thi loi
						$errors=$this->Upload->errors;
						// piece together errors
						if(is_array($errors)){ $errors = implode("<br />",$errors); }
						$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error')); 
						$this->redirect($this->referer()); 
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
		}
		
		$this->request->data = $this->Banner->read(null, $id);
		$this->request->data['Banner']['page'] = array_filter(explode('-', $this->request->data['Banner']['page']));
		$this->request->data['Banner']['description'] = trim(strip_tags(str_replace(array('<br />','<br>'), '', $this->request->data['Banner']['description'])));
	}
	
	
	/**
	 * @Description : Cho banner vào thùng rác
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
	 * @Description : Đưa banner vào thùng rác
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int data
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function trashItem($id){
		//Thông tin hình ảnh
		$a_banner = $this->Banner->read('id,name',$id);
		$item_banner = $a_banner['Banner'];
		
		//Ghi vào bảng Trash
		$data['name'] = $item_banner['name'];
		$data['item_id'] = $item_banner['id'];
		$data['model'] = 'Banner';
		$data['description'] = 'Banner';
		$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		
		$this->loadModel('Trash');
		$this->Trash->create();
		if($this->Trash->save($data)){
			$this->Banner->id = $id;
			$this->Banner->set(array('trash'=>1));
			if($this->Banner->save()) {
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		return false;
	}
}
