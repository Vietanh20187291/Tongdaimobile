<?php
App::uses('AppController', 'Controller');
/**
 * Trashes Controller
 *
 * @property Trash $Trash
 */
class TrashesController extends AppController {
	public $limit_ad = 50;
	public $uses = array('Trash','Product','ProductCategory','ProductMaker','Post','PostCategory','Gallery','GalleryCategory','Video','VideoCategory','Document','DocumentCategory','Banner','Information','Faq','FaqCategory','Advertisement');

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
	 * @Description : Danh sách đối tượng trong thùng rác
	 *
	 * @return 	: void
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_index() {
		$lang = $this->Session->read('lang');

		//Action (Xóa, kích hoạt, bỏ kích hoạt)
		if(!empty($_POST['action']) && !empty($_POST['chkid'])){
			switch ($_POST['action']){
				case 'res':
					foreach ($_POST['chkid'] as $val){
						$this->restoreItem($val);
					}
					$message = __('Bản ghi đã được khôi phục lại');
					break;
				case 'del':
					foreach ($_POST['chkid'] as $val){
// 						$this->Trash->delete($val);
						$this->deleteItem($val);
					}
					$message = __('Xóa thành công');
					break;
			}
			$this->Session->setFlash('<span>'.$message.'</span>','default',array('class'=>'success'));
		}

		//Danh sach hỗ trợ trực tuyến phan trang
		$this->Trash->recursive = -1;
		$this->paginate = array(
			'order'=>array('created'=>'desc','name'=>'asc'),
			'limit'=>$this->limit_ad
		);
		$a_trashes = $this->paginate();
		$this->set('a_trashes_c',$a_trashes);
		//thùng rác sẽ tự động xóa sau 30 ngày
		foreach($a_trashes as $val){
			$current_date = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			if($current_date-$val['Trash']['created'] >(30*24*60*60)) $this->deleteItem($val['Trash']['id']);
		}
		//Url hiện tại
		$current_url = urlencode($this->Oneweb->curPageURL());
		$this->set('current_url_c',$current_url);
	}


