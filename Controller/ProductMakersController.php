<?php
App::uses('AppController', 'Controller');
/**
 * ProductMakers Controller
 *
 * @property ProductMaker $ProductMaker
 */
class ProductMakersController extends AppController {
	public $components = array('Upload');
	public $limit_ad = 50;


	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description : Danh sách hãng sản xuất
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
						$this->ProductMaker->id = $val;
						$this->ProductMaker->set(array('status'=>1));
						$this->ProductMaker->save();
					}
					$message = __('Hãng sản xuất đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->ProductMaker->id = $val;
						$this->ProductMaker->set(array('status'=>0));
						$this->ProductMaker->save();
					}
					$message = __('Hãng sản xuất đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trashItem($val);
					}
					$message = __('Hãng sản xuất đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		//Danh sach hãng sx phan trang
		$a_conditions = array('lang'=>$lang,'trash'=>0);
		$this->ProductMaker->recursive = -1;
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array('id','name','lang','slug','link','counter','status','sort'),
			'order'=>array('sort'=>'asc','name'=>'asc'),
			'limit'=>$this->limit_ad
		);
		$a_makers = $this->paginate();
		$this->set('a_makers_c',$a_makers);

		$counter = $this->ProductMaker->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);

		//Danh sach danh muc theo dang cay
		$a_product_categories_tree = $this->ProductMaker->Product->ProductCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));
		$this->set('a_product_categories_tree_c',$a_product_categories_tree);

		//Danh sach hang sx
		$a_product_makers = $this->ProductMaker->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>array('sort'=>'asc','name'=>'asc')));
		$this->set('a_product_makers_c',$a_product_makers);

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
	 * @Description : Sắp xếp hãng sx
	 *
	 * @throws 	: NotFoundException
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function admin_ajaxChangeSort(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['val']) || empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$this->ProductMaker->id = $_POST['id'];
		$this->ProductMaker->set(array('sort'=>$_POST['val']));
		$this->ProductMaker->save();
		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	}


	/**
	 * @Description : Thêm hãng sản xuất
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');

		if ($this->request->is('post')) {
			$oneweb_product = Configure::read('Product');
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['ProductMaker'];

			//Ảnh đại diện
			$file = $data['image'];
			$data['image'] = '';

			//Banner
			if(!empty($oneweb_product['maker_banner'])) {
				$banner = $data['banner'];
				$data['banner'] = '';
			}
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
			$a_all_slugs = $this->ProductMaker->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));

			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);

			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			//Ngôn ngữ
			$data['lang'] = $lang;

			$this->ProductMaker->create();
			if ($this->ProductMaker->save($data)) {
				$id = $this->ProductMaker->getLastInsertID();

				$path = realpath($oneweb_product['path']['maker']).DS;		//Đường dẫn file ảnh

				//Upload image
				if(!empty($file['name'])){
					$result = $this->Upload->upload($file, $path, null, null, null);
					if($result){
						$image = $this->Upload->result;

						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->ProductMaker->id = $id;
						$this->ProductMaker->set('image',$image);
						$this->ProductMaker->save();
					}else{
						//Hien thi loi
						$errors=$this->Upload->errors;
						// piece together errors
						if(is_array($errors)){ $errors = implode("<br />",$errors); }
						$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
						$this->redirect(array('action'=>'edit',$id));
					}
				}

				//Upload banner
				if(!empty($oneweb_product['maker_banner'])) {
					if(!empty($banner['name'])){
						$result = $this->Upload->upload($banner, $path, null, array('type' => 'resizemax', 'size' => $oneweb_product['size']['maker_banner'], 'output' => 'jpg'));
						if($result){
							$image = $this->Upload->result;

							//Luu ten anh vao ban ghi vua duoc them vao bang products
							$this->ProductMaker->id = $id;
							$this->ProductMaker->set('banner',$image);
							$this->ProductMaker->save();
						}else{
							//Hien thi loi
							$errors=$this->Upload->errors;
							// piece together errors
							if(is_array($errors)){ $errors = implode("<br />",$errors); }
							$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
							$this->redirect(array('action'=>'edit',$id));
						}
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
	 * @Description : Sửa hãng sx
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_edit($id = null) {
		$this->ProductMaker->id = $id;
		if (!$this->ProductMaker->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');

		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_product = Configure::read('Product');
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['ProductMaker'];

			$this->ProductMaker->recursive = -1;
			$a_maker = $this->ProductMaker->read('image,banner',$id);
			$a_maker = $a_maker['ProductMaker'];

			//Ảnh đại diện
			if(!empty($data['image']['name'])){		//Up ảnh khác
				$file = $data['image'];
			}
			$data['image'] = $a_maker['image'];

			//Banner
			if(!empty($data['banner']['name'])){		//Up ảnh khác
				$banner = $data['banner'];
			}
			$data['banner'] = $a_maker['banner'];

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
			$a_all_slugs = $this->ProductMaker->find('list',array('conditions'=>array('lang'=>$lang,'id !='=>$id),'fields'=>'slug','recursive'=>-1));

			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);

			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			if ($this->ProductMaker->save($data)) {
				$path = realpath($oneweb_product['path']['maker']).DS;		//Đường dẫn file ảnh

				//Upload image
				if(!empty($file['name'])){
					//Xóa ảnh cũ
					if(!empty($a_maker['image']) && file_exists($path.$a_maker['image'])) unlink($path.$a_maker['image']);

					//Up ảnh mới
					$result = $this->Upload->upload($file, $path, null, null, null);
					if($result){
						$image = $this->Upload->result;

						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->ProductMaker->id = $id;
						$this->ProductMaker->set('image',$image);
						$this->ProductMaker->save();
					}else{
						//Hien thi loi
						$errors=$this->Upload->errors;
						// piece together errors
						if(is_array($errors)){ $errors = implode("<br />",$errors); }
						$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
						$this->redirect($this->referer());
					}
				}

				//Upload banner
				if(!empty($banner['name'])){
					//Xóa banner cũ
					if(!empty($a_maker['banner']) && file_exists($path.$a_maker['banner'])) unlink($path.$a_maker['banner']);

					//Up banner mới
					$result = $this->Upload->upload($banner, $path, null, array('type' => 'resizemax', 'size' => $oneweb_product['size']['maker_banner'], 'output' => 'jpg'));
					if($result){
						$image = $this->Upload->result;

						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->ProductMaker->id = $id;
						$this->ProductMaker->set('banner',$image);
						$this->ProductMaker->save();
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
		} else {
			$this->request->data = $this->ProductMaker->read(null, $id);
		}
	}


	/**
	 * @Description : Xóa ảnh (banner hoặc ảnh đại diện)
	 *
	 * @throws 	: NotFoundException
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDelImage(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id']) || empty($_POST['field'])) throw new NotFoundexception(__('Invalid'));

		$this->ProductMaker->recursive = -1;
		$a_maker = $this->ProductMaker->read("{$_POST['field']} as img",$_POST['id']);
		$a_maker = $a_maker['ProductMaker'];
		if(!empty($a_maker)){
			$oneweb_product = Configure::read('Product');
			$path = realpath($oneweb_product['path']['maker']).DS;		//Đường dẫn file ảnh

			$this->ProductMaker->id = $_POST['id'];
			$this->ProductMaker->set(array($_POST['field']=>''));
			if($this->ProductMaker->save()){
				if(!empty($a_maker['img']) && file_exists($path.$a_maker['img'])) unlink($path.$a_maker['img']);
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		return false;
	}


	/**
	 * @Description : Xóa hãng sản xuất và các sp con của nó vào thùng rác
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
	 * @Description : Xóa hãng sx và các sp của nó
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function trashItem($id) {
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		//Đọc thông tin hãng sx
		$this->ProductMaker->bindModel(array(
			'hasMany'=>array(
				'Product' => array(
				'className' => 'Product',
				'foreignKey' => 'product_maker_id',
				'dependent' => false,
				'conditions' => array('trash'=>0),
				'fields' => array('id'),
			))
		));

		$a_maker = $this->ProductMaker->find('first',array(
			'conditions'=>array('ProductMaker.id'=>$id,'ProductMaker.trash'=>0),
			'fields'=>array('id','name'),
			'recursive'=>1
		));

		//Đưa vào thùng rác
		if(!empty($a_maker)){
			$a_child_id = array();

			foreach ($a_maker['Product'] as $val) $a_child_id[] = $val['id'];

			$data['name'] = $a_maker['ProductMaker']['name'];
			$data['item_id'] = $a_maker['ProductMaker']['id'];
			$data['model'] = 'ProductMaker';
			$data['child_id'] = implode(',', $a_child_id);
			$data['child_model'] = 'Product';
			$description = 'Hãng sản xuất';
			if(!empty($a_child_id)) $description.=' (Có '.(count($a_child_id)).' sản phẩm)';

			$data['description'] = $description;
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			$this->loadModel('Trash');
			$this->Trash->create();
			if($this->Trash->save($data)){
				$this->ProductMaker->Product->updateAll(array('Product.trash'=>1),array('Product.id'=>$a_child_id));

				$this->ProductMaker->id = $a_maker['ProductMaker']['id'];
				$this->ProductMaker->set(array('trash'=>1));
				$this->ProductMaker->save();
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}

		return false;
	}
}
