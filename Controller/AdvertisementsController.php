<?php
App::uses('AppController', 'Controller');
/**
 * Advertisements Controller
 *@Author: Ngô Văn Nam
 * @property Advertisement $Advertisement
 */
class AdvertisementsController extends AppController {

	public $components = array();
	private  $limit_admin = 50;

	public function beforeFilter() {
		parent::beforeFilter();
		$admin = $this->Auth->user();
		if ($admin['role'] != 'admin') throw new NotFoundException(__('Trang này không tồn tại',true));
	}

	public function admin_index() {
		$lang = $this->Session->read('lang');
		$a_conditions = array('lang'=>$lang,'trash'=>0);

		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->Advertisement->id = $val;
						$this->Advertisement->set(array('status'=>1));
						$this->Advertisement->save();
					}
					$message = __('Đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->Advertisement->id = $val;
						$this->Advertisement->set(array('status'=>0));
						$this->Advertisement->save();
					}
					$message = __('Đã được bỏ kích hoạt');
					break;
				case 'del':
					foreach ($_POST['chkid'] as $val){
						$this->Advertisement->delete($val);
					}
					$message = __('Đã được xóa');
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
			$a_conditions = array_merge($a_conditions,array('Advertisement.name like'=>'%'.$_GET['keyword'].'%'));
		}
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array('id','name','position','content','status','created'),
			'order'=>$a_order,
			'limit'=>$this->limit_admin
		);

		$a_advertisements = $this->paginate();
		$this->set('a_advertisements_c', $a_advertisements);

		$counter = $this->Advertisement->find('count',array('conditions'=>$a_conditions));
		$this->set('counter_c',$counter);

		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}
	public function admin_ajaxChangeStatus(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$return = $this->_changeStatus($_POST['field'], $_POST['id']);
		if($_POST['field']){	//Đếm vị trí hiển thị trong trg hợp nó là vị trí hiển thị
			$this->Advertisement->recursive = -1;
			$a_advertisement = $this->Advertisement->read('status',$_POST['id']);
			$a_advertisement = array_filter($a_advertisement['Advertisement']);

			$return = array_merge($return,array('count'=>count($a_advertisement)));
		}

		return json_encode($return);
	}
	public function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['field']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$this->Advertisement->id = $_POST['id'];
		$this->Advertisement->set(array($_POST['field']=>$_POST['val']));
		$this->Advertisement->save();

		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}
	public function admin_add() {
		$lang = $this->Session->read('lang');

		if ($this->request->is('post')) {
			$data = $this->request->data['Advertisement'];

			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			//Ngôn ngữ
			$data['lang'] = $lang;

			$this->Advertisement->create();
			if ($this->Advertisement->save($data)) {
				$id = $this->Advertisement->getLastInsertID();
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
	public function admin_edit($id = null) {
		$this->Advertisement->id = $id;
		if (!$this->Advertisement->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['Advertisement'];

			if ($this->Advertisement->save($data)) {
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
		$this->request->data = $this->Advertisement->read(null, $id);
// 		$this->request->data['Advertisement']['content'] = trim(strip_tags(str_replace(array('<br />','<br>'), '', $this->request->data['Advertisement']['content'])));
	}
	public function admin_ajaxTrashItem(){
		$this->layout = false;
		$this->autoRender = false;

		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		return $this->trashItem($_POST['id']);
	}
	private function trashItem($id){
		//Thông tin hình ảnh
		$a_advertisement = $this->Advertisement->read('id,name',$id);
		$item_banner = $a_advertisement['Advertisement'];

		//Ghi vào bảng Trash
		$data['name'] = $item_banner['name'];
		$data['item_id'] = $item_banner['id'];
		$data['model'] = 'Advertisement';
		$data['description'] = 'Advertisement';
		$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

		$this->loadModel('Trash');
		$this->Trash->create();
		if($this->Trash->save($data)){
			$this->Advertisement->id = $id;
			$this->Advertisement->set(array('trash'=>1));
			if($this->Advertisement->save()) {
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		return false;
	}
}