	/**
	 * @Description : Khôi phục
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int data
	 * @return 	: Boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxRestoreItem(){
		$this->layout = false;
		$this->autoRender = false;

		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		return $this->restoreItem($_POST['id']);
	}

	/**
	 * @Description : Khôi phục
	 *
	 * @param 	: int $id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function restoreItem($id){
		//Thông tin đối tượng được khôi phục
		$a_trash = $this->Trash->read(null,$id);
		if(empty($a_trash)) return false;

		$item_trash = $a_trash['Trash'];

		$this->$item_trash['model']->recursive = -1;
		$this->$item_trash['model']->id = $item_trash['item_id'];
		$this->$item_trash['model']->set(array('trash'=>0));
		if($this->$item_trash['model']->save()){	//Khôi phục

			//Khôi phục danh mục con của sản phẩm và bài viết
			if($item_trash['model']=='ProductCategory' || $item_trash['model']=='PostCategory'){
				//Tìm danh mục con
				$a_childen = $this->$item_trash['model']->children($item_trash['item_id'],false,'id,trash');
				foreach($a_childen as $val){
					$item_category = $val[$item_trash['model']];
					$this->$item_trash['model']->id = $item_category['id'];
					$this->$item_trash['model']->set(array('trash'=>0));
					$this->$item_trash['model']->save();
				}
			}

			//Khôi phục các con của nó (VD: Sản phẩm, bài viết... -- Không bao gồm danh mục con)
			if(!empty($item_trash['child_id'])){
				foreach(explode(',', $item_trash['child_id']) as $val){
					$this->$item_trash['child_model']->id = $val;
					$this->$item_trash['child_model']->set(array('trash'=>0));
					$this->$item_trash['child_model']->save();

					//Xóa trong bảng trash
					$this->Trash->deleteAll(array('model'=>$item_trash['child_model'],'item_id'=>$val));
				}
			}

			//Xóa trong bảng trash
			$this->Trash->deleteAll(array('model'=>$item_trash['model'],'item_id'=>$item_trash['item_id']));

			$this->Session->write('modified',true);			//Thiết lập y/c xóa cache
			return true;
		}
		return false;
	}

	/**
	 * @Description : Xóa vĩnh viễn
	 *
	 * @throws 	: NotFoundException
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	public function admin_ajaxDeleteItem(){
		$this->layout = false;
		$this->autoRender = false;

		if(empty($_POST['id'])) throw new NotFoundException(__('Invalid'));
		return $this->deleteItem($_POST['id']);
	}

	/**
	 * @Description : Xóa vĩnh viễn
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int data
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteItem($id){
		//Thông tin đối tượng được bị xóa
		$a_trash = $this->Trash->read(null,$id);
		if(empty($a_trash)) return false;

		$item_trash = $a_trash['Trash'];

		$result = $this->deleteCaseItem($item_trash['item_id'], $item_trash['model']);
		if(!empty($item_trash['child_id'])){	//Xóa đối tượng con
			foreach(explode(',', $item_trash['child_id']) as $val)
				$this->deleteCaseItem($val, $item_trash['child_model']);
		}

		$flag_trash = false;
		if($result) $flag_trash = true;
		else{
			//Ktra xem đối tượng này có còn tồn tại trong bảng của nó không, nếu ko thì xóa nó đi
			$this->loadModel($item_trash['model']);
			$check_item = $this->$item_trash['model']->find('count',array('conditions'=>array('id'=>$item_trash['item_id']),'recursive'=>-1));
			if(!$check_item) $flag_trash = true;
		};
		if($flag_trash){
			$this->Trash->deleteAll(array('model'=>$item_trash['model'],'item_id'=>$item_trash['item_id']));
			$result = true;
		}
		return $result;
	}

	/**
	 * @Description :
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int data
	 * @return 	: array
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteCaseItem($id,$model){
		$result = false;
		switch ($model){
			case 'Product':
				$result = $this->deleteProduct($id);
				break;
			case 'ProductCategory':
				$result = $this->deleteProductCategory($id);
				break;
			case 'ProductMaker':
				$result = $this->deleteProductMaker($id);
				break;
			case 'Post':
				$result = $this->deletePost($id);
				break;
			case 'PostCategory':
				$result = $this->deletePostCategory($id);
				break;
			case 'Gallery':
				$result = $this->deleteGallery($id);
				break;
			case 'GalleryCategory':
				$result = $this->deleteGalleryCategory($id);
				break;
			case 'Video':
				$result = $this->deleteVideo($id);
				break;
			case 'VideoCategory':
				$result = $this->deleteVideoCategory($id);
				break;
			case 'Document':
				$result = $this->deleteDocument($id);
				break;
			case 'DocumentCategory':
				$result = $this->deleteDocumentCategory($id);
				break;
			case 'Banner':
				$result = $this->deleteBanner($id);
				break;
			case 'Information':
				$result = $this->deleteInformation($id);
				break;
			case 'Faq':
				$result = $this->deleteFaq($id);
				break;
			case 'FaqCategory':
				$result = $this->deleteFaqCategory($id);
				break;
			case 'Advertisement':
				$result = $this->deleteAdvertisement($id);
				break;
		};
		return $result;
	}




	////////////////////////////////////////
	//////********* SẢN PHẨM ********///////
	////////////////////////////////////////

