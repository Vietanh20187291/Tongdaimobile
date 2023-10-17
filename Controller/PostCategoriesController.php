<?php
App::uses('AppController', 'Controller');
/**
 * PostCategories Controller
 *
 * @property PostCategory $PostCategory
 */
class PostCategoriesController extends AppController {
	public $components = array('Upload');
	private $limit_ad = 50;
	
	
	/********************************************************/
	/********************************************************/
	/********************** Admin ***************************/
	/********************************************************/
	/********************************************************/
	
	/**
	 * @Description : Danh sách danh mục bài viết
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
		
		$a_conditions = array('PostCategory.lang'=>$lang,'PostCategory.trash'=>0);
		
		//Lọc danh mục theo danh mục cha
		if(!empty($_GET['parent_id'])){
			$a_ids = array($_GET['parent_id']);
			$a_children = $this->PostCategory->children($_GET['parent_id'],false,array('id'));
			if(!empty($a_children))
				foreach ($a_children as $val) 
					$a_ids[] = $val['PostCategory']['id'];
			
			$a_conditions = array_merge($a_conditions,array('PostCategory.id'=>$a_ids));
		}
		
		//Danh sach danh muc phan trang
		$this->PostCategory->recursive = 0;
		$this->PostCategory->bindModel(array(
			'belongsTo'=>array('ParentPostCategory' => array(
			'className' => 'PostCategory',
			'foreignKey' => 'parent_id',
			'conditions' => array('ParentPostCategory.trash'=>0),
			'fields' => '',
			'order' => ''
		))
		));
		$this->paginate = array(
			'conditions'=>$a_conditions,
			'fields'=>array('id','name','lang','path','status','position','image','parent_id','counter','link','ParentPostCategory.id'),
			'order'=>array('PostCategory.lft'=>'asc'),
			'limit'=>$this->limit_ad
		);
		$a_post_categories = $this->paginate();
		$this->set('a_post_categories_c',$a_post_categories);
		
		$counter = $this->PostCategory->find('count',array('conditions'=>$a_conditions,'recursive'=>-1));
		$this->set('counter_c',$counter);
		
		//Danh sach danh muc theo dang cay
		$a_post_categories_tree = $this->PostCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));
		$this->set('a_post_categories_tree_c',$a_post_categories_tree);
		
		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
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
			$oneweb_post = Configure::read('Post');
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['PostCategory'];
			
			//Ảnh đại diện
			$file = $data['image'];
			$data['image'] = '';
			
			//Banner
			$banner = $data['banner'];
			$data['banner'] = '';
			
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
			$a_all_slugs = $this->PostCategory->find('list',array('conditions'=>array('lang'=>$lang),'fields'=>'slug'));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			//Position
			if(!empty($data['parent_id'])){
				$a_category_parent = $this->PostCategory->read('position',$data['parent_id']);
				$data['position'] = $a_category_parent['PostCategory']['position'];
			}
			
			//Ngay tao
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			//Ngôn ngữ
			$data['lang'] = $lang;
			
			$this->PostCategory->create();
			if ($this->PostCategory->save($data)) {
				$id = $this->PostCategory->getLastInsertID();
				
				//Cập nhật và chỉnh sửa lại đường dẫn của các danh mục con và chính nó
				$this->_updatePath($id);
				
				$path = realpath($oneweb_post['path']['category']).DS;		//Đường dẫn file ảnh
				
				//Upload image
				if(!empty($file['name'])){
					$result = $this->Upload->upload($file, $path, null, array('type' => 'resizemax', 'size' => $oneweb_post['size']['category'], 'output' => 'jpg'));
					if($result){
						$image = $this->Upload->result;
						
						//Luu ten anh vao ban ghi vua duoc them vao bang posts
						$this->PostCategory->id = $id;
						$this->PostCategory->set('image',$image);
						$this->PostCategory->save();
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
					$result = $this->Upload->upload($banner, $path, null, array('type' => 'resizemax', 'size' => $oneweb_post['size']['category_banner'], 'output' => 'jpg'));
					if($result){
						$image = $this->Upload->result;
						
						//Luu ten anh vao ban ghi vua duoc them vao bang posts
						$this->PostCategory->id = $id;
						$this->PostCategory->set('banner',$image);
						$this->PostCategory->save();
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
		$a_categories_c = $this->PostCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));
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
		$this->PostCategory->id = $id;
		if (!$this->PostCategory->exists()) throw new NotFoundException(__('Invalid'));
		$lang = $this->Session->read('lang');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$oneweb_post = Configure::read('Post');
			$oneweb_seo = Configure::read('Seo');
			$data = $this->request->data['PostCategory'];
			
			$this->PostCategory->recursive = -1;
			$a_category = $this->PostCategory->read('image,banner',$id);
			$a_category = $a_category['PostCategory'];
			
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
			$a_all_slugs = $this->PostCategory->find('list',array('conditions'=>array('lang'=>$lang,'id !='=>$id),'fields'=>'slug','recursive'=>-1));
			
			$data['slug'] = $this->Oneweb->slug($data['slug'],$a_all_slugs);
			
			//Position
			if(!empty($data['parent_id'])){
				$a_category_parent = $this->PostCategory->read('position',$data['parent_id']);
				$data['position'] = $a_category_parent['PostCategory']['position'];
			}
			
			//Ngày sửa
			$data['modified'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			if ($this->PostCategory->save($data)) {
				
				//Cập nhật và chỉnh sửa lại đường dẫn của các danh mục con và chính nó
				$this->_updatePath($id);
				
				$path = realpath($oneweb_post['path']['category']).DS;		//Đường dẫn file ảnh
			
				//Upload image
				if(!empty($file['name'])){
					//Xóa ảnh cũ
					if(!empty($a_category['image']) && file_exists($path.$a_category['image'])) unlink($path.$a_category['image']);
					
					//Up ảnh mới
					$result = $this->Upload->upload($file, $path, null, array('type' => 'resizemax', 'size' => $oneweb_post['size']['category'], 'output' => 'jpg'));
					if($result){
						$image = $this->Upload->result;
						
						//Luu ten anh vao ban ghi vua duoc them vao bang posts
						$this->PostCategory->id = $id;
						$this->PostCategory->set('image',$image);
						$this->PostCategory->save();
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
					$result = $this->Upload->upload($banner, $path, null, array('type' => 'resizemax', 'size' => $oneweb_post['size']['category_banner'], 'output' => 'jpg'));
					if($result){
						$image = $this->Upload->result;
						
						//Luu ten anh vao ban ghi vua duoc them vao bang posts
						$this->PostCategory->id = $id;
						$this->PostCategory->set('banner',$image);
						$this->PostCategory->save();
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
				$this->Session->setFlash(__('The post category could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->PostCategory->read(null, $id);
		}
		$parentPostCategories = $this->PostCategory->ParentPostCategory->find('list');
		$this->set(compact('parentPostCategories'));
		
		//Danh sach danh muc
		$a_categories_c = $this->PostCategory->generateTreeList(array('lang'=>$lang,'trash'=>0));
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
		
		$a_path = $this->PostCategory->getPath($id,'slug');
		$tmp = '';
		foreach ($a_path as $val){
			$tmp[] = $val['PostCategory']['slug'];
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
		$a_category_ids = $this->PostCategory->children($id,false,'id');		//Lay id cua tat ca danh muc con
		
		$a_ids = array($id);			//Id của các danh mục cần cập nhật lại đường dẫn
		if(!empty($a_category_ids)){
			foreach($a_category_ids as $val){
				$a_ids[] = $val['PostCategory']['id'];
			}
		}
		
		foreach($a_ids as $val){
			$this->PostCategory->id = $val;
			$this->PostCategory->set(array('path'=>$this->_getPath($val)));
			$this->PostCategory->save();
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
		
		$this->PostCategory->recursive = -1;
		$a_category = $this->PostCategory->read("{$_POST['field']} as img",$_POST['id']);
		$a_category = $a_category['PostCategory'];
		if(!empty($a_category)){
			$oneweb_post = Configure::read('Post');
			$path = realpath($oneweb_post['path']['category']).DS;		//Đường dẫn file ảnh
			
			$this->PostCategory->id = $_POST['id'];
			$this->PostCategory->set(array($_POST['field']=>''));
			if($this->PostCategory->save()){
				if(!empty($a_category['img']) && file_exists($path.$a_category['img'])) unlink($path.$a_category['img']);
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
		$this->PostCategory->recursive = -1;
		$a_cate_info = $this->PostCategory->read('status,parent_id',$id);
		if($status!=null) $active = ($status)?'0':'1';
		else $active = $a_cate_info['PostCategory']['status'];
		
		if($active){
			//Bỏ active các danh mục con
			$a_child_ids = $this->PostCategory->children($id,false,'id');
			
			foreach($a_child_ids as $val){
				$item = $val['PostCategory'];
				$a_all_ids[] = $item['id'];
			}
			$status = 0;
		}else{
			//Kiểm tra active danh mục cha
			if(!empty($a_cate_info['PostCategory']['parent_id'])){
				$a_parent_ids = $this->PostCategory->getPath($id,'id');
				if(!empty($a_parent_ids))
					foreach($a_parent_ids as $val){
						$item = $val['PostCategory'];
						if($item['id']!=$id){
							$a_all_ids[] = $item['id'];
						}
					}
			}
			$status = 1;
		}
		$this->PostCategory->recursive = -1;
		$this->PostCategory->updateAll(array('status'=>$status),array('PostCategory.id'=>$a_all_ids));
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
		
		$a_cate_info = $this->PostCategory->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>0),
			'fields'=> array('id','name','trash'),
			'recursive'=>-1
		));
		if(!empty($a_cate_info)){
			$a_cate_ids[] = $id;											//Mang id danh muc hien tai va cac danh muc con cua no
			$a_child_id = array();										//Ghi lại id của các mục con (bài viêt, sản phẩm... - Ko bao gồm danh mục con)

			$this->loadModel('Trash');
			
			$a_child_cates = $this->PostCategory->children($id,false,'id,trash');
			$this->PostCategory->recursive=-1;

			if(!empty($a_child_cates))
				foreach ($a_child_cates as $val){
					$item_category = $val['PostCategory'];
					$a_cate_ids[]=$item_category['id'];
				}
			
			//Tim tat ca san pham thuoc danh mục này và các danh mục con của nó
			$a_posts = $this->PostCategory->Post->find('all',array(
				'conditions'=>array('post_category_id'=>$a_cate_ids,'trash'=>0),
				'fields'=>array('id','trash'),
				'recursive'=>-1
			));
			
			foreach($a_posts as $val){														//Đưa toàn bộ sản phẩm thuộc các danh mục trên vào thùng rác
				$item_post = $val['Post'];
				$a_child_id[] = $item_post['id'];
			}
			
			//Kiểm tra trong bảng Trash đã có item_id của các danh mục phía trên chưa
			//Nếu có rồi thì lấy toàn bộ thông tin của nó đưa vào bản ghi trash mới, và xóa cái cũ đi (việc xóa cái cũ đi thực hiện sau khi lưu vào Trash thành công)
			$a_trashes_old = $this->Trash->find('all',array(
				'conditions'=>array('item_id'=>$a_cate_ids,'model'=>'PostCategory'),
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
			$data['name'] = $a_cate_info['PostCategory']['name'];
			$data['item_id'] = $a_cate_info['PostCategory']['id'];
			$data['model'] = 'PostCategory';
			$data['child_id'] = implode(',', $a_child_id);
			$data['child_model'] = 'Post';
			$description = 'Danh mục bài viết';
			if(!empty($a_child_cates)){
				$description.=' (Có '.(count($a_child_cates)).' danh mục con';
				if(!empty($a_child_id)) $description.=' và ';
				else $description.=')';
			}elseif(!empty($a_child_id)) $description.=' (Có ';
			if(!empty($a_child_id)) $description.=count($a_child_id).' bài viết)';
			
			$data['description'] = $description;
			$data['created'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			
			$this->Trash->create();
			if($this->Trash->save($data)){
				//Xóa các bản thi thừa trong bảng Trash
				if(!empty($a_trashes_old_id)) $this->Trash->deleteAll(array('id'=>$a_trashes_old_id));
				
				//Đưa toàn bộ danh mục vào thùng rác
				$this->PostCategory->updateAll(array('PostCategory.trash'=>1),array('PostCategory.id'=>$a_cate_ids));		
				
				//Đưa toàn bộ sản phẩm thuộc các danh mục trên vào thùng rác
				$this->PostCategory->Post->updateAll(array('Post.trash'=>1),array('Post.id'=>$a_child_id));
				
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
        $a_category = $this->PostCategory->findById($id);
        if (empty($a_category)) throw new NotFoundException(__('Invalid'));
        
        $this->PostCategory->id = $a_category['PostCategory']['id'];
        
        if ($delta > 0) {  
            $this->PostCategory->moveUp($this->PostCategory->id, abs($delta));
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
    	$a_category = $this->PostCategory->findById($id);
        if (empty($a_category)) throw new NotFoundException(__('Invalid'));
        
        $this->PostCategory->id = $a_category['PostCategory']['id'];
        
        if ($delta > 0) {  
            $this->PostCategory->moveDown($this->PostCategory->id, abs($delta));
	        $this->Session->write('modified',true);			//Thiết lập y/c xóa cache
			$this->Session->setFlash('<span>'.__('Danh mục đã được sắp xếp lại').'</span>','default',array('class'=>'success')); 
        } else $this->Session->setFlash('<span>'.__('có lỗi, vui lòng thử lại').'</span>','default',array('class'=>'error')); 
		
        $this->redirect($this->referer());
    }
}
