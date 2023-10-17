<?php
App::uses('AppController', 'Controller');
/**
 * OrderCategories Controller
 *
 * @property OrderCategory $OrderCategory
 */
class OrderCategoriesController extends AppController {
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
		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'active':
					foreach ($_POST['chkid'] as $val){
						$this->OrderCategory->id = $val;
						$this->OrderCategory->set(array('status'=>1));
						$this->OrderCategory->save();
					}
					$message = __('Danh mục đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->OrderCategory->id = $val;
						$this->OrderCategory->set(array('status'=>0));
						$this->OrderCategory->save();
					}
					$message = __('Danh mục đã được bỏ kích hoạt');
					break;
				case 'delete':
					foreach ($_POST['chkid'] as $val){
						$this->OrderCategory->delete($val);
					}
					$message = __('Danh mục đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success')); 
		}
		
		//Danh sach danh mục phan trang
		$this->OrderCategory->bindModel(array(
			'hasMany'=>array(
				'Order' => array(
								'className' => 'Order',
								'foreignKey' => 'order_category_id',
								'dependent' => false,
								'fields' => 'id',
							)
						)
		));
		$this->OrderCategory->recursive = 1;
		
		$this->paginate = array(
			'order'=>array('sort'=>'asc','name'=>'asc','created'=>'desc'),
			'limit'=>$this->limit_ad
		);
		$a_categories = $this->paginate();
		$this->set('a_categories_c',$a_categories);
		
		$counter = $this->OrderCategory->find('count',array('recursive'=>-1));
		$this->set('counter_c',$counter);
		
		//Danh sach danh muc - list
		$this->OrderCategory->bindModel(array(
			'hasMany'=>array(
				'Order' => array(
								'className' => 'Order',
								'foreignKey' => 'order_category_id',
								'dependent' => false,
								'fields' => 'id',
							)
						)
		));
		$a_list_categories = $this->OrderCategory->find('all',array('order'=>'sort asc'));
		$a_list_categories_s = array();		//Danh sách ra sidebar
		$total = 0;
		foreach($a_list_categories as $val){
			$item_order = $val['Order'];
			$item_cate = $val['OrderCategory'];
			$a_list_categories_s[$item_cate['id']] = $item_cate['name'].' ('.count($item_order).')';
			$total+=count($item_order);
		}
		$this->set('a_list_categories_s',$a_list_categories_s);
		$this->set('total_order_s',$total);
		
		
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
		if(empty($_POST['id']) || empty($_POST['field'])) throw new NotFoundException(__('Invalid'));
		
		$return = $this->_changeStatus($_POST['field'], $_POST['id']);
		
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
		
		$this->OrderCategory->id = $_POST['id'];
		$this->OrderCategory->set(array('sort'=>$_POST['val']));
		$this->OrderCategory->save();
	}
	
	
	/**
	 * @Description : Xóa danh mục
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDeleteItem() {
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		
		if($this->OrderCategory->delete($_POST['id'])) return true;
		else return false;
	}
	
	
	/**
	 * @Description : Thêm danh mục
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$data = $this->request->data['OrderCategory'];
			//Sắp xếp
			if(empty($data['sort'])) $data['sort'] = $this->OrderCategory->find('count',array('recursive'=>-1))+1;
			
			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			$this->OrderCategory->create();
			if ($this->OrderCategory->save($data)) {
				$id = $this->OrderCategory->getLastInsertID();
				$this->Session->setFlash('<span>'.__('Thêm mới thành công').'</span>','default',array('class'=>'success')); 
				
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
		$this->OrderCategory->id = $id;
		if (!$this->OrderCategory->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data['OrderCategory'];
			
			//Sắp xếp
			if(empty($data['sort'])){
				$this->OrderCategory->recursive = -1;
				$a_category = $this->OrderCategory->read('sort',$id);
				$data['sort'] = $a_category['Category']['id'];
			}
			
			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			if ($this->OrderCategory->save($data)) {
				$this->Session->setFlash('<span>'.__('Thông tin đã được cập nhật').'</span>','default',array('class'=>'success')); 
				
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
			$this->request->data = $this->OrderCategory->read(null, $id);
		}
	}
}