	/**
	 * @Description : Xóa sản phẩm
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteProduct($id) {
		if(empty($id)) throw new NotFoundException(__('Invalid'));
		$path = realpath(Configure::read('Product.path.product')).DS;		//Đường dẫn file ảnh

		//Lay thong tin san pham
		$this->Product->recursive = -1;
		$a_product = $this->Product->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id','image')
		));

		//Tìm các ảnh còn lại của sp trong bảng product_images
		$a_product_images = $this->Product->ProductImage->find('all',array('conditions'=>array('product_id'=>$id),'recursive'=>-1));


		//Xóa sp khỏi bảng products
		if(!empty($a_product) && $this->Product->delete($id)){
			//Xóa ảnh đại diện của sp
			if(!empty($a_product['Product']['image']) && file_exists($path.$a_product['Product']['image']))
				unlink($path.$a_product['Product']['image']);

			//Xóa ảnh sp
			foreach ($a_product_images as $val){
				if(!empty($val['ProductImage']['image']) && file_exists($path.$val['ProductImage']['image']))
					unlink($path.$val['ProductImage']['image']);
			}

			return true;
		}else return false;
	}



	/**
	 * @Description : Xóa danh mục sản phẩm
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int $id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteProductCategory($id){
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		$this->ProductCategory->recursive = -1;
		$a_cate_info = $this->ProductCategory->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id','image','banner')
		));
		if(!empty($a_cate_info)){
			$path = realpath(Configure::read('Product.path.category')).DS;
			$item_category = $a_cate_info['ProductCategory'];

			$a_cate_ids[] = $id;											//Mang id danh muc hien tai va cac danh muc con cua no

			$a_cate_imgs=array();											//Mang image cua danh muc, bao gom ca danh muc con
			if(!empty($item_category['image'])) $a_cate_imgs[]=$item_category['image'];

			$a_cate_banners=array();											//Mang image banner cua danh muc, bao gom ca danh muc con
			if(!empty($item_category['banner'])) $a_cate_banners[]=$item_category['banner'];

			$a_child_cates = $this->ProductCategory->children($id,false,'id,image,banner');

			if(!empty($a_child_cates)){
				foreach ($a_child_cates as $val){
					$a_cate_ids[]=$val['ProductCategory']['id'];
					if(!empty($val['ProductCategory']['image'])){
						$a_cate_imgs[]=$val['ProductCategory']['image'];
					}
					if(!empty($val['ProductCategory']['banner'])){
						$a_cate_banners[]=$val['ProductCategory']['banner'];
					}
				}
			}

			if($this->ProductCategory->delete($id)){
				//Xoa anh danh muc
				foreach($a_cate_imgs as $val){
					if(!empty($val) && file_exists($path.$val)){
						unlink($path.$val);
					}
				}
				//Xoa banner danh muc
				foreach($a_cate_banners as $val){
					if(!empty($val) && file_exists($path.$val)){
						unlink($path.$val);
					}
				}
				return true;
			}
		}
		return false;
	}


	/**
	 * @Description : Xóa hãng sx
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteProductMaker($id){
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		$this->ProductMaker->recursive = -1;
		$a_maker_info = $this->ProductMaker->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id','image','banner')
		));

		if(!empty($a_maker_info)){
			$path = realpath(Configure::read('Product.path.maker')).DS;
			$item_maker = $a_maker_info['ProductMaker'];

			if($this->ProductMaker->delete($id)){
				//Xoa anh đại diện
				if(!empty($item_maker['image']) && file_exists($path.$item_maker['image'])){
					unlink($path.$item_maker['image']);
				}
				//Xoa banner
				if(!empty($item_maker['banner']) && file_exists($path.$item_maker['banner'])){
					unlink($path.$item_maker['banner']);
				}
				return true;
			}
		}
		return false;
	}



	////////////////////////////////////////
	//////********* BÀI VIẾT ********///////
	////////////////////////////////////////

