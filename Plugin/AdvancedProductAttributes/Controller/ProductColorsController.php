<?php
App::uses('AppController', 'Controller');

Class ProductColorsController extends AdvancedProductAttributesAppController {
	public $limit_admin = 20;

	public function admin_index() {
		$a_conditions = array();

		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->ProductColor->id = $val;
						$this->ProductColor->set(array('status'=>1));
						$this->ProductColor->save();
					}
					$message = __('Màu sắc đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->ProductColor->id = $val;
						$this->ProductColor->set(array('status'=>0));
						$this->ProductColor->save();
					}
					$message = __('Màu sắc đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->ProductColor->delete($val);
					}
					$message = __('Màu sắc đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		if(!empty($_GET['keyword']) && $_GET['keyword']!=__('Tìm kiếm')){	//Tu khoa
			$a_conditions = array_merge($a_conditions,array('ProductColor.color like'=>'%'.$_GET['keyword'].'%'));
		}


		$this->paginate = array(
			'conditions'=>$a_conditions,
			'limit'=>$this->limit_admin
		);

		$product_colors = $this->paginate();
		$this->set('product_colors', $product_colors);

		$counter = $this->ProductColor->find('count',array('conditions'=>$a_conditions));
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
	public function admin_ajaxChangeColor(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$return = $this->ProductColor->find('first', array(
			'conditions'=>array('ProductColor.id'=>$_POST['id'], 'status'=>true),
			'recursive'=>-1
		));

		// $this->loadModel('ProductAttribute');
		// $product_attribute = $this->ProductAttribute->find('all', array(
		// 	'conditions'=>array('product_id'=>$_POST['product_id'], 'product_color_id'=>$_POST['ProductColorId'], 'product_size_id'=>$_POST['ProductSizeId'])
		// ));
		// debug($product_attribute);die;
		// $return = $return['ProductColor'];
		// $return['product_size'] =
		// debug($return);die;
		return json_encode($return['ProductColor']);
	}

	private function changeStatus($id=null,$status=null,$return=false){
		if($id==null) throw new NotFoundException(__('Invalid'));

		//Kiểm tra trạng thái hiện tại
		$this->ProductColor->recursive = -1;
		$a_information = $this->ProductColor->read('status',$id);
		$a_information = $a_information['ProductColor'];

		if($status==null) $status = ($a_information['status'])?0:1;
		$a_ids = array($id);		//Mang id cần set trạng thái

		for($i=0;$i<count($a_ids);$i++){
			$this->ProductColor->id = $a_ids[$i];
			$this->ProductColor->set(array('status'=>$status));
			$this->ProductColor->save();
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
			$data = $this->request->data['ProductColor'];
			//Lấy danh sách slug đã tồn tại
			$a_all_slugs = $this->ProductColor->find('list',array('fields'=>'slug'));

			$data['slug'] = $this->Oneweb->slug($data['color'],$a_all_slugs);

			$this->ProductColor->create();
			if ($this->ProductColor->save($data)) {
				$id = $this->ProductColor->getLastInsertID();

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
		$this->ProductColor->id = $id;
		if (!$this->ProductColor->exists()) throw new NotFoundException(__('Invalid'));

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['ProductColor'];
			//Lấy danh sách slug đã tồn tại
			$a_all_slugs = $this->ProductColor->find('list',array('fields'=>'slug'));

			$data['slug'] = $this->Oneweb->slug($data['color'],$a_all_slugs);

			if ($this->ProductColor->save($data)) {

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
			$this->request->data = $this->ProductColor->read(null, $id);
		}
	}

	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		if($this->ProductColor->delete($_POST['id'])){
			$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
			return true;
		}
		else return false;
	}
}