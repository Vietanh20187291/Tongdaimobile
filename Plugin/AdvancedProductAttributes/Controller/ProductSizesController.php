<?php
App::uses('AppController', 'Controller');

Class ProductSizesController extends AdvancedProductAttributesAppController {
	public $limit_admin = 20;

	public function admin_index() {
		$a_conditions = array();

		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->ProductSize->id = $val;
						$this->ProductSize->set(array('status'=>1));
						$this->ProductSize->save();
					}
					$message = __('Kích cỡ đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->ProductSize->id = $val;
						$this->ProductSize->set(array('status'=>0));
						$this->ProductSize->save();
					}
					$message = __('Kích cỡ đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->ProductSize->delete($val);
					}
					$message = __('Kích cỡ đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tìm kiếm')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('ProductSize.size like'=>'%'.$_GET['keyword'].'%'));
		}


		$this->paginate = array(
			'conditions'=>$a_conditions,
			'limit'=>$this->limit_admin
		);

		$product_sizes = $this->paginate();
		$this->set('product_sizes', $product_sizes);

		$counter = $this->ProductSize->find('count',array('conditions'=>$a_conditions));
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
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$return = $this->changeStatus($_POST['id'],null,true);
		return json_encode($return);
	}

	private function changeStatus($id=null,$status=null,$return=false){
		if($id==null) throw new NotFoundException(__('Invalid'));

		//Kiểm tra trạng thái hiện tại
		$this->ProductSize->recursive = -1;
		$a_information = $this->ProductSize->read('status',$id);
		$a_information = $a_information['ProductSize'];

		if($status==null) $status = ($a_information['status'])?0:1;
		$a_ids = array($id);		//Mang id cần set trạng thái

		for($i=0;$i<count($a_ids);$i++){
			$this->ProductSize->id = $a_ids[$i];
			$this->ProductSize->set(array('status'=>$status));
			$this->ProductSize->save();
		}

		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache

		if($return){
			$tmp['cl'] = ($status)?'active':'unactive';
			$tmp['id'] = $a_ids;
			return $tmp;
		}
	}


	/**
	 * @Description : Thêm information
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');

		if ($this->request->is('post')) {
			$data = $this->request->data['ProductSize'];

			$this->ProductSize->create();
			if ($this->ProductSize->save($data)) {
				$id = $this->ProductSize->getLastInsertID();

				$this->Session->setFlash('<span>'.__('Thêm mới thành công').'</span>','default',array('class'=>'success'));
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				if (!empty($_POST['save'])){
					$this->redirect(array('plugin'=>'AdvancedProductAttributes','action'=>'edit',$id));
				}elseif (!empty($_POST['save_add'])){
					$this->redirect(array('plugin'=>'AdvancedProductAttributes','action'=>'add'));
				}else $this->redirect(array('plugin'=>'AdvancedProductAttributes','action'=>'index'));
			} else {
				$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
			}
		}
	}

	/**
	 * @Description : Sửa information
	 *
	 * @throws NotFoundException
	 * @param int $id
	 * @return void
	 * @Author Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->ProductSize->id = $id;
		if (!$this->ProductSize->exists()) throw new NotFoundException(__('Invalid'));

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['ProductSize'];

			if ($this->ProductSize->save($data)) {

				$this->Session->setFlash('<span>'.__('Thông tin đã được cập nhật').'</span>','default',array('class'=>'success'));
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				if (!empty($_POST['save'])){
					$this->redirect($this->referer());
				}elseif (!empty($_POST['save_add'])){
					$this->redirect(array('plugin'=>'AdvancedProductAttributes','action'=>'add'));
				}else{
					$url = (!empty($_GET['url']))?urldecode($_GET['url']):array('plugin'=>'AdvancedProductAttributes','action'=>'index');
					$this->redirect($url);
				}
			} else {
				$this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));
			}
		} else {
			$this->request->data = $this->ProductSize->read(null, $id);
		}
	}

	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		if($this->ProductSize->delete($_POST['id'])){
			$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
			return true;
		}
		else return false;
	}
}