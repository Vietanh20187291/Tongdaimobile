<?php
App::uses('AppController', 'Controller');
/**
 * ProductTaxes Controller
 *
 * @property ProductTax $ProductTax
 */
class ProductTaxesController extends AppController {
	
	public $limit_ad = 50;
	
	
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description : Danh sách thuế
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
						$this->ProductTax->id = $val;
						$this->ProductTax->set(array('status'=>1));
						$this->ProductTax->save();
					}
					$message = __('Thuế đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->ProductTax->id = $val;
						$this->ProductTax->set(array('status'=>0));
						$this->ProductTax->save();
					}
					$message = __('Thuế đã được bỏ kích hoạt');
					break;
				case 'delete':
					foreach ($_POST['chkid'] as $val){
						$this->ProductTax->delete($val);
					}
					$message = __('Thuế đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success')); 
		}
		
		//Danh sach thuế phan trang
		$this->ProductTax->recursive = -1;
		$this->paginate = array(
			'conditions'=>array('lang'=>$lang),
			'order'=>array('name'=>'asc'),
			'limit'=>$this->limit_ad
		);
		$a_taxes = $this->paginate();
		$this->set('a_taxes_c',$a_taxes);
		
		$counter = $this->ProductTax->find('count',array('conditions'=>array('lang'=>$lang),'recursive'=>-1));
		$this->set('counter_c',$counter);
		
		//Danh sach danh muc theo dang cay
		$a_product_categories_tree = $this->ProductTax->Product->ProductCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));
		$this->set('a_product_categories_tree_c',$a_product_categories_tree);
		
		//Danh sach hang sx
		$a_product_makers = $this->ProductTax->Product->ProductMaker->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>array('sort'=>'asc','name'=>'asc')));
		$this->set('a_product_makers_c',$a_product_makers);
		
		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}


	/**
	 * @Description : Thêm thuế
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post')) {
			$data = $this->request->data['ProductTax'];
			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			//Ngôn ngữ
			$data['lang'] = $lang;
			
			$this->ProductTax->create();
			if ($this->ProductTax->save($data)) {
				$id = $this->ProductTax->getLastInsertID();
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
	 * @Description : Sửa thuế
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->ProductTax->id = $id;
		if (!$this->ProductTax->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['ProductTax'];
			//Ngay sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			if ($this->ProductTax->save($data)) {
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
			$this->request->data = $this->ProductTax->read(null, $id);
		}
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
	 * @Description : Xóa thuế
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		if($this->ProductTax->delete($_POST['id'])){
			$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
			return true;
		}
		else return false;
	}

}