	/**
	 * @Description : Xóa bài viết
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deletePost($id) {
		if(empty($id)) throw new NotFoundException(__('Invalid'));
		$oneweb_post = Configure::read('Post');
		$path = realpath($oneweb_post['path']['post']).DS;		//Đường dẫn file ảnh

		//Lay thong tin bài viết
		$this->Post->recursive = -1;
		$a_post = $this->Post->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id','image')
		));

		//Xóa bài viết
		if(!empty($a_post) && $this->Post->delete($id)){
			//Xóa ảnh đại diện
			if(!empty($a_post['Post']['image']) && file_exists($path.$a_post['Post']['image']))
				unlink($path.$a_post['Post']['image']);

			return true;
		}else return false;
	}

	/**
	 * @Description : Xóa danh mục bài viết
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int $id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deletePostCategory($id){
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		$this->PostCategory->recursive = -1;
		$a_cate_info = $this->PostCategory->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id','image','banner')
		));
		if(!empty($a_cate_info)){
			$path = realpath(Configure::read('Post.path.category')).DS;
			$item_category = $a_cate_info['PostCategory'];

			$a_cate_ids[] = $id;											//Mang id danh muc hien tai va cac danh muc con cua no

			$a_cate_imgs=array();											//Mang image cua danh muc, bao gom ca danh muc con
			if(!empty($item_category['image'])) $a_cate_imgs[]=$item_category['image'];

			$a_cate_banners=array();											//Mang image banner cua danh muc, bao gom ca danh muc con
			if(!empty($item_category['banner'])) $a_cate_banners[]=$item_category['banner'];

			$a_child_cates = $this->PostCategory->children($id,false,'id,image,banner');

			if(!empty($a_child_cates)){
				foreach ($a_child_cates as $val){
					$a_cate_ids[]=$val['PostCategory']['id'];
					if(!empty($val['PostCategory']['image'])){
						$a_cate_imgs[]=$val['PostCategory']['image'];
					}
					if(!empty($val['PostCategory']['banner'])){
						$a_cate_banners[]=$val['PostCategory']['banner'];
					}
				}
			}

			if($this->PostCategory->delete($id)){
				//Xoa anh danh muc
				foreach($a_cate_imgs as $val){
					if(!empty($val) && file_exists($path.$val)){
						unlink($path.$val);
					}
				}
				//Xoa banner danh muc
				foreach($a_cate_banners as $val){
					if(!empty($val) && file_exists($path.$val)){
						unlink($path.$val);
					}
				}
				return true;
			}
		}
		return false;
	}


	////////////////////////////////////////
	//////********* HÌNH ẢNH ********///////
	////////////////////////////////////////

	/**
	 * @Description : Xóa album ảnh
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteGallery($id) {
		if(empty($id)) throw new NotFoundException(__('Invalid'));
		$path = realpath(Configure::read('Media.path.gallery')).DS;		//Đường dẫn file ảnh

		//Lay thong tin album
		$this->Gallery->recursive = -1;
		$a_gallery = $this->Gallery->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id','image')
		));

		//Tìm các ảnh còn lại của album trong bảng gallery_images
		$a_gallery_images = $this->Gallery->GalleryImage->find('all',array('conditions'=>array('gallery_id'=>$id),'recursive'=>-1));

		//Xóa hình ảnh
		if(!empty($a_gallery) && $this->Gallery->delete($id)){
			//Xóa ảnh đại diện của sp
			if(!empty($a_gallery['Gallery']['image']) && file_exists($path.$a_gallery['Gallery']['image']))
				unlink($path.$a_gallery['Gallery']['image']);

			//Xóa ảnh sp
			foreach ($a_gallery_images as $val){
				if(!empty($val['GalleryImage']['image']) && file_exists($path.$val['GalleryImage']['image']))
					unlink($path.$val['GalleryImage']['image']);
			}

			return true;
		}else return false;
	}

	/**
	 * @Description : Xóa danh mục hình ảnh
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteGalleryCategory($id){
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		$this->GalleryCategory->recursive = -1;
		$a_gallery_info = $this->GalleryCategory->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id')
		));
		if(!empty($a_gallery_info) && $this->GalleryCategory->delete($id)) return true;

		return false;
	}


	////////////////////////////////////////
	//////********** VIDEO **********///////
	////////////////////////////////////////

