<?php
App::uses('AppController', 'Controller');
/**
 * ProductCategories Controller
 *
 * @property ProductCategory $ProductCategory
 */
class ProductCategoriesController extends AppController {
	public $components = array('Upload');
	public $uses = array('ProductCategory','ProductMaker');
	private $limit_ad = 50;


	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/

	/**
	 * @Description : Danh sách danh mục sản phẩm
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
						$this->_changeStatusCategory($val,1);
					}
					$message = __('Danh mục đã được kích hoạt');
					break;
				case 'unactive':
					foreach ($_POST['chkid'] as $val){
						$this->_changeStatusCategory($val,0);
					}
					$message = __('Danh mục đã được bỏ kích hoạt');
					break;
				case 'trashes':
					foreach ($_POST['chkid'] as $val){
						$this->trash($val);
					}
					$message = __('Danh mục đã được xóa');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		$a_conditions = array('ProductCategory.lang'=>$lang,'ProductCategory.trash'=>0);

		//Lọc danh mục theo danh mục cha
		if(!empty($_GET['parent_id'])){
			$a_ids = array($_GET['parent_id']);
			$a_children = $this->ProductCategory->children($_GET['parent_id'],false,array('id'));
			if(!empty($a_children))
				foreach ($a_children as $val)
					$a_ids[] = $val['ProductCategory']['id'];

			$a_conditions = array_merge($a_conditions,array('ProductCategory.id'=>$a_ids));
		}

		//Danh sach danh muc phan trang
		$this->ProductCategory->recursive = 0;
		$this->ProductCategory->bindModel(array(
			'belongsTo'=>array('ParentProductCategory' => array(
			'className' => 'ProductCategory',
			'foreignKey' => 'parent_id',
			'conditions' => array('ParentProductCategory.trash'=>0),
			'fields' => '',
			'order' => ''
		))
		));
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array('id','lang','name','path','status','link','image','parent_id','counter','ParentProductCategory.id'),
			'order'=>array('ProductCategory.lft'=>'asc','ProductCategory.rght'=>'asc'),
			'limit'=>$this->limit_ad
		);
		$a_product_categories = $this->paginate();
		$this->set('a_product_categories_c',$a_product_categories);

		$counter = $this->ProductCategory->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);

		//Danh sach danh muc theo dang cay
		$a_product_categories_tree = $this->ProductCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));
		$this->set('a_product_categories_tree_c',$a_product_categories_tree);

		//Danh sach hang sx
		$a_product_makers = $this->ProductMaker->find('list',array('conditions'=>array('lang'=>$lang,'trash'=>0),'order'=>array('sort'=>'asc','name'=>'asc')));
		$this->set('a_product_makers_c',$a_product_makers);

		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}


	/**
	 * @Description :	Xem danh mục con và các sản phẩm con
	 *
	 * @params 	:
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_view($id=null){
		if($id==null) throw new NotFoundException(__('Invalid',true));
	}


	/**
	 * @Description : Thêm danh mục
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_add() {
		$lang = $this->Session->read('lang');
		if ($this->request->is('post')) {
			$oneweb_product = Configure::read('Product');
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['ProductCategory'];

			//Ảnh đại diện
			$file = $data['image'];
			$data['image'] = '';

			//Banner
			$banner = $data['banner'];
			$data['banner'] = '';
			//Icon
			$icon = $data['icon'];
			$data['icon'] = '';

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
			$a_all_slugs = $this->ProductCategory->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));

			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);

			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			//Ngôn ngữ
			$data['lang'] = $lang;

			$this->ProductCategory->create();
			if ($this->ProductCategory->save($data)) {
				$id = $this->ProductCategory->getLastInsertID();

				//Cập nhật và chỉnh sửa lại đường dẫn của các danh mục con và chính nó
				$this->_updatePath($id);

				$path = realpath($oneweb_product['path']['category']).DS;		//Đường dẫn file ảnh

				//Upload image
				if(!empty($file['name'])){
					$result = $this->Upload->upload($file, $path, null, array('type' => 'resizemax', 'size' => $oneweb_product['size']['category'], 'output' => 'jpg'));
					if($result){
						$image = $this->Upload->result;

						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->ProductCategory->id = $id;
						$this->ProductCategory->set('image',$image);
						$this->ProductCategory->save();
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
				if(!empty($banner['name'])){
					$result = $this->Upload->upload($banner, $path, null, array('type' => 'resizemax', 'size' => $oneweb_product['size']['category_banner'], 'output' => 'jpg'));
					if($result){
						$image = $this->Upload->result;

						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->ProductCategory->id = $id;
						$this->ProductCategory->set('banner',$image);
						$this->ProductCategory->save();
					}else{
						//Hien thi loi
						$errors=$this->Upload->errors;
						// piece together errors
						if(is_array($errors)){ $errors = implode("<br />",$errors); }
						$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
						$this->redirect(array('action'=>'edit',$id));
					}
				}

				//Upload icon
				if(!empty($icon['name'])){
					$result_icon = $this->Upload->upload($icon, $path, null, array('type' => 'resizemax', 'size' => $oneweb_product['size']['icon'], 'output' => 'jpg'));
					if($result_icon){
						$icon = $this->Upload->result;

						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->ProductCategory->id = $id;
						$this->ProductCategory->set('icon',$icon);
						$this->ProductCategory->save();
					}else{
						//Hien thi loi
						$errors=$this->Upload->errors;
						// piece together errors
						if(is_array($errors)){ $errors = implode("<br />",$errors); }
						$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
						$this->redirect(array('action'=>'edit',$id));
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

		//Danh sach danh muc
		$a_categories_c = $this->ProductCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));
		$this->set(compact('a_categories_c'));
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
		$this->ProductCategory->id = $id;
		if (!$this->ProductCategory->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');

		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_product = Configure::read('Product');
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['ProductCategory'];

			$this->ProductCategory->recursive = -1;
			$a_category = $this->ProductCategory->read('image,banner,icon',$id);
			$a_category = $a_category['ProductCategory'];

			//Ảnh đại diện
			if(!empty($data['image']['name'])){		//Up ảnh khác
				$file = $data['image'];
			}
			$data['image'] = $a_category['image'];

			//Banner
			if(!empty($data['banner']['name'])){		//Up ảnh khác
				$banner = $data['banner'];
			}
			$data['banner'] = $a_category['banner'];
			//icon
			if(!empty($data['icon']['name'])){		//Up ảnh khác
				$icon = $data['icon'];
			}
			$data['icon'] = $a_category['icon'];

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
			$a_all_slugs = $this->ProductCategory->find('list',array('conditions'=>array('lang'=>$lang,'id !='=>$id),'fields'=>'slug','recursive'=>-1));

			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);

			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			if ($this->ProductCategory->save($data)) {

				//Cập nhật và chỉnh sửa lại đường dẫn của các danh mục con và chính nó
				$this->_updatePath($id);

				$path = realpath($oneweb_product['path']['category']).DS;		//Đường dẫn file ảnh

				//Upload image
				if(!empty($file['name'])){
					//Xóa ảnh cũ
					if(!empty($a_category['image']) && file_exists($path.$a_category['image'])) unlink($path.$a_category['image']);

					//Up ảnh mới
					$result = $this->Upload->upload($file, $path, null, array('type' => 'resizemax', 'size' => $oneweb_product['size']['category'], 'output' => 'jpg'));
					if($result){
						$image = $this->Upload->result;

						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->ProductCategory->id = $id;
						$this->ProductCategory->set('image',$image);
						$this->ProductCategory->save();
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
					if(!empty($a_category['banner']) && file_exists($path.$a_category['banner'])) unlink($path.$a_category['banner']);

					//Up banner mới
					$result = $this->Upload->upload($banner, $path, null, array('type' => 'resizemax', 'size' => $oneweb_product['size']['category_banner'], 'output' => 'jpg'));
					if($result){
						$image = $this->Upload->result;

						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->ProductCategory->id = $id;
						$this->ProductCategory->set('banner',$image);
						$this->ProductCategory->save();
					}else{
						//Hien thi loi
						$errors=$this->Upload->errors;
						// piece together errors
						if(is_array($errors)){ $errors = implode("<br />",$errors); }
						$this->Session->setFlash('<span>'.$errors.'</span>','default',array('class'=>'error'));
						$this->redirect($this->referer());
					}
				}
				//Upload icon
				if(!empty($icon['name'])){
					//Xóa banner cũ
					if(!empty($a_category['icon']) && file_exists($path.$a_category['icon'])) unlink($path.$a_category['icon']);

					//Up icon mới
					$result_icon = $this->Upload->upload($icon, $path, null, array('type' => 'resizemax', 'size' => $oneweb_product['size']['icon'], 'output' => 'jpg'));
					if($result_icon){
						$icon = $this->Upload->result;

						//Luu ten anh vao ban ghi vua duoc them vao bang products
						$this->ProductCategory->id = $id;
						$this->ProductCategory->set('icon',$icon);
						$this->ProductCategory->save();
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
				$this->Session->setFlash(__('The product category could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->ProductCategory->read(null, $id);
		}
		$parentProductCategories = $this->ProductCategory->ParentProductCategory->find('list');
		$this->set(compact('parentProductCategories'));

		//Danh sach danh muc
		$a_categories_c = $this->ProductCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));
		$this->set(compact('a_categories_c'));
	}


	/**
	 * @Description : Lấy đường dẫn từ mục gốc đến nó
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: string
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _getPath($id=null){
		if($id==null) throw new NotFoundException(__('Invalid'));

		$a_path = $this->ProductCategory->getPath($id,'slug');
		$tmp = '';
		foreach ($a_path as $val){
			$tmp[] = $val['ProductCategory']['slug'];
		}
		$path = implode(',', $tmp);
		return $path;
	}



	/**
	 * @Description : Cập nhật lại đường dẫn của các mục con của mục truyền vào
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function _updatePath($id=null){
		if($id==null) throw new NotFoundException(__('Invalid'));

		//Cập nhật đường dẫn danh mục con và chính nó
		$a_category_ids = $this->ProductCategory->children($id,false,'id');		//Lay id cua tat ca danh muc con

		$a_ids = array($id);			//Id của các danh mục cần cập nhật lại đường dẫn
		if(!empty($a_category_ids)){
			foreach($a_category_ids as $val){
				$a_ids[] = $val['ProductCategory']['id'];
			}
		}

		foreach($a_ids as $val){
			$this->ProductCategory->id = $val;
			$this->ProductCategory->set(array('path'=>$this->_getPath($val)));
			$this->ProductCategory->save();
		}
	}


	/**
	 * @Description : Thay đổi trạng thái danh mục
	 *
	 * @throws 	: NotFoundException
	 * @return 	: json
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxChangeStatus(){
		$this->layout = false;
		$this->autoRender = false;
		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));

		$id = $_POST['id'];		//ID danh muc
		$return = $this->_changeStatusCategory($id,null,true);

		return json_encode($return);
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

		$this->ProductCategory->recursive = -1;
		$a_category = $this->ProductCategory->read("{$_POST['field']} as img",$_POST['id']);
		$a_category = $a_category['ProductCategory'];
		if(!empty($a_category)){
			$oneweb_product = Configure::read('Product');
			$path = realpath($oneweb_product['path']['category']).DS;		//Đường dẫn file ảnh

			$this->ProductCategory->id = $_POST['id'];
			$this->ProductCategory->set(array($_POST['field']=>''));
			if($this->ProductCategory->save()){
				if(!empty($a_category['img']) && file_exists($path.$a_category['img'])) unlink($path.$a_category['img']);
				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		return false;
	}



	/**
	* @Description	: Thay đổi trang thái danh mục
	*
	* @thows	: NotFoundException
	* @param	: int	$id, $status = 1 or 0
	* @param	: boolean $return=true: trả lại mảng dữ liệu sdung khi dùng ajax để gọi
	* @return	: boolean	$return
	* @Author	: Hoang Tuan Anh - tuananh@url.vn
	*/
	private function _changeStatusCategory($id=null,$status=null,$return = false){
		if($id==null) throw new NotFoundException(__('Invalid'));

		$a_all_ids = array($id);
		//Kiểm tra trạng thái hiện tại
		$this->ProductCategory->recursive = -1;
		$a_cate_info = $this->ProductCategory->read('status,parent_id',$id);
		if($status!=null) $active = ($status)?'0':'1';
		else $active = $a_cate_info['ProductCategory']['status'];

		if($active){
			//Bỏ active các danh mục con
			$a_child_ids = $this->ProductCategory->children($id,false,'id');

			foreach($a_child_ids as $val){
				$item = $val['ProductCategory'];
				$a_all_ids[] = $item['id'];
			}
			$status = 0;
		}else{
			//Kiểm tra active danh mục cha
			if(!empty($a_cate_info['ProductCategory']['parent_id'])){
				$a_parent_ids = $this->ProductCategory->getPath($id,'id');
				if(!empty($a_parent_ids))
					foreach($a_parent_ids as $val){
						$item = $val['ProductCategory'];
						if($item['id']!=$id){
							$a_all_ids[] = $item['id'];
						}
					}
			}
			$status = 1;
		}
		$this->ProductCategory->recursive = -1;
		$this->ProductCategory->updateAll(array('status'=>$status),array('ProductCategory.id'=>$a_all_ids));

		$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
		if($return){
			$tmp['cl'] = ($status)?'active':'unactive';
			$tmp['id'] = $a_all_ids;
			return $tmp;
		}
	}

