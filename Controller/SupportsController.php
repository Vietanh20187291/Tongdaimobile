<?php
App::uses('AppController', 'Controller');
/**
 * Supports Controller
 *
 * @property Support $Support
 */
class SupportsController extends AppController {
	public $limit_ad = 50;

	public function beforeFilter() {
		parent::beforeFilter();
		$admin = $this->Auth->user();
		if ($admin['role'] != 'admin') throw new NotFoundException(__('Trang này không tồn tại',true));
	}
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description : Danh sách hỗ trợ trực tuyến
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
						$this->Support->id = $val;
						$this->Support->set(array('status'=>1));
						$this->Support->save();
					}
					$message = __('Hỗ trợ trực tuyến đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->Support->id = $val;
						$this->Support->set(array('status'=>0));
						$this->Support->save();
					}
					$message = __('Hỗ trợ trực tuyến đã được bỏ kích hoạt');
					break;
				case 'del':
					foreach ($_POST['chkid'] as $val){
						$this->Support->delete($val);
					}
					$message = __('Hỗ trợ trực tuyến đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		//Danh sach hỗ trợ trực tuyến phan trang
		$this->Support->recursive = -1;
		$this->paginate = array(
			'order'=>array('sort'=>'asc','name'=>'asc'),
			'limit'=>$this->limit_ad
		);
		$a_supports = $this->paginate();
		$this->set('a_supports_c',$a_supports);

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
	 * @Description : Sắp xếp hỗ trợ trực tuyến
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$this->Support->id = $_POST['id'];
		$this->Support->set(array('sort'=>$_POST['val']));
		$this->Support->save();
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}


	/**
	 * @Description : Xóa hỗ trợ trực tuyến
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		if($this->Support->delete($_POST['id'])){
			$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
			return true;
		}
		else return false;
	}


	/**
	 * @Description : Thêm hỗ trợ trực tuyến
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');

		if ($this->request->is('post')) {
			$data = $this->request->data['Support'];
			//Sắp xếp
			if(empty($data['sort'])) $data['sort'] = $this->Support->find('count')+1;

			$this->Support->create();
			if ($this->Support->save($data)) {
				$id = $this->Support->getLastInsertID();
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
	 * @Description : Sửa hỗ trợ trực tuyến
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->Support->id = $id;
		if (!$this->Support->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['Support'];

			//Sắp xếp
			if(empty($data['sort'])){
				$this->Support->recursive = -1;
				$a_support = $this->Support->read('sort',$id);
				$data['sort'] = $a_support['Category']['id'];
			}

			if ($this->Support->save($data)) {
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
			$this->request->data = $this->Support->read(null, $id);
		}
	}
}