	/**
	 * @Description : Xóa video
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteVideo($id) {
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		//Lay thong tin video
		$this->Video->recursive = -1;
		$a_video = $this->Video->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id')
		));

		if(!empty($a_video) && $this->Video->delete($id)) return true;
		else return false;
	}

	/**
	 * @Description : Xóa danh mục video
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteVideoCategory($id){
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		$this->VideoCategory->recursive = -1;
		$a_category = $this->VideoCategory->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id')
		));
		if(!empty($a_category) && $this->VideoCategory->delete($id)) return true;

		return false;
	}


	////////////////////////////////////////
	//////********* HÌNH ẢNH ********///////
	////////////////////////////////////////

	/**
	 * @Description : Xóa tài liệu
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteDocument($id) {
		if(empty($id)) throw new NotFoundException(__('Invalid'));
		$path = realpath(Configure::read('Media.path.document')).DS;		//Đường dẫn file ảnh

		//Lay thong tin
		$this->Document->recursive = -1;
		$a_document = $this->Document->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id','file')
		));

		//Xóa tài liệu
		if(!empty($a_document) && $this->Document->delete($id)){
			//Xóa ảnh đại diện của sp
			if(!empty($a_document['Document']['file']) && file_exists($path.$a_document['Document']['file']))
				unlink($path.$a_document['Document']['file']);

			return true;
		}else return false;
	}

	/**
	 * @Description : Xóa danh mục hình ảnh
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteDocumentCategory($id){
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		$this->DocumentCategory->recursive = -1;
		$a_document_info = $this->DocumentCategory->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id')
		));
		if(!empty($a_document_info) && $this->DocumentCategory->delete($id)) return true;

		return false;
	}


	////////////////////////////////////////
	//////********** BANNER *********///////
	////////////////////////////////////////

	/**
	 * @Description : Xóa banner
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteBanner($id) {
		if(empty($id)) throw new NotFoundException(__('Invalid'));
		$path = realpath(Configure::read('Banner.path')).DS;		//Đường dẫn file ảnh

		//Lay thong tin
		$a_banner = $this->Banner->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id','image')
		));

		if(!empty($a_banner) && $this->Banner->delete($id)){
			//Xóa ảnh
			if(!empty($a_banner['Banner']['image']) && file_exists($path.$a_banner['Banner']['image']))
				unlink($path.$a_banner['Banner']['image']);

			return true;
		}else return false;
	}


	////////////////////////////////////////
	//////******* INFORMATION *******///////
	////////////////////////////////////////

	/**
	 * @Description : Xóa banner
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteInformation($id) {
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		//Lay thong tin
		$a_information = $this->Information->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id'),
			'recursive'=>-1
		));

		if(!empty($a_information) && $this->Information->delete($id)) return true;
		else return false;
	}


	////////////////////////////////////////
	///////********** FAQ **********////////
	////////////////////////////////////////

	/**
	 * @Description : Xóa FAQ
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteFaq($id) {
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		//Lay thong tin video
		$this->Faq->recursive = -1;
		$a_faq = $this->Faq->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id')
		));

		if(!empty($a_faq) && $this->Faq->delete($id)) return true;
		else return false;
	}

	/**
	 * @Description : Xóa danh mục video
	 *
	 * @throws 	: NotFoundException
	 * @param 	: int id
	 * @return 	: boolean
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	private function deleteFaqCategory($id){
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		$this->FaqCategory->recursive = -1;
		$a_category = $this->FaqCategory->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id')
		));
		if(!empty($a_category) && $this->FaqCategory->delete($id)) return true;

		return false;
	}

	private function deleteAdvertisement($id){
		if(empty($id)) throw new NotFoundException(__('Invalid'));

		$this->Advertisement->recursive = -1;
		$a_advertisement = $this->Advertisement->find('first',array(
			'conditions'=>array('id'=>$id,'trash'=>1),
			'fields'=>array('id')
		));
		if(!empty($a_advertisement) && $this->Advertisement->delete($id)) return true;

		return false;
	}
}