	/**
	 * @Description : Đưa danh mục vào thùng rác
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int $id
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_trash($id){
		$this->layout = false;
		$this->autoRender = false;
		if ($id==null) throw new NotFoundException(__('Invalid'));
		if($this->trash($id)) $this->Session->setFlash('<span>'.__('Danh mục đã được xóa').'</span>','default',array('class'=>'success'));
		else $this->Session->setFlash('<span>'.__('Có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));

		$this->redirect($this->referer());
	}


	/**
	 * @Description : Đưa danh mục và các sản phẩm của nó vào thùng rác
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int data
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function trash($id){
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		$a_cate_info = $this->ProductCategory->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>0),
			'fields'=> array('id','name','trash'),
			'recursive'=>-1
		));
		if(!empty($a_cate_info)){
			$a_cate_ids[] = $id;											//Mang id danh muc hien tai va cac danh muc con cua no
			$a_child_id = array();										//Ghi lại id của các mục con (bài viêt, sản phẩm... - Ko bao gồm danh mục con)

			$a_child_cates = $this->ProductCategory->children($id,false,'id,trash');
			$this->ProductCategory->recursive=-1;

			if(!empty($a_child_cates))
				foreach ($a_child_cates as $val){
					$item_category = $val['ProductCategory'];
					$a_cate_ids[]=$item_category['id'];
				}

			//Tim tat ca san pham thuoc danh mục này và các danh mục con của nó
			$a_products = $this->ProductCategory->Product->find('all',array(
				'conditions'=>array('product_category_id'=>$a_cate_ids,'trash'=>0),
				'fields'=>array('id','trash'),
				'recursive'=>-1
			));

			foreach($a_products as $val){														//Đưa toàn bộ sản phẩm thuộc các danh mục trên vào thùng rác
				$item_product = $val['Product'];
				$a_child_id[] = $item_product['id'];
			}

			//Kiểm tra trong bảng Trash đã có item_id của các danh mục phía trên chưa
			//Nếu có rồi thì lấy toàn bộ thông tin của nó đưa vào bản ghi trash mới, và xóa cái cũ đi (việc xóa cái cũ đi thực hiện sau khi lưu vào Trash thành công)

			$this->loadModel('Trash');

			$a_trashes_old = $this->Trash->find('all',array(
				'conditions'=>array('item_id'=>$a_cate_ids,'model'=>'ProductCategory'),
				'fields'=>array('id','child_id')
			));

			foreach($a_trashes_old as $val){
				$item_trash = $val['Trash'];
				$a_child_id = array_merge($a_child_id,explode(',', $item_trash['child_id']));
				$a_trashes_old_id[] = $item_trash['id'];
			}

			$a_child_id = array_filter($a_child_id);
			sort($a_child_id);

			//Cập nhật vào bảng Trash
			$data['name'] = $a_cate_info['ProductCategory']['name'];
			$data['item_id'] = $a_cate_info['ProductCategory']['id'];
			$data['model'] = 'ProductCategory';
			$data['child_id'] = implode(',', $a_child_id);
			$data['child_model'] = 'Product';
			$description = 'Danh mục sản phẩm';
			if(!empty($a_child_cates)){
				$description.=' (Có '.(count($a_child_cates)).' danh mục con';
				if(!empty($a_child_id)) $description.=' và ';
				else $description.=')';
			}elseif(!empty($a_child_id)) $description.=' (Có ';
			if(!empty($a_child_id)) $description.=count($a_child_id).' sản phẩm)';

			$data['description'] = $description;
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));

			$this->Trash->create();
			if($this->Trash->save($data)){
				//Xóa các bản thi thừa trong bảng Trash
				if(!empty($a_trashes_old_id)) $this->Trash->deleteAll(array('id'=>$a_trashes_old_id));

				//Đưa toàn bộ danh mục vào thùng rác
				$this->ProductCategory->updateAll(array('ProductCategory.trash'=>1),array('ProductCategory.id'=>$a_cate_ids));

				//Đưa toàn bộ sản phẩm thuộc các danh mục trên vào thùng rác
				$this->ProductCategory->Product->updateAll(array('Product.trash'=>1),array('Product.id'=>$a_child_id));

				$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
				return true;
			}
		}
		return false;
	}



	/**
	 * @Description : Di chuyên danh mục đi lên
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id, $delta
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_moveUp($id = null, $delta = null){
        $a_category = $this->ProductCategory->findById($id);
        if (empty($a_category)) throw new NotFoundException(__('Invalid'));

        $this->ProductCategory->id = $a_category['ProductCategory']['id'];

        if ($delta > 0) {
            $this->ProductCategory->moveUp($this->ProductCategory->id, abs($delta));
            $this->Session->write('modified',true);			//Thiết lập y/c xóa cache
	    	$this->Session->setFlash('<span>'.__('Danh mục đã được sắp xếp lại').'</span>','default',array('class'=>'success'));
        } else $this->Session->setFlash('<span>'.__('có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));

		$this->redirect($this->referer());
    }

    /**
     * @Description : Di chuyển danh mục lên trên
     *
     * @throws 	: NotFoundException
     * @param 	: int $id, $delta
     * @return 	: void
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    public function admin_moveDown($id = null, $delta = null) {
    	$a_category = $this->ProductCategory->findById($id);
        if (empty($a_category)) throw new NotFoundException(__('Invalid'));

        $this->ProductCategory->id = $a_category['ProductCategory']['id'];

        if ($delta > 0) {
            $this->ProductCategory->moveDown($this->ProductCategory->id, abs($delta));
            $this->Session->write('modified',true);			//Thiết lập y/c xóa cache
			$this->Session->setFlash('<span>'.__('Danh mục đã được sắp xếp lại').'</span>','default',array('class'=>'success'));
        } else $this->Session->setFlash('<span>'.__('có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error'));

        $this->redirect($this->referer());
    }
}
